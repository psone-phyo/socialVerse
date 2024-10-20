<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Like;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    public function like(Request $request){
        try{
            $validation = Validator::make([
                'post_id' => $request->post_id,
                'user_id' => $request->user_id,
            ],[
                'post_id' => 'required|integer|exists:posts,id',
                'user_id' => 'required|integer|exists:users,id'
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
            Like::create([
                'post_id' => $request->post_id,
                'user_id' => $request->user_id,
            ]);
            $poster = Post::select('user_id')->where('id', $request->post_id)->first();
            Notification::create([
                'user_id' => $poster->user_id,
                'message' => 'Your post is liked by ',
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

    public function dislike(Request $request){
        try{
            $validation = Validator::make([
                'post_id' => $request->post_id,
                'user_id' => $request->user_id,
            ],[
                'post_id' => 'required|integer|exists:posts,id',
                'user_id' => 'required|integer|exists:users,id'
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
            Like::where([
                ['user_id', $request->user_id],
                ['post_id', $request->post_id],
            ])->delete();
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
