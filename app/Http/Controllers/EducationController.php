<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\School;
use App\Models\College;
use App\Models\University;
use Illuminate\Http\Request;

class EducationController extends Controller
{
        /**
     * showing data in school table
     * @param
     */
    public function get($education, $id = 0){
        try{
            if ($education == 'schools'){
                $data = $id != 0 ? School::find($id) : School::all();
            }elseif ($education == 'universities'){
                $data = $id != 0 ? University::find($id) : University::all();
            }elseif ($education == 'colleges'){
                $data = $id != 0 ? College::find($id) : College::all();
            }
            if($data != null){
                return response()->json([
                    'data' => $data,
                    'status' => '200'
                ],200);
            }else{
                return response()->json([
                    'error' => "the id $id is not found in $education category",
                    'status' => '400'
                ],200);
            };
        }catch(Exception $e){
            return response()->json([
                'error' => $e,
                'status' => 500
            ],500);
        }

    }

    /**
     * create data in database
     * @param name
     */
    public function store(Request $request, $education){
        try{
            if ($request->name != null){
                if ($education == 'schools'){
                    $data = School::create([
                        'name' => $request->name,
                    ]);
                }elseif ($education == 'universities'){
                    $data = University::create([
                        'name' => $request->name,
                    ]);
                }elseif ($education == 'colleges'){
                    $data = College::create([
                        'name' => $request->name,
                    ]);
                }
                return response()->json([
                    'data' => $data,
                    'status' => '201'
                ],201);
            }else{
                return response()->json([
                    'error' => 'the name cannot be null',
                    'status' => 400
                ],400);
            }

        }catch (Exception $e){
            return response()->json([
                'error' => $e,
                'status' => 500
            ],500);
        };

    }

     /**
     * delete data in database
     * @param id
     */
    public function delete($education, $id = 0){
        try{
            if ($id == 0) {
                return response()->json([
                    'error' => 'No id is given to delete.',
                    'status' => 400
                ]);
            }
            if ($education == 'schools'){
                $deletedData = School::find($id);
            }elseif ($education == 'universities'){
                $deletedData = University::find($id);
            }elseif ($education == 'colleges'){
                $deletedData = College::find($id);
            }
            if($deletedData != null){
                $deletedData->delete();
                return response()->json([
                    'deletedData' => $deletedData,
                    'status' => 200
                ],200);
            }
            return response()->json([
                'error' => "the data of id $id is not found",
                'status' => '400'
            ],400);

        }catch (Exception $e){
            return response()->json([
                'error' => $e,
                'status' => 500
            ],500);
        };

    }

    /**
     * update data in database
     * @param id, name
     */
    public function update(Request $request, $education){
        try{
            if($request->id != null && $request->name != null){
                $data = ['name' => $request->name];
                $id  = $request->id;
                if ($education == 'schools'){
                    $data = School::find($id);
                    if ($data != null){
                        School::find($id)->update($data);
                        $updatedData = School::find($id);
                    }else{
                        return response()->json([
                            'error' => "the id $id is not found",
                            'status' => '400'
                        ],400);
                    }
                }elseif ($education == 'universities'){
                    $data = University::find($id);
                    if ($data != null){
                        University::find($id)->update($data);
                        $updatedData = University::find($id);
                    }else{
                        return response()->json([
                            'error' => "the id $id is not found",
                            'status' => '400'
                        ],400);
                    }
                }elseif ($education == 'colleges'){
                    $data = College::find($id);
                    if ($data != null){
                        College::find($id)->update($data);
                        $updatedData = College::find($id);
                    }else{
                        return response()->json([
                            'error' => "the id $id is not found",
                            'status' => '400'
                        ],400);
                    }
                }
                return response()->json([
                    'updatedData' => $updatedData,
                    'status' => 201
                ],201);
            }
            return response()->json([
                'error' => "the data id and name cannot be null",
                'status' => '400'
            ],400);
        }catch (Exception $e){
            return response()->json([
                'error' => $e,
                'status' => 500
            ],500);
        };

    }
}
