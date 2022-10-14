<?php

namespace App\Jobs;

use App\Models\Job;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The thread id
     * It will be stored in the job payload
     *
     * @var int
     */
    public $threadId = 0;

    /**
     * Create a new ProcessMessageJob instance.
     *
     * @param  int  $threadId
     * @return void
     */
    public function __construct(int $threadId)
    {
        $jobs = Job::all();

        /**
         * If there are not current pending jobs continue with the job creation
         * and assigned it the proper threadId
         */
        if (count($jobs) == 0) {
            $this->threadId = $threadId;

            return;
        }

        /**
         * Check if there is a pending job for the current thread id (based on the payload information)
         * if there is a current job for the current thread id, the job will be skipped
         * otherwise, the job will be created
         */
        $threadActive = false;
        foreach ($jobs as $job) {
            $command = $job->payload['data']['command'];
            $commandUnserialized = unserialize($command);
            $jobThreadId = $commandUnserialized->threadId;

            if ($threadId == $jobThreadId) {
                $threadActive = true;
                break;
            }
        }

        if (! $threadActive) {
            $this->threadId = $threadId;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * Obtain the messages that has been created in the last minute
         * for the current executed job thread id, then, collect the
         * user emails and console log the messages
         */
        $messages = Message::findMessagesByThreadIdAndXMinutesAgo($this->threadId, 1);
        $numMessages = count($messages);
        $userEmails = $messages->pluck('user.email')->unique()->values()->all();

        // To fix: Better implementation with Laravel Storage or Log
        error_log(' ');
        error_log("---THREADS NEWS FOR THREAD: $this->threadId---");
        foreach ($userEmails as $userEmail) {
            error_log("Hey $userEmail - there are $numMessages new messages in Thread $this->threadId");
        }
    }
}
