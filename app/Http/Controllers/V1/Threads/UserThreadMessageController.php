<?php

namespace App\Http\Controllers\V1\Threads;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Http\Requests\Message\UpdateMessageRequest;
use App\Http\Resources\MessageResource;
use App\Jobs\ProcessMessageJob;
use App\Models\Message;
use App\Models\Thread;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/**
 * This class is responsible for managing all api/v1/user/* actions.
 */
class UserThreadMessageController extends BaseController
{
    /**
     * Create a message in storage and return json response with message.
     *
     * @param  \App\Http\Requests\Message\StoreMessageRequest  $request
     * @param  \App\Models\User  $user
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\JsonResponse
     */
    public function createMessage(StoreMessageRequest $request, User $user, Thread $thread): JsonResponse
    {
        $message = Message::create(array_merge(
            $request->validated(),
            [
                'user_id' => $user->id,
                'thread_id' => $thread->id,
            ],
        ));

        $newJob = new ProcessMessageJob($thread->id);
        if ($newJob->threadId != 0) {
            $processMessageJob = $newJob->delay(Carbon::now()->addMinutes(1));
            dispatch($processMessageJob);
        }

        $responseData = [];
        $responseData['message'] = new MessageResource($message);

        return $this->sendResponse($responseData, 201);
    }

    /**
     * Return json response with threads in which user has participated.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserThreads(User $user): JsonResponse
    {
        $threads = Thread::findThreadsByUserId($user->id);
        $responseData = [];
        if (count($threads) == 0) {
            $responseData['message'] = 'Threads not found';

            return $this->sendResponseError($responseData);
        }

        $responseData['threads'] = $threads;

        return $this->sendResponse($responseData);
    }

    /**
     * Update message body and return json response.
     *
     * @param  \App\Http\Requests\Message\UpdateMessageRequest  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMessage(UpdateMessageRequest $request, Message $message): JsonResponse
    {
        $responseData = [];
        if (! $message->isCreatedAtBeforeThanXMinutes(5)) {
            $responseData['message'] = 'User not allowed to update this message. Has passed more than five minutes since creation';

            return $this->sendResponseError($responseData, 401);
        }

        $message->body = $request->body;
        $message->save();

        $responseData['message'] = 'Message updated';

        return $this->sendResponse($responseData);
    }
}
