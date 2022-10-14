<?php

namespace App\Http\Controllers\V1\Threads;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Message\SearchMessageRequest;
use App\Http\Requests\Thread\StoreThreadRequest;
use App\Http\Resources\ThreadResource;
use App\Models\Message;
use App\Models\Thread;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * This class is responsible for managing all api/v1/threads/* actions.
 */
class ThreadController extends BaseController
{
    /**
     * Create a thread in storage and return json response with thread.
     *
     * @param  \App\Http\Requests\Thread\StoreThreadRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(StoreThreadRequest $request): JsonResponse
    {
        $thread = Thread::create($request->validated());

        $responseData = [];
        $responseData['thread'] = new ThreadResource($thread);

        return $this->sendResponse($responseData, 201);
    }

    /**
     * Return json response with messages find by thread id.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages(Thread $thread): JsonResponse
    {
        $messages = Message::where('thread_id', '=', $thread->id)->orderBy('created_at', 'desc')->get();
        $responseData = [];
        if (count($messages) == 0) {
            $responseData['message'] = 'Thread have not messages';

            return $this->sendResponseError($responseData);
        }

        $responseData['messages'] = $messages;

        return $this->sendResponse($responseData);
    }

    /**
     * Return json response with messages of current user, find by thread id and search term.
     *
     * @param  \App\Http\Requests\Message\SearchMessageRequest  $request
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchUserThreadMessages(SearchMessageRequest $request, Thread $thread): JsonResponse
    {
        $userId = Auth::user()->id;
        $messages = Message::findMessagesByUserIdAndThreadIdAndSearchTerm($userId, $thread->id, $request->search_term);

        $responseData = [];
        if (count($messages) == 0) {
            $responseData['message'] = 'Messages not found';

            return $this->sendResponseError($responseData);
        }

        $responseData['messages'] = $messages;

        return $this->sendResponse($responseData);
    }
}
