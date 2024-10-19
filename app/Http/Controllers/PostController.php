<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Post;
use App\Models\college;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    public function get($user_id){
        try{
        $myPosts = Post::select('users.id as user_id', 'users.name as user_name', 'users.profile' ,'posts.id  as post_id', 'posts.content', 'posts.privacy', 'posts.created_at', 'posts.updated_at')
                        ->leftjoin('users', 'users.id', 'posts.user_id')
                        ->where('user_id', $user_id)
                        ->orderby('created_at', 'desc')
                        ->get();

        $friendPosts = Post::select('users.id as user_id', 'users.name as user_name', 'users.profile' ,'posts.id as post_id', 'posts.content', 'posts.privacy', 'posts.created_at', 'posts.updated_at')
                    ->leftjoin('friendships', 'friendships.friend_id', 'posts.user_id')
                    ->leftjoin('users', 'users.id', 'posts.user_id')
                    ->where([
                        ['friendships.user_id', $user_id],
                        ['friendships.status', 'friends'],
                        ['posts.privacy', '!=' , 'private']
                    ])
                    ->orderby('posts.updated_at', 'desc')
                    ->get();
        return response()->json([
            'myPosts' => $myPosts,
            'friendPosts' => $friendPosts,
            'status' => 200
        ],200);
        }catch(Exception $e){
            return response()->json([
                'error' => $e,
                'status' => 500
            ],500);
        }
    }

    public function store(Request $request){
        try{
            $data = $this->arrangeData($request);
            $validation = $this->validation($data);
            if ($validation->fails()){
                $errors = $this->returnvalidation($validation);
                return response()->json([
                    'error' => $errors,
                    'status' => '400'
                ],400);
            }
            $data = Post::create($data);
            return response()->json([
                'data' => $data,
                'stauts' => 201
            ],201);
        }catch(Exception $e){
            return response()->json([
                'error' => $e,
                'status' => 500
            ],500);
        }
    }

    public function update(Request $request){
        try{
            $data = [
                'id' => $request->id,
                'user_id' => $request->user_id,
                'content' => $request->content,
                'privacy' => $request->privacy
            ];
            $validation = Validator::make($data, [
                'id' => 'required|integer|exists:posts,id',
                'user_id' => 'required|integer|exists:users,id',
                'privacy' => 'required'
            ]);
            if ($validation->fails()){
                $errors = $this->returnvalidation($validation);
                return response()->json([
                    'error' => $errors,
                    'status' => '400'
                ],400);
            }
            Post::find($request->id)->update($data);
            $data = Post::find($request->id);
            return response()->json([
                'data' => $data,
                'stauts' => 200
            ],200);
        }catch(Exception $e){
            return response()->json([
                'error' => $e,
                'status' => 500
            ],500);
        }
    }

    public function delete($id){
        try{
            $validation = Validator::make(['id' => $id],[
                'id' => "required|integer|exists:posts,id"
            ]);
            if ($validation->fails()){
                $errors = $this->returnvalidation($validation);
                return response()->json([
                    'error' => $errors,
                    'status' => 400
                ],400);
            }
            Post::find($id)->delete();
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

    private function returnvalidation($validation){
        return collect($validation->errors()->toArray())->map(function($error){
            return $error[0];
        });
    }

    private function arrangeData($request){
        return [
            'user_id' => $request->user_id,
            'content' => $request->content,
            'privacy' => $request->privacy
        ];
    }

    private function validation($data){
        return Validator::make($data, [
            'user_id' => 'required|integer|exists:users,id',
            'privacy' => 'required'
        ]);
    }
}
