<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// import model Post
use App\Models\Post;
// import untuk validasi
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    //

    // function untuk menampikan semua data
    public function index()
    {
        $posts = Post::latest()->get();

        // make response JSON
        return response()->json([
            'data'    => $posts,
            'message' => 'List Data Post',
            'success' => true
        ], 200);
    }

    // function menampilkan sesuai id
    public function show($id)
    {
        // finc post by Id
        $post = Post::firstWhere('id', $id);

        if ($post) {
            // make response JSON
            return response()->json([
                'data'    => $post,
                'message' => 'Detail Data Post',
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'data'    => $post,
                'message' => 'Detail Data Post Not Found',
                'success' => false
            ], 200);
        }
    }

    public function store(Request $request)
    {
        // set validation 
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);

        // response error validation 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // save to database
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        // success save to database
        if ($post) {

            return response()->json([
                'data'    => $post,
                'message' => 'Post Created',
                'success' => true
            ], 201);
        }

        // failed save to database
        return response()->json([
            'message' => 'Post Failed to Save',
            'success' => false
        ], 409);
    }

    public function update(Request $request, $id)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'title'   => 'required',
            'content' => 'required'
        ]);

        // response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        // find post by ID
        $post = Post::findOrfail($id);

        if ($post) {

            // update post
            $post->update([
                'title'   => $request->title,
                'content' => $request->content
            ]);

            return response()->json([
                'data'    => $post,
                'message' => 'Post Updated',
                'success' => true
            ], 200);
        }

        // data post not found
        return response()->json([
            'message' => 'Post Not Found',
            'success' => false
        ], 404);
    }

    public function destroy($id)
    {
        // find post by id
        $post = Post::findOrfail($id);

        if ($post) {

            // delete post
            $post->delete();

            return response()->json([
                'message' => 'Post Deleted',
                'success' => true
            ]);
        }

        return response()->json([
            'message' => 'Post Not Found',
            'success' => false
        ], 404);
    }
}
