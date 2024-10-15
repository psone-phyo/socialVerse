<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\School;
use App\Models\College;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
                $dataCount = $data->toArray();
                if (count($dataCount) == 0){
                    return response()->json([
                        'data' => 'There is no data yet.',
                        'status' => '200'
                    ],200);
                }
                return response()->json([
                    'data' => $data,
                    'status' => 200
                ],200);
            }else{
                return response()->json([
                    'data' => "the user with id $id is not found",
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
            $data = $this->getdata($request);
            //validation
            $validation = $this->validation($data, 'create');
            if ($validation->fails()) {
                $errors = collect($validation->errors()->toArray())
                ->map(function ($error) {
                    return $error[0];  // Get the first error message only
                });
                return response()->json(['error' => $errors, 'status' => 400], 400);
            }

            //photo saved
            if (isset($data['profile'])) {
                $imagename = uniqid() . $data['profile'];
                $imagepath = 'profile/' . $imagename;
                Storage::disk('public')->put($imagepath, $imagename);
                $data['profile'] = $imagename;
            }

            $data = User::create($data);
            return response()->json([
                'data' => $data,
                'status' => '201'
            ],201);
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
                if (Storage::disk('public')->exists('profile/' . $deletedData->profile)) {
                    Storage::disk('public')->delete('profile/' . $deletedData->profile);
                }
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

    public function update(Request $request)
    {
        try {
            $data = $this->getdata($request);
            $data['id'] = $request->id;
            //validation
            $validation = $this->validation($data, 'update');
            if ($validation->fails()) {
                $errors = collect($validation->errors()->toArray())
                ->map(function ($error) {
                    return $error[0];  // Get the first error message only
                });
                return response()->json(['error' => $errors, 'status' => 400], 400);
            }
            //photo saved
            if (isset($data['profile'])) {
                $oldphoto = User::select('profile')->where('id', $data['id'])->first();
                $oldphoto = $oldphoto->getOriginal();
                if (Storage::disk('public')->exists('profile/' . $oldphoto['profile'])) {
                    Storage::disk('public')->delete('profile/' . $oldphoto['profile']);
                }
                $imagename = uniqid() . $data['profile'];
                $imagepath = 'profile/' . $imagename;
                Storage::disk('public')->put($imagepath, $imagename);
                $data['profile'] = $imagename;
            }

            User::find($request->id)->update($data);
            $data = User::find($request->id);
            return response()->json([
                'updatedData' => $data,
                'status' => '201'
            ],201);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e,
                'status' => 500
            ], 500);
        }
    }

    //get the data in key value format for database
    private function getdata($request){
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
        return Arr::where($data, function ($value) {
            return !is_null($value);
        });

    }

    //validation function for user profile
    private function validation($data, $action)
    {
        $rules = [];
        if ($action == 'create'){
            $rules = [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required|min:8|max:12',
            ];

        }else{
            $rules = [
                'id' => 'required|integer|exists:users,id'
            ];
        }
        if (isset($data['phone'])){
            $rules['phone'] = 'required|numeric|min_digits:8|max_digits:15';
        }

        return Validator::make($data, $rules);
    }


}
