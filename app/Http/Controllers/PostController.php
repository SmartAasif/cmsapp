<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use Illuminate\Http\Request;

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
        $posts = Post::with('user')->paginate(10);
        return response(['posts' => $posts]);
    }
    
    public function userPosts($userid)
    {
        $posts = Post::where('user_id', $userid)->get();
        return response(['posts' => $posts, 'user_id' => $userid]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $this->validate($request, [
            'user_id' => 'required',
            'title' => 'required',
            'description' => 'required'
        ]);

        $post =Post::create(
            $request->all());
        $image =new Image();
        $image->url = $request->image;
        $image->imageable_type = $image->imageable;
        $image->imageable_id = $request->user_id;
        $image->is_deleted = 0;
        $post->image()->save($image);
        return response(["message"=>"Post created successfully","post"=>$post]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post=Post::with('comments')->find($id);
        $image =Post::find($id);
        return response(["message"=>"success","data"=>$post,"image"=>$image]);
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
        $post = Post::find($id);
        // return view('postlist', compact('post'));
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
        $post=Post::find($id);
        $post->update($request->all());
        return response(["messag"=>"post updated successfully","updated"=>$post],200) ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Post::find($id)->delete();
        return response(["message"=>"post deleted successfully"],200);
    }
}
