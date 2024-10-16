<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
       /**
     * showing data in school table
     * @param id?
     */
    public function get($id = null){
        try{
            $data = $id != null ? Company::find($id) : Company::all();
            if($data != null){
                if (count($data) <= 0){
                    return response()->json([
                        'data' => 'There is no data yet.',
                        'status' => '200'
                    ],200);
                }
                return response()->json([
                    'data' => $data,
                    'status' => '200'
                ],200);
            }else{
                return response()->json([
                    'error' => "the id $id is not found.",
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
    public function store(Request $request){
        try{
            if ($request->name != null){
                $data = Company::create([
                    'name' => $request->name
                ]);
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
    public function delete($id = 0){
        try{
            if ($id == 0) {
                return response()->json([
                    'error' => 'No id is given to delete.',
                    'status' => 400
                ],400);
            }
            $deletedData = Company::find($id);
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
    public function update(Request $request){
        try{
            if($request->id != null && $request->name != null){
                $updatedata = ['name' => $request->name];
                $id  = $request->id;
                    $data = Company::find($id);
                    if ($data != null){
                        Company::find($id)->update($updatedata);
                        $updatedData = Company::find($id);
                    }else{
                        return response()->json([
                            'error' => "the id $id is not found",
                            'status' => '400'
                        ],400);
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
