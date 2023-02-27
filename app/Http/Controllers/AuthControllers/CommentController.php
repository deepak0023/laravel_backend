<?php

namespace App\Http\Controllers\AuthControllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\AuthModel\Comment;
use App\Models\AuthModel\Article;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Check for authentication.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Article $article)
    {
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
    public function store(Request $request, Article $article)
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
    public function show(Article $article, Comment $comment)
    {
        $article_comment = $article->comment()->where('cm_id', $$comment->cm_id)->first();

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
    public function update(Request $request, Article $article, Comment $comment)
    {
        try {
            $this->authorize('update', $comment);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to do this action'
            ], 403);
        }

        $data = [];

        if(!empty($request->input('title'))) $data['cm_title'] = $request->input('title');
        if(!empty($request->input('description'))) $data['cm_description'] = $request->input('description');

        $article->comment()->where('cm_id', $comment->cm_id)->update($data);

        return response()->json([
            "message" => "Comment item updated successfully",
            "data"    => $article->comment()->where('cm_id', $comment->cm_id)->first()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article, Comment $comment)
    {
        try {
            $this->authorize('delete', $comment);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to do this action'
            ], 403);
        }

        $article->comment()->where('cm_id', $comment->cm_id)->delete();

        return response()->json([
            "message" => "Comment item deleted successfully",
        ], 200);
    }
}
