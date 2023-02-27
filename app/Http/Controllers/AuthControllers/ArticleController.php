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
    public function show(Article $article)
    {
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
    public function update(Request $request, Article $article)
    {
        try {
            $this->authorize('update', $article);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to do this action'
            ], 403);
        }

        if(!empty($request->input('title'))) $data['ar_title'] = $request->input('title');
        if(!empty($request->input('description'))) $data['ar_description'] = $request->input('description');

        Article::where('ar_id', $article->ar_id)->update($data);

        $article_data = Article::where('ar_id', $article->ar_id)->get();

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
    public function destroy(Article $article)
    {
        try {
            $this->authorize('delete', $article);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to do this action'
            ], 403);
        }

        $article->delete();

        return response()->json([
            "message" => "Article deleted successfully",
        ], 200);
    }
}
