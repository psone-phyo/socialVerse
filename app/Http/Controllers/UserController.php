<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\School;
use App\Models\College;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function get($id = null)
    {
        try {
            if ($id == null) {
                $data = User::all();
            } else {
                $data = User::find($id);
            }

            if ($data != null) {
                $dataCount = $data->toArray();
                if (count($dataCount) == 0) {
                    return response()->json([
                        'data' => 'There is no data yet.',
                        'status' => '200'
                    ], 200);
                }
                return response()->json([
                    'data' => $data,
                    'status' => 200
                ], 200);
            } else {
                return response()->json([
                    'data' => "the user with id $id is not found",
                    'status' => 400
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'data' => $e,
                'status' => '500'
            ], 500);
        }
    }


    public function imagestore(Request $request)
    {
        try {
            $imagename = uniqid() . '_sv_' . $request->file('profile')->getClientOriginalName();
            $request->file('profile')->storeAs('profile', $imagename, 'public');
            return response()->json([
                'data' => "/storage/profile/" . $imagename,
                'status' => 200
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => $e,
                'status' => '500'
            ], 500);
        }

    }

    public function create(Request $request)
    {
        try {
            $data = $this->getdata($request, 'create');
            //validation
            $validation = $this->validation($data, 'create');
            if ($validation->fails()) {
                $errors = $this->returnvalidation($validation);
                return response()->json(['error' => $errors, 'status' => 400], 400);
            }
            $data = User::create($data);
            return response()->json([
                'data' => $data,
                'status' => '201'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e,
                'status' => 500
            ], 500);
        }
    }

    public function delete($id = null)
    {
        try {
            if ($id == null) {
                return response()->json([
                    'error' => 'No id is given to delete.',
                    'status' => 400
                ], 400);
            }
            $deletedData = User::find($id);
            if ($deletedData != null) {
                $deletedData->delete();
                if ($deletedData->profile){
                    $filePath = public_path($deletedData->profile);
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                }
                return response()->json([
                    'deletedData' => $deletedData,
                    'status' => 200
                ], 200);
            } else {
                return response()->json([
                    'data' => "the id $id is not found",
                    'status' => 400
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => $e,
                'status' => 500
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $data['id'] = $request->id;
            $data += $this->getdata($request);
            //validation
            $validation = $this->validation($data, 'update');
            if ($validation->fails()) {
                $errors = $this->returnvalidation($validation);
                return response()->json(['error' => $errors, 'status' => 400], 400);
            }
            //photo saved
            if (isset($data['profile'])) {
                $oldphoto = User::select('profile')->where('id', $data['id'])->first();
                $oldphoto = $oldphoto->getOriginal()['profile'];

                if ($oldphoto){
                    $filePath = public_path($oldphoto);
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                }
            }

            User::find($request->id)->update($data);
            $data = User::find($request->id);
            return response()->json([
                'updatedData' => $data,
                'status' => '201'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e,
                'status' => 500
            ], 500);
        }
    }

    //change only name
    public function updateName(Request $request)
    {
        try {
            $data = [
                'id' => $request->id,
                'name' => $request->name
            ];
            $validation = Validator::make($data, [
                'id' => 'required|integer|exists:users,id',
                'name' => 'required',
            ]);
            if ($validation->fails()) {
                $errors = $this->returnvalidation($validation);
                return response()->json(['error' => $errors, 'status' => 400], 400);
            }
            User::find($request->id)->update([
                "name" => $request->name
            ]);
            $updatedData = User::find($request->id);
            return response()->json([
                'updatedData' => $updatedData,
                "status" => "200"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "error" => $e,
                "status" => 500
            ], 500);
        }
    }

    //change email
    public function updateEmail(Request $request)
    {
        try {
            $data = [
                'id' => $request->id,
                'email' => $request->email
            ];
            $validation = Validator::make($data, [
                'id' => 'required|integer|exists:users,id',
                'email' => 'required|unique:users,email,' . $request->id,
            ]);
            if ($validation->fails()) {
                $errors = $this->returnvalidation($validation);
                return response()->json(['error' => $errors, 'status' => 400], 400);
            }
            User::find($request->id)->update([
                "email" => $request->email
            ]);
            $updatedData = User::find($request->id);
            return response()->json([
                'updatedData' => $updatedData,
                "status" => "200"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "error" => $e,
                "status" => 500
            ], 500);
        }
    }

    /**
     * change password
     * @param id, oldpassword, newpassword
     */

    public function changePassword(Request $request)
    {
        try {
            $data = $this->getdata($request, 'changepassword');
            $validation = Validator::make($data, [
                'id' => 'required|integer|exists:users,id',
                'oldpassword' => 'required|min:8|max:12',
                'newpassword' => 'required|min:8|max:12'
            ]);
            if ($validation->fails()) {
                $errors = $this->returnvalidation($validation);
                return response()->json(['error' => $errors, 'status' => 400], 400);
            }

            $password = User::select('password')->where('id', $request->id)->first();
            $password = $password->getOriginal()['password'];
            $correctpw = Hash::check($request->oldpassword, $password);
            if ($correctpw) {
                User::find($request->id)->update([
                    'password' => Hash::make($request->newpassword)
                ]);
                return response()->json([
                    'success' => 'Password was successfully updated',
                    'status' => '200'
                ], 200);
            } else {
                return response()->json([
                    'fail' => 'Current password is incorrect.',
                    'status' => '400'
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => $e,
                "status" => 500
            ], 500);
        }
    }

    private function returnvalidation($validation)
    {
        return collect($validation->errors()->toArray())
            ->map(function ($error) {
                return $error[0];  // Get the first error message only
            });
    }

    //change the request data to array format for validation
    private function getdata($request, $action = 'default')
    {
        $data = [];
        if ($action == 'changepassword') {  //for only change password
            return [
                'id' => $request->id,
                'oldpassword' => $request->oldpassword,
                'newpassword' => $request->newpassword
            ];
        }

        if ($action == 'create') {
            $data['name'] = $request->name;
            $data['password'] = $request->password;
            $data['email'] = $request->email;
        }
        $data += [
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
        return $data;
    }

    //validation function for user profile
    private function validation($data, $action)
    {
        $rules = [];
        if ($action == 'create') {
            $rules = [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required|min:8|max:12',
            ];
        } else {
            $rules = [
                'id' => 'required|integer|exists:users,id'
            ];
        }
        if (isset($data['phone'])) {
            $rules['phone'] = 'required|numeric|min_digits:8|max_digits:15';
        }

        return Validator::make($data, $rules);
    }
}
