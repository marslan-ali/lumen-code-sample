<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Validators\ValidatesAuthenticationRequests;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers
 * @author  Arslan Ali
 * @email   marslan.ali@gmail.com
 */
class AuthController extends Controller
{
    use ValidatesAuthenticationRequests;
    
    /**
     * Login user and return the user is successful.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $request->all()['user'];

        if (! Auth::once($credentials)) {
            return $this->respondFailedLogin();
        }

        return new UserResource(Auth::user());
    }

    /**
     * Register a new user and return the user if successful.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validateRegister($request);

        $user = User::create([
            'username' => $request->input('user.username'),
            'email' => $request->input('user.email'),
            'password' => Hash::make($request->input('user.password')),
        ]);

        return (new UserResource($user))->response()->header('Status', 201);
    }

    /**
     * Respond with failed login.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondFailedLogin()
    {
        return $this->respond([
            'errors' => [
                'email or password' => ['is invalid'],
            ]
        ], 422);
    }
}
