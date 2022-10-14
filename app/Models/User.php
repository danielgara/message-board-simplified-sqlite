<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * User Attributes
     * $this->id - int - contains the user primary key (id)
     * $this->name - string - contains the user name
     * $this->email - string - contains the user email
     * $this->email_verified_at - timestamp - contains the user email verification date
     * $this->password - string - contains the user password
     * $this->remember_token - string - contains the user remember token
     * $this->bio - string - contains the user bio
     * $this->created_at - timestamp - contains the user creation date
     * $this->updated_at - timestamp - contains the user update date
     * $this->messages - Messages[] - contains the associated messages
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relationship
     * Get the messages for the user.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Check if the received id matches with the current user id.
     *
     * @param  int  $id
     * @return bool
     */
    public function isCurrentUser(int $id): bool
    {
        return $this->id == $id ? true : false;
    }
}
