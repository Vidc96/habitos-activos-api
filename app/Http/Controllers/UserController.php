<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $adminUser = User::where('id', $id)
                        ->where('role', 'admin')
                        ->first();

        if (!$adminUser) {

            return response()->json(['error' => 'You do not have permission to access this feature.'], 403);
        }

        $users = User::where('role', 'user')
                    ->get(['name', 'email']);

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['message' => 'User created successfully'], 201);
    }

    /**
     * Store a newly search resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $data = $request->json()->all();
        $email = $data['email'];
        $password = $data['password'];
    
        $user = User::where('email', $email)->first();
    
        if ($user && Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Login successful'], 200);
        } else {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id, ['name', 'email']);

        return response()->json($user);
    }

    /**
     * Display the user ID matching the provided email address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showByEmail(Request $request, $id)
    {
        $adminUser = User::where('id', $id)
                        ->where('role', 'admin')
                        ->first();

        if (!$adminUser) {
            return response()->json(['error' => 'You do not have permission to access this feature.'], 403);
        }

        $email = $request->json('email');

        $user = User::where('email', $email)
                    ->first(['id']);

        if (!$user) {
            return response()->json(['error' => 'No user was found with the provided email.'], 404);
        }

        return response()->json($user);
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
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'password' => 'required|min:6',
        ]);

        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();

        $data = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        return response()->json(['message' => 'User updated successfully', 'data' => $data]);
    }

    /** 
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}
