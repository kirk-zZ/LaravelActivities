<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $posts = \App\Models\Post::get();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (Auth::check()) {
            // The user is logged in...
            return view('posts.create');
        }else{
        return redirect('/posts') ->with('alert', 'Login first!');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // dd($request);
        $request->validate([
            'title' => 'required|unique:posts|max:255',
            'description' => 'required'
        ]);
        
        
        if($request->hasFile('img')){

            $request->validate([
                'img' => 'mimes:jpeg,bmp,png,jpeg' // Only allow .jpg, .bmp and .png file types.
            ]);

            $filenameWithExt = $request->file('img')->getClientOriginalName();

            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            $extension = $request->file('img')->getClientOriginalExtension();

            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            $path = $request->file('img')->storeAs('public/img', $fileNameToStore);
        } else{
            $fileNameToStore = '';
        }

        $post = new Post();
        $post->fill($request->all());
        $post->img = $fileNameToStore;
        $post->user_id = auth()->user()->id;
        if($post->save()){
            $message = "Successfully save";
        }
        return redirect('/posts');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = \App\Models\Post::find($id);
        $comments = $post->comments;
        return view('posts.show', compact('post','comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        if(Auth::check()){
        $post = \App\Models\Post::find($id);
        return view('posts.edit', compact('post'));
        }else{
            return redirect('/posts') ->with('alert', 'Login first!');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
        if(Auth::check()){
        $post = \App\Models\Post::find($id);
        $post->title = $request->title;
        $post->description = $request->description;
        $post->save();

        return redirect('/posts');
        }else{
            return redirect('/posts') ->with('alert', 'Login first!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if(Auth::check()){
        $post = \App\Models\Post::find($id);
        $post->delete();

        return redirect('/posts');
        }else{
            return redirect('/posts') ->with('alert', 'Login first!');
        }
    }
}
