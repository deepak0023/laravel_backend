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
        try {
            $this->authorize('viewAny', User::class);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to perform this action'
            ], 403);
        }

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
    public function show(User $user)
    {
        try {
            $this->authorize('view', $user);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to perform this action'
            ], 403);
        }

        $user = User::where('id', $user->id)->first();

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
    public function update(Request $request, User $user)
    {
        try {
            $this->authorize('update', $user);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to perform this action'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'unique:users,email,'.$user->id,
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

        $user->update($data);

        return response()->json([
            "message" => "User updated successfully",
            "data"    => $user->where('id', $user->id)->get()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $this->authorize('delete', $user);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to perform this action'
            ], 403);
        }

        $user->delete();

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
    public function setUserRole(Request $request, User $user)
    {
        try {
            $this->authorize('set_user_role');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are unauthorized to perform this action'
            ], 403);
        }

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

        $user->update(['user_rl_id' => $request->role_id]);

        return response()->json([
            "message" => "User role id updated successfully",
            "data" => $user
        ], 200);
    }
}
