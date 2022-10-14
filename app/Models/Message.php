<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * Message Attributes
     * $this->id - int - contains the message primary key (id)
     * $this->body - string - contains the message body
     * $this->thread_id - int - contains the referenced thread id
     * $this->user_id - int - contains the referenced user id
     * $this->created_at - timestamp - contains the message creation date
     * $this->updated_at - timestamp - contains the message update date
     * $this->thread - Thread - contains the associated Thread
     * $this->user - User - contains the associated User
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'body',
        'thread_id',
        'user_id',
    ];

    /**
     * Relationship
     * Get the thread in which the message is posted.
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Relationship
     * Get the user that posted the message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if current message was created before than X minutes.
     *
     * @param  int  $minutes
     * @return bool
     */
    public function isCreatedAtBeforeThanXMinutes(int $minutes): bool
    {
        $currentTimeMinusXMinutes = Carbon::now()->subMinutes($minutes)->toDateTimeString();

        return $this->created_at >= $currentTimeMinusXMinutes ? true : false;
    }

    /**
     * Find messages by thread id and created before than X minutes.
     *
     * @param  int  $minutes
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public static function findMessagesByThreadIdAndXMinutesAgo(int $threadId, int $minutes): ?Collection
    {
        $currentTimeMinusXMinutes = Carbon::now()->subMinutes($minutes)->toDateTimeString();

        return Message::where('thread_id', '=', $threadId)
                    ->where('created_at', '>=', $currentTimeMinusXMinutes)
                    ->get();
    }

    /**
     * Find messages by user id, thread id, and search term (loosely).
     *
     * @param  int  $userId
     * @param  int  $threadId
     * @param  string  $searchTerm
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public static function findMessagesByUserIdAndThreadIdAndSearchTerm(int $userId, int $threadId, string $searchTerm): ?Collection
    {
        return Message::where('user_id', '=', $userId)
                    ->where('thread_id', '=', $threadId)
                    ->where('body', 'LIKE', '%'.$searchTerm.'%')
                    ->get();
    }
}
