<?php

namespace App\Jobs;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The thread id and message minute
     *
     * @var string
     */
    public $threadIdAndMessageMinute;

    /**
     * Job dispatch
     *
     * @var bool
     */
    public $dispatch = false;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $threadIdAndMessageMinute)
    {
        $this->threadIdAndMessageMinute = $threadIdAndMessageMinute;
        $jobs = Job::all();
        error_log('\n Hi creating job');
        //error_log($jobs);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        error_log('\n Hi processing job');
        $jobs = Job::all();
        if (count($jobs) > 0) {
            foreach ($jobs as $job) {
                $command = $job->payload['data']['command'];
                $commandUnserialized = unserialize($command);
                $threadIdAndMessageMinute = $commandUnserialized->threadIdAndMessageMinute;

                error_log(print_r($threadIdAndMessageMinute, true));
            }
        }
        //
    }
}
