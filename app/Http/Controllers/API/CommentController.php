<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required',
            'comment' => 'required',
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = Auth::user();

        $post_id = $request->post_id;
        $post = Post::find($post_id);

        if($post){
            $data = [
                'user_id' => $user->id,
                'post_id' => $post_id,
                'comment' => $request->comment
            ];

            // create comment
            $comment = Comment::create($data);

            return response()->json(['status' => true, 'data' => $comment]);
        }
        else{
            return response()->json(['status' => false, 'message' => 'no post found']);
        }
    }

    public function read(Request $request)
    {
        $user = Auth::user();

        if($user->role == 'writer'){
            $post = Post::where('user_id', $user->id)->with('comments')->get();
        }

        if($user->role == 'editor'){
            $post = Post::with('comments')->get();
        }

        return response()->json(['status' => true, 'data' => $post]);
    }
}
