<?php

namespace App\Http\Controllers\AuthControllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\AuthModel\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Check for authentication.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            "message" => "successfully fetched all users data",
            "data"    => $users
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
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required'
        ], [
            'name.required' => 'The name is required',
            'email.required' => 'The email is required',
            'email.unique' => 'The email already exists in the system',
            'password' => 'Password is required',
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ]);
        }

        $password = $request->input('password');

        // Get input values
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($password)
        ];

        $user = User::create($data);

        return response()->json([
            "message" => "User successfully created",
            "data"    => $user
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
        if(User::where('id', $id)->exists()) {
            $user = User::where('id', $id)->first();
        } else {
            return response()->json([
                "message" => "No user for the mentioned id",
                "data" => []
            ], 200);
        }

        return response()->json([
            "message" => "success",
            "data"    => $user
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
        $validator = Validator::make($request->all(), [
            'email' => 'unique:users,email,'.$id,
        ], [
            'email.unique' => 'The email already exists in the system',
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ]);
        }

        $data = [];

        if(!empty($request->input('name'))) $data['name'] = $request->input('name');
        if(!empty($request->input('email'))) $data['email'] = $request->input('email');
        if(!empty($request->input('password'))) $data['password'] = bcrypt($request->input('password'));

        if(User::where('id', $id)->exists()) {
            User::where('id', $id)->update($data);
        } else {
            return response()->json([
                "message" => "No user for the mentioned id",
                "data" => []
            ], 200);
        }

        $user_data = User::where('id', $id)->get();

        return response()->json([
            "message" => "User updated successfully",
            "data"    => $user_data
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
        if(User::where('id', $id)->exists()) {
            User::where('id', $id)->delete();
        } else {
            return response()->json([
                "message" => "No User for the mentioned id",
                "data" => []
            ], 200);
        }

        return response()->json([
            "message" => "User deleted successfully",
        ], 200);
    }

    /**
     * Update role id for user [intentded for admin operation].
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setUserRole(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'in:1,2'
        ], [
            'role_id.in' => 'Incorrect role id value',
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ]);
        }

        $role_id = $request->input('role_id');

        if(User::where('id', $id)->exists()) {
            User::where('id', $id)->update(['user_rl_id' => $role_id]);
        } else {
            return response()->json([
                "message" => "No User for the mentioned id",
                "data" => []
            ], 200);
        }

        return response()->json([
            "message" => "User role id updated successfully",
            "data" => User::where('id', $id)->first()
        ], 200);
    }
}
