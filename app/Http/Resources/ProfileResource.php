<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class ProfileResource
 * @package App\Http\Resources
 * @author  Arslan Ali
 * @email   marslan.ali@gmail.com
 */
class ProfileResource extends Resource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'profile';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'username'  => $this->username,
            'bio'       => $this->bio,
            'image'     => $this->image,
            'following' => $this->following,
        ];
    }
}
