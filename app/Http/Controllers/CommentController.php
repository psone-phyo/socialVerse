<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function create(Request $request){
        try{
            $data = [
                'post_id' => $request->post_id,
                'user_id' => $request->user_id,
                'comment' => $request->comment
            ];
            $validation = Validator::make($data,[
                'post_id' => 'required|integer|exists:posts,id',
                'user_id' => 'required|integer|exists:users,id',
                'comment' => 'required'
            ]);
            if ($validation->fails()){
                $errors = collect($validation->errors()->toArray())
                        ->map(function($error){
                            return $error[0];
                        });
                return response()->json([
                    'error' => $errors,
                    'status' => 400
                ],400);
            }
            Comment::create($data);
            $poster = Post::select('user_id')->where('id', $request->post_id)->first();
            Notification::create([
                'user_id' => $poster->user_id,
                'message' => 'Your post is commented by ',
                'sender_id' => $request->user_id,
                'seen' => 0
            ]);
            return response()->json([
                'status' => 204
            ],204);
        }catch(Exception $e){
            return response()->json([
                'error' => $e,
                'status' => 500
            ],500);
        }
    }

    public function get($post_id){
        try{
            $validation = Validator::make([
                'post_id' => $post_id
            ],[
                'post_id' => 'required|integer|exists:posts,id',
            ]);
            if ($validation->fails()){
                $errors = collect($validation->errors()->toArray())
                        ->map(function($error){
                            return $error[0];
                        });
                return response()->json([
                    'error' => $errors,
                    'status' => 400
                ],400);
            }
            $data = Comment::select('users.name','users.profile', 'comments.id as comment_id', 'comments.user_id', 'comments.post_id', 'comments.comment', 'comments.created_at', 'comments.updated_at')
                    ->leftjoin('users', 'users.id', 'comments.user_id')
                    ->where('comments.post_id', $post_id)
                    ->orderby('comments.created_at', 'desc')
                    ->get();
            return response()->json([
                'data' => $data,
                'status' => 200
            ],200);
        }catch(Exception $e){
            return response()->json([
                'error' => $e,
                'status' => 500
            ],500);
        }
    }

    public function delete($comment_id){
        try{
            $validation = Validator::make([
                'id' => $comment_id
            ],[
                'id' => 'required|integer|exists:comments,id',
            ]);
            if ($validation->fails()){
                $errors = collect($validation->errors()->toArray())
                        ->map(function($error){
                            return $error[0];
                        });
                return response()->json([
                    'error' => $errors,
                    'status' => 400
                ],400);
            }
            Comment::find($comment_id)->delete();
            return response()->json([
                'status' => 204
            ],204);
        }catch(Exception $e){
            return response()->json([
                'error' => $e,
                'status' => 500
            ],500);
        }
    }

    /**
     * update
     * @param comment_id, comment
     */
    public function update(Request $request){
        try{
            $data = [
                'comment_id' => $request->comment_id,
                'comment' => $request->comment
            ];
            $validation = Validator::make($data,[
                'comment_id' => 'required|integer|exists:comments,id',
                'comment' => 'required'
            ]);
            if ($validation->fails()){
                $errors = collect($validation->errors()->toArray())
                        ->map(function($error){
                            return $error[0];
                        });
                return response()->json([
                    'error' => $errors,
                    'status' => 400
                ],400);
            }
            Comment::find($request->comment_id)->update(['comment' => $request->comment]);
            return response()->json([
                'status' => 204
            ],204);
        }catch(Exception $e){
            return response()->json([
                'error' => $e,
                'status' => 500
            ],500);
        }
    }
}
