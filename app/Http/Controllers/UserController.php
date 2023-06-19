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

        $users = User::all();
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
            return response()->json(['id' => $user->id ], 200);
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
    public function showRole($id)
    {
        $user = User::findOrFail($id, ['role']);
        $role = $user->role;
        return response()->json($role);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showUser($id)
    {
        $user = User::findOrFail($id);
        $data = [
            'name' => $user->name,
            'email' => $user->email,
        ];
        return response()->json($data);
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
        $validatedData = [];
        if ($request->filled('name') && $request->filled('current_password')) {
            $validatedData['name'] = $request->input('name');
        }
        if ($request->filled('email') && $request->filled('current_password')) {
            $validatedData['email'] = $request->input('email');
        }
        if ($request->filled('password') && $request->filled('current_password')) {
            $currentPassword = $request->input('current_password');
            if (Hash::check($currentPassword, $user->password)) {
                $validatedData['password'] = bcrypt($request->input('password'));
            } else {
                return response()->json(null, 400);
            }
        }
    
        if (!empty($validatedData)) {
            $user->update($validatedData);
        }
    
        return response()->json(null, 204);
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
