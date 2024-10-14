<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function get($id = null){
        try{
            if ($id == null){
                $data = User::all();
            }else{
                $data = User::find($id);
            }

            if ($data != null){
                return response()->json([
                    'data' => $data,
                    'status' => 200
                ],200);
            }else{
                return response()->json([
                    'data' => "the id $id is not found",
                    'status' => 400
                ],400);
            }
        }catch(Exception $e){
            return response()->json([
                'data' => $e,
                'status' => '500'
            ],500);
        }

    }

    public function create(Request $request)
    {
        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->phone,
                'profile' => $request->profile,
                'bio' => $request->bio,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'hometown_id' => $request->hometown_id,
                'city_id' => $request->city_id,
                'country_id' => $request->country_id,
                'work_at' => $request->work_at,
                'job_id' => $request->job_id,
                'school_id' => $request->school_id,
                'college_id' => $request->college_id,
                'university_id' => $request->university_id,
            ];
            $validation = $this->validation(($data));
            if ($validation->fails()) {
                return response()->json(['error' => $validation->errors(), 'status' => 400], 400);
            }
            if ($data['profile'] != null){
                $data['profile']->store('/profile');
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => $e,
                'status' => 500
            ], 500);
        }
    }

    public function delete($id = null){
        try{
            if ($id == null){
                return response()->json([
                    'error' => 'No id is given to delete.',
                    'status' => 400
                ],400);
            }
            $deletedData = User::find($id);
            if ($deletedData != null){
                $deletedData->delete();
                return response()->json([
                    'deletedData' => $deletedData,
                    'status' => 200
                ],200);
            }else{
                return response()->json([
                    'data' => "the id $id is not found",
                    'status' => 400
                ],400);
            }
        }catch(Exception $e){
            return response()->json([
                'error' => $e,
                'status' => 500
            ],500);
        }
    }


    private function validation($data)
    {
        return Validator::make($data, [
            'name' => 'required',
            'email' => 'required|unique',
            'password' => 'required|min:8|max:12',
            'phone' => 'numeric',
        ]);
    }


}
