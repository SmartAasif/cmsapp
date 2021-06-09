<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $comments = Comment::with('posts')->paginate(10);
        return response(['comments' => $comments]);
    }

    public function postComments($post_id)
    {
        $comments = Comment::where('post_id', $post_id)->get();
        return response(['comments' => $comments, 'post_id' => $post_id]);
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
            'post_id' => 'required',
            'description' => 'required'
        ]);

        $commet =Comment::create(
            $request->all());
        return response(["message"=>"Comment created successfully","comment"=>$commet],200);
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
        $commet=Comment::find($id);
        return response(["message"=>"success","data"=>$commet]);
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
        $comment=Comment::find($id);
        $comment->update($request->all());
        return response(["messag"=>"comment updated successfully","updated"=>$comment],200) ;
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
        Comment::find($id)->delete();
        return response(["message"=>"comment deleted successfully"],200);
    }
}
