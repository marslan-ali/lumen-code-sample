<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Validators\ValidatesUserRequests;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 * @author  Arslan Ali
 * @email   marslan.ali@gmail.com
 */
class UserController extends Controller
{
    use ValidatesUserRequests;

    /**
     * UserController constructor.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new UserResource(Auth::user());
    }

    /**
     * Update the authenticated user and return the user if successful.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $this->validateUpdate($request);

        $user = Auth::user();
        if ($request->has('user')) {
            $user->update($request->get('user'));
        }

        return new UserResource($user);
    }
}
