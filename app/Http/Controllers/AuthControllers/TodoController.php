<?php

namespace App\Http\Controllers\AuthControllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\AuthModel\Todo;
use App\Http\Controllers\Controller;

class TodoController extends Controller
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
        try {
            $this->authorize('viewAny', Todo::class);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to perform this action'
            ], 403);
        }

        $todos = Todo::all();
        return response()->json([
            "message" => "successfully fetched all todos data",
            "data"    => $todos
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
        $validator = Todo::make($request->all(), [
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
            'td_title' => $request->input('title'),
            'td_user_id' => $user->id,
            'td_description' => $request->input('description'),
            'td_status' => 'o'
        ];

        $todo = Todo::create($data);

        return response()->json([
            "message" => "Todo successfully created",
            "data"    => $todo
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        try {
            $this->authorize('view', $todo);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to perform this action'
            ], 403);
        }

        return response()->json([
            "message" => "success",
            "data"    => $todo
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        try {
            $this->authorize('update', $todo);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to perform this action'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'in:o,p,c'
        ], [
            'status.in' => 'Incorrect status value',
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ]);
        }

        if(!empty($request->input('title'))) $data['td_title'] = $request->input('title');
        if(!empty($request->input('description'))) $data['td_description'] = $request->input('description');
        if(!empty($request->input('status'))) $data['td_status'] = $request->input('status');

        Todo::where('td_id', $todo->id)->update($data);

        $todo_data = Todo::where('td_id', $todo->id)->get();

        return response()->json([
            "message" => "Todo updated successfully",
            "data"    => $todo_data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        try {
            $this->authorize('delete', $todo);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to perform this action'
            ], 403);
        }

        Todo::where('td_id', $todo->td_id)->delete();

        return response()->json([
            "message" => "Todo deleted successfully",
        ], 200);
    }
}
