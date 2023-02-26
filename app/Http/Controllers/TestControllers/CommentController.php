<?php

namespace App\Http\Controllers\TestControllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\TestModel\Comment;
use App\Models\TestModel\Article;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($ar_id)
    {
        $article =  Article::where('ar_id', $ar_id)->first();

        return response()->json([
            "message" => "successfully fetched all comments for the article",
            "data"    => $article->comment()->get()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $ar_id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ], [
            'title.required' => 'The title param value is required',
            'description.required' => 'The description param value is required',
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ]);
        }

        $article = Article::where('ar_id', $ar_id)->first();

        // Get input values
        $comment = new Comment([
            'cm_title' => $request->input('title'),
            'cm_description' => $request->input('description'),
        ]);

        $article_comment = $article->comment()->save($comment);

        return response()->json([
            "message" => "Comment item successfully created for the todo",
            "data"    => $article_comment
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($ar_id, $id)
    {
        if(Comment::where('cm_id', $id)->exists()) {

            $article = Article::where('ar_id', $ar_id)->with('comment')->first();
            $article_comment = $article->comment()->where('cm_id', $id)->first();

        } else {
            return response()->json([
                "message" => "No Comment item for the mentioned id",
                "data" => []
            ], 200);
        }

        return response()->json([
            "message" => "success",
            "data"    => $article_comment
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ar_id, $id)
    {
        $data = [];

        if(!empty($request->input('title'))) $data['cm_title'] = $request->input('title');
        if(!empty($request->input('description'))) $data['cm_description'] = $request->input('description');

        if(Comment::where('cm_id', $id)->exists()) {

            $article = Article::where('ar_id', $ar_id)->first();

            $article_comment = $article->comment()->where('cm_id', $id)->update($data);

        } else {
            return response()->json([
                "message" => "No comment item for the mentioned id",
                "data" => []
            ], 200);
        }

        return response()->json([
            "message" => "Comment item updated successfully",
            "data"    => $article->comment()->where('cm_id', $id)->first()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($ar_id, $id)
    {
        if(Comment::where('cm_id', $id)->exists()) {

            $article = Article::where('ar_id', $ar_id)->first();

            $article->comment()->where('cm_id', $id)->delete();

        } else {
            return response()->json([
                "message" => "No comment item for the mentioned id",
                "data" => []
            ], 200);
        }

        return response()->json([
            "message" => "Comment item deleted successfully",
        ], 200);
    }
}
