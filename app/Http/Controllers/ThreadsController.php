<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return \Illuminate\Http\Response
     * @internal param null $channelSlug
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
           'title' => 'required',
           'body' => 'required',
           'channel_id' => 'required|exists:channels,id',
        ]);

        $thread = Thread::create([
            'user_id' =>auth()->id(),
            'channel_id' =>request('channel_id'),
            'title' =>request('title'),
            'body' =>request('body')
        ]);

        return redirect($thread->path());
    }

    /**
     * Display the specified resource.
     *
     * @param $channelId
     * @param  \App\Thread $thread
     * @return Thread
     */
    public function show($channelId, Thread $thread)
    {   
        // return $thread->load('replies');
        // return $thread->withCount('replies')->first();
        return view('threads.show',[
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(25)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $channel
     * @param Thread $thread
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if(\request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }

    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists)
        {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->get();
    }

}
