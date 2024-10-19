<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Friendship;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FriendshipController extends Controller
{
    public function addFriend(Request $request)
    {
        try {
            $data = [
                'user_id' => $request->user_id,
                'friend_id' => $request->friend_id,
                'status' => 'pending'
            ];
            $validation = Validator::make($data, [
                'user_id' => 'required|integer|exists:users,id',
                'friend_id' => 'required|integer|exists:users,id|different:user_id',
            ]);
            if ($validation->fails()) {
                $errors = collect($validation->errors()->toArray())
                    ->map(function ($error) {
                        return $error[0];  // Get the first error message only
                    });
                return response()->json([
                    'error' => $errors,
                    'status' => 404
                ], 404);
            }
            $data = Friendship::create($data);
            Notification::create([
                'user_id' => $request->friend_id,
                'message' => 'You have a friend request from ',
                'seen' => '0',
                'sender_id' => $request->user_id
            ]);
            return response()->json([
                'status' => 204
            ], 204);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e,
                'status' => 500
            ], 500);
        }
    }

    /**
     * accept the friend request
     * @param user_id(who gets friend request), friend_id(who sends friend request),
     *         notification_id(for noti table)
     */

    public function acceptFriend(Request $request)
    {
        try {
            $data = $this->arrangeData($request);
            $validation = $this->validation($data);

            if ($validation->fails()) {
                $errors = $this->returnvalidation($validation);
                return response()->json([
                    'error' => $errors,
                    'status' => 404
                ], 404);
            }
            Friendship::where('user_id', $request->friend_id)
                ->where('friend_id', $request->user_id)
                ->update([
                    'status' => 'friends'
                ]);
            Friendship::create([
                    'user_id' => $request->user_id,
                    'friend_id' => $request->friend_id,
                    'status' => 'friends'
                ]);
            Notification::find($request->notification_id)->update(['seen' => 1]);
            Notification::create([
                'user_id' => $request->friend_id,
                'message' => 'Your friend request is accepted by ',
                'seen' => 0,
                'sender_id' => $request->user_id
            ]);

            return response()->json([
                'status' => 204
            ], 204);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e,
                'status' => 500
            ], 500);
        }
    }

    /**
     * reject the friend request
     * @param user_id(who gets friend request), friend_id(who sends friend request),
     *         notification_id(for noti table)
     */

    public function rejectFriend(Request $request)
    {
        try {
            $data = $this->arrangeData($request);
            $validation = $this->validation($data);

            if ($validation->fails()) {
                $errors = $this->returnvalidation($validation);
                return response()->json([
                    'error' => $errors,
                    'status' => 404
                ], 404);
            }
            Friendship::where('user_id', $request->friend_id)
                ->where('friend_id', $request->user_id)
                ->delete();
            Notification::find($request->notification_id)->update(['seen' => 1]);
            Notification::create([
                'user_id' => $request->friend_id,
                'message' => 'Your friend request is rejected by ',
                'seen' => 0,
                'sender_id' => $request->user_id
            ]);

            return response()->json([
                'status' => 204
            ], 204);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e,
                'status' => 500
            ], 500);
        }
    }

    /**
     * reject the friend request
     * @param user_id(who gets friend request), friend_id(who sends friend request),
     *         notification_id(for noti table)
     */

     public function unfriend(Request $request)
     {
         try {
             $data = [
                'user_id' => $request->user_id,
                'friend_id' => $request->friend_id,
             ];
             $validation = Validator::make($data, [
                'user_id' => 'required|integer|exists:users,id',
                'friend_id' => 'required|integer|exists:users,id|different:user_id',
            ]);;

             if ($validation->fails()) {
                 $errors = $this->returnvalidation($validation);
                 return response()->json([
                     'error' => $errors,
                     'status' => 404
                 ], 404);
             }
             Friendship::where('user_id', $request->user_id)
                 ->where('friend_id', $request->friend_id)
                 ->delete();
             Friendship::where('user_id', $request->friend_id)
                 ->where('friend_id', $request->user_id)
                 ->delete();
             return response()->json([
                 'status' => 204
             ], 204);
         } catch (Exception $e) {
             return response()->json([
                 'error' => $e,
                 'status' => 500
             ], 500);
         }
     }

    private function arrangeData($request){
        return [
            'notification_id' => $request->notification_id,
            'user_id' => $request->user_id,
            'friend_id' => $request->friend_id,
            'status' => 'friends'
        ];
    }

    private function validation ($data){
        return Validator::make($data, [
            'notification_id' => 'required|integer|exists:notifications,id',
            'user_id' => 'required|integer|exists:users,id',
            'friend_id' => 'required|integer|exists:users,id|different:user_id',
        ]);
    }

    private function returnvalidation($validation){
        return collect($validation->errors()->toArray())
                    ->map(function ($error) {
                        return $error[0];  // Get the first error message only
                    });
    }
}
