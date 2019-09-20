<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Http\Resources\ProfileResource;

/**
 * Class ProfileController
 *
 * @package App\Http\Controllers
 * @author  Arslan Ali
 * @email   marslan.ali@gmail.com
 */
class ProfileController extends Controller
{

    /**
     * ProfileController constructor.
     *
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'show']);
        $this->middleware('auth:optional', ['only' => 'show']);
    }

    /**
     * Get the profile of the user given by their username
     *
     * @param string $username
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {
        $user = $this->getUserByName($username);
        return new ProfileResource($user);
    }

    /**
     * Follow the user given by their username and return the user if successful.
     *
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow(string $username)
    {
        $user = $this->getUserByName($username);
        Auth::user()->follow($user);
        return new ProfileResource($user);
    }

    /**
     * Unfollow the user given by their username and return the user if successful.
     *
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function unFollow(string $username)
    {
        $user = $this->getUserByName($username);
        Auth::user()->unFollow($user);
        return new ProfileResource($user);
    }

    /**
     * Retrieve user by their username
     * @param  string $username
     * @return \App\Models\User
     */
    protected function getUserByName(string $username)
    {
        if (! $user = User::whereUsername($username)->first()) {
            abort(404, "User ${username} not found");
        }
        return $user;
    }
}
