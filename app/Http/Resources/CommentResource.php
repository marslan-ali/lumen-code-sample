<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class CommentResource
 *
 * @package App\Http\Resources
 * @author  Arslan Ali
 * @email   marslan.ali@gmail.com
 */
class CommentResource extends Resource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'comment';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'body'      => $this->body,
            'createdAt' => $this->created_at->toAtomString(),
            'updatedAt' => $this->updated_at->toAtomString(),
            'author' => [
                'username'  => $this->author->username,
                'bio'       => $this->author->bio,
                'image'     => $this->author->image,
                'following' => $this->author->following,
            ]
        ];
    }

    /**
     * Create new resource collection.
     *
     * @param  mixed  $resource
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        $collection = parent::collection($resource)->collection;
        if ($collection->count() > 1) {
            return [Str::plural(self::$wrap) => $collection];
        }
        // This is according to API specs, but Postman collection gives an error:
        return [self::$wrap => $collection->first()];
    }
}
