<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($td_id)
    {
        $comments = Comment::where('cm_td_id', $td_id)->get();
        return response()->json([
            "message" => "successfully fetched all comments for the todo",
            "data"    => $comments
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $td_id)
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
        $data = [
            'cm_td_id' => (int) $td_id,
            'cm_title' => $request->input('title'),
            'cm_description' => $request->input('description'),
        ];

        $comment = Comment::create($data);

        return response()->json([
            "message" => "Comment item successfully created for the todo",
            "data"    => $comment
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($td_id, $id)
    {
        if(Comment::where('cm_id', $id)->exists()) {
            $comment = Comment::where('cm_id', $id)->get();
        } else {
            return response()->json([
                "message" => "No Comment item for the mentioned id",
                "data" => []
            ], 200);
        }

        return response()->json([
            "message" => "success",
            "data"    => $comment
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $td_id, $id)
    {
        $data = [];

        if(!empty($request->input('title'))) $data['cm_title'] = $request->input('title');
        if(!empty($request->input('description'))) $data['cm_description'] = $request->input('description');

        if(Comment::where('cm_id', $id)->exists()) {
            Comment::where('cm_id', $id)->update($data);
        } else {
            return response()->json([
                "message" => "No comment item for the mentioned id",
                "data" => []
            ], 200);
        }

        $comment_data = Comment::where('cm_id', $id)->get();

        return response()->json([
            "message" => "Comment item updated successfully",
            "data"    => $comment_data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($td_id, $id)
    {
        if(Comment::where('cm_id', $id)->exists()) {
            Comment::where('cm_id', $id)->delete();
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