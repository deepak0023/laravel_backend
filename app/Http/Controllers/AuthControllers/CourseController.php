<?php

namespace App\Http\Controllers\AuthControllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AuthModel\Course;
use App\Models\AuthModel\User;
use App\Http\Controllers\Controller;

class CourseController extends Controller
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
        $courses = Course::all();
        return response()->json([
            "message" => "successfully fetched all courses data",
            "data"    => $courses
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
        try {
            $this->authorize('create', Course::class);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to perform this action'
            ], 403);
        }

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

        // Get input values [we need to get user id from jwt]
        $data = [
            'cr_title' => $request->input('title'),
            'cr_description' => $request->input('description')
        ];

        $course = Course::create($data);

        return response()->json([
            "message" => "Course successfully created",
            "data"    => $course
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        try {
            $this->authorize('view', $course);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to perform this action'
            ], 403);
        }

        return response()->json([
            "message" => "success",
            "data"    => $course
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        try {
            $this->authorize('update', $course);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to perform this action'
            ], 403);
        }

        if(!empty($request->input('title'))) $data['cr_title'] = $request->input('title');
        if(!empty($request->input('description'))) $data['cr_description'] = $request->input('description');

        $course->update($data);

        return response()->json([
            "message" => "Course updated successfully",
            "data"    => $course->where('cr_id', $course->cr_id)->first()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        try {
            $this->authorize('delete', $course);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to perform this action'
            ], 403);
        }

        $course->delete();

        return response()->json([
            "message" => "course deleted successfully",
        ], 200);
    }

    /**
     * Register user functionality [many to many]
     *
     * @param [type] $cr_id
     * @return void
     */
    public function registerUser(Course $course) {

        try {
            $this->authorize('register_course');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to do this action'
            ], 403);
        }

        $user = User::find(auth()->user()->id);

        $course->user()->attach($user);

        return response()->json([
            "message" => "registered user successfully",
            "data" => $user->course()->get()
        ], 200);
    }

    /**
     * Unregister user functionality [many to many]
     *
     * @param [type] $cr_id
     * @return void
     */
    public function unregisterUser(Course $course) {

        try {
            $this->authorize('unregister_course');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to do this action'
            ], 403);
        }

        $user = User::find(auth()->user()->id);

        $course->user()->detach($user);

        return response()->json([
            "message" => "unregistered user successfully",
            "data" => $user->course()->get()
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserCourseList()
    {
        try {
            $this->authorize('list_user_courses', Todo::class);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to perform this action'
            ], 403);
        }

        $user = User::find(auth()->user()->id);

        return response()->json([
            "message" => "successfully fetched all todos data",
            "data"    => $user->course()->get()
        ], 200);
    }
}
