<?php

namespace App\Http\Controllers\AuthControllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\AuthModel\Article;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
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
    public function index()
    {
        $articles = Article::all();
        return response()->json([
            "message" => "successfully fetched all articles data",
            "data"    => $articles
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

        $user = auth()->user();

        // Get input values [we need to get user id from jwt]
        $data = [
            'ar_title' => $request->input('title'),
            'ar_user_id' => $user->id,
            'ar_description' => $request->input('description')
        ];

        $article = Article::create($data);

        return response()->json([
            "message" => "Article successfully created",
            "data"    => $article
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Article::where('ar_id', $id)->exists()) {
            $article = Article::where('ar_id', $id)->first();
        } else {
            return response()->json([
                "message" => "No article for the mentioned id",
                "data" => []
            ], 200);
        }

        return response()->json([
            "message" => "success",
            "data"    => $article
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!empty($request->input('title'))) $data['ar_title'] = $request->input('title');
        if(!empty($request->input('description'))) $data['ar_description'] = $request->input('description');

        if(Article::where('ar_id', $id)->exists()) {
            Article::where('ar_id', $id)->update($data);
        } else {
            return response()->json([
                "message" => "No article for the mentioned id",
                "data" => []
            ], 200);
        }

        $article_data = Article::where('ar_id', $id)->get();

        return response()->json([
            "message" => "Article updated successfully",
            "data"    => $article_data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Article::where('ar_id', $id)->exists()) {
            Article::where('ar_id', $id)->delete();
        } else {
            return response()->json([
                "message" => "No article for the mentioned id",
                "data" => []
            ], 200);
        }

        return response()->json([
            "message" => "Article deleted successfully",
        ], 200);
    }
}
