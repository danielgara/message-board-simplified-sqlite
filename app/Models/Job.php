<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    /**
     * Job Attributes
     * $this->id - int - contains the job primary key (id)
     * $this->queue - string - contains the job queue
     * $this->payload - longtext - contains the job payload
     * $this->attemps - int - contains the job attemps
     * $this->reserved_at - timestamp - contains the job reservation date
     * $this->created_at - timestamp - contains the job creation date
     * $this->available_at - timestamp - contains the job available date
     */

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'payload' => 'array',
    ];
}
