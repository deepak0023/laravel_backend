<?php

namespace App\Http\Controllers\TestControllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\TestModel\Course;
use App\Models\TestModel\User;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
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
    public function show($id)
    {
        if(Course::where('cr_id', $id)->exists()) {
            $course = Course::where('cr_id', $id)->first();
        } else {
            return response()->json([
                "message" => "No course for the mentioned id",
                "data" => []
            ], 200);
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
    public function update(Request $request, $id)
    {
        if(!empty($request->input('title'))) $data['cr_title'] = $request->input('title');
        if(!empty($request->input('description'))) $data['cr_description'] = $request->input('description');

        if(Course::where('cr_id', $id)->exists()) {
            Course::where('cr_id', $id)->update($data);
        } else {
            return response()->json([
                "message" => "No course for the mentioned id",
                "data" => []
            ], 200);
        }

        $course_data = Course::where('cr_id', $id)->get();

        return response()->json([
            "message" => "Course updated successfully",
            "data"    => $course_data
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
        if(Course::where('cr_id', $id)->exists()) {
            Course::where('cr_id', $id)->delete();
        } else {
            return response()->json([
                "message" => "No course for the mentioned id",
                "data" => []
            ], 200);
        }

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
    public function registerUser($cr_id) {

        $user_id = 1; // need to get from auth gurd in production
        $user = User::find($user_id);

        $course = Course::where('cr_id', $cr_id)->first();

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
    public function unregisterUser($cr_id) {

        $user_id = 1; // need to get from auth gurd in production
        $user = User::find($user_id);

        $course = Course::where('cr_id', $cr_id)->first();

        $course->user()->detach($user);

        return response()->json([
            "message" => "unregistered user successfully",
            "data" => $user->course()->get()
        ], 200);
    }
}
