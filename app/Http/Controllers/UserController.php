<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\CreateLibrarianRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->all('email', 'password'))) {
            $user = auth()->user();
            $token = $user->createToken('AUTH', [$user->role])->plainTextToken;

            return respondSuccess("Login successful", [
                "user" => $user,
                "token" => $token,
            ]);
        }

        return respondError("Email or password incorrect", 401);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $limit = request("limit", 15);

        $users = User::paginate($limit);
        return respondSuccess("Users", $users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        $token = $user->createToken("AUTH", [UserRole::MEMBER])->plainTextToken;

        return respondSuccess("User successfully created", [
            "user" => $user,
            "token" => $token,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return respondSuccess("User", $user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if (auth()->user()->role === UserRole::ADMIN || auth()->user()->id === $user->id) {
            $user->update($request->all());

            return respondSuccess("User successfully updated", $user);
        }
        return respondError("Forbidden", 403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return respondSuccess("User successfully deleted");
    }

    public function createLibrarian(CreateLibrarianRequest $request)
    {
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "role" => UserRole::LIBRARIAN,
        ]);


        return respondSuccess("User successfully created", $user, 201);
    }
}
