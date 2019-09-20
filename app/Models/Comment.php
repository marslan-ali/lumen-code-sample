<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Comment
 *
 * @package App\Models
 * @author  Arslan Ali
 * @email   marslan.ali@gmail.com
 */
class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'author_id'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'author'
    ];

    /**
     * Get the author of the comment.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
