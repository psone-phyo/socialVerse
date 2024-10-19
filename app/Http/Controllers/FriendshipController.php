<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    public function addFriend(){
        try{
            
        }catch(Exception $e){
            return response()->json([
                'error' => $e,
                'status' => 500
            ],500);
        }
    }
}
