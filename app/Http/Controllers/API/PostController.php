<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\API\PostRequest;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Post;

class PostController extends Controller
{
    public function create(PostRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'post' => 'required',
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = Auth::user();

        $data = [
            'user_id' => $user->id,
            'title' => $request->title,
            'post' => $request->post
        ];

        // create post
        $post = Post::create($data);

        return response()->json(['status' => true, 'data' => $data]);
    }

    public function update(PostRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'post' => 'required',
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $id = $request->id;

        $post = Post::find($id);
        $user = Auth::user();

        if($post && $post->user_id == $user->id){
            $data = [
                'title' => $request->title,
                'post' => $request->post
            ];

            $post->update($data);

            return response()->json(['status' => true, 'data' => $data]);
        }
        else{
            return response()->json(['status' => false]);
        }
    }

    public function read(PostRequest $request)
    {
        $id = $request->id;

        $post = Post::find($id);
        $user = Auth::user();

        if($post && $post->user_id == $user->id){
            return response()->json(['status' => true, 'data' => $post]);
        }
        else{
            return response()->json(['status' => false]);
        }
    }

    public function delete(PostRequest $request)
    {
        $id = $request->id;

        $post = Post::find($id);
        $user = Auth::user();

        if($post && $post->user_id == $user->id){
            $post->delete();

            return response()->json(['status' => true]);
        }
        else{
            return response()->json(['status' => false]);
        }
    }
}
