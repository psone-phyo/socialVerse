<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    // public function storeImage(Request $request){
    //     try{
    //         foreach ($request->image as $image){
    //             $validation = Validator::make(['image' => $image], [
    //                 'image' => 'mimes:mp4,mkv,avi,qt,jpg,jpeg,png,gif,bmp.tiff,tif,webp,svg,avif|max:20480'
    //             ]);
    //         }
    //         if ($validation->fails()){
    //             $errors = collect($validation->errors()->toArray())->map(function($error){
    //                 return $error[0];
    //             });
    //             return response()->json([
    //                 'error' => $errors,
    //                 'status' => 400
    //             ],400);
    //         }

    //         dd('ok');
    //     }catch(Exception $e){
    //         return response()->json([
    //             'error' => $e,
    //             'status' => 500
    //         ],500);
    //     }
    // }
}
