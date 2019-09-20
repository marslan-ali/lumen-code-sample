<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class UserResource
 *
 * @package App\Http\Resources
 * @author  Arslan Ali
 * @email   marslan.ali@gmail.com
 */
class UserResource extends Resource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'user';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'email'     => $this->email,
            'token'     => $this->token,
            'username'  => $this->username,
            'bio'       => $this->bio,
            'image'     => $this->image,
        ];
    }
}
