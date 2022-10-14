<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    /**
     * Thread Attributes
     * $this->id - int - contains the thread primary key (id)
     * $this->title - string - contains the thread title
     * $this->created_at - timestamp - contains the thread creation date
     * $this->updated_at - timestamp - contains the thread update date
     * $this->messages - Messages[] - contains the associated messages
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
    ];

    /**
     * Relationship
     * Get the messages for the thread.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Find threads by user id.
     *
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public static function findThreadsByUserId(int $userId): ?Collection
    {
        return Thread::whereHas('messages', function (Builder $query) use ($userId) {
            $query->where('user_id', '=', $userId);
        })->get();
    }
}
