<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * get notifications by user_id
     */
    public function get($user_id){
        try{
            $data = Notification::select('notifications.id','notifications.user_id','notifications.message','notifications.seen', 'notifications.sender_id', 'notifications.created_at', 'users.name',
            'users.profile')
            ->leftjoin('users', 'users.id', 'notifications.sender_id')
            ->where('user_id', $user_id)
            ->orderby('notifications.created_at', 'desc')
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


        /**
     * change status of notification (mark as read)
     */
    public function readNotification(Request $request){
        try{
            $validation = Validator::make([
                'id' => $request->id
            ], [
                'id' => 'required|exists:notifications,id|integer',
            ]);
            if ($validation->fails()){
                $errors = $this->returnvalidation($validation);
                return response()->json([
                    'error' => $errors,
                    'status' => 400
                ],400);
            }
            Notification::find($request->id)->update(['seen' => 1]);
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
}
