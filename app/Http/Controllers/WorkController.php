<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkController extends Controller
{
    /**
     * showing data in school table
     * @param id?
     */
    public function get($id = null)
    {
        try {
            $data = $id != null ? Work::find($id) : Work::all();
            if ($data != null) {
                if (count($data) <= 0) {
                    return response()->json([
                        'data' => 'There is no data yet.',
                        'status' => '200'
                    ], 200);
                }
                return response()->json([
                    'data' => $data,
                    'status' => '200'
                ], 200);
            } else {
                return response()->json([
                    'error' => "the id $id is not found.",
                    'status' => '400'
                ], 200);
            };
        } catch (Exception $e) {
            return response()->json([
                'error' => $e,
                'status' => 500
            ], 500);
        }
    }

    /**
     * create data in database
     * @param name
     */
    public function store(Request $request)
    {
        try {
            if ($request->name != null) {
                $data = Work::create([
                    'job' => $request->name
                ]);
                return response()->json([
                    'data' => $data,
                    'status' => '201'
                ], 201);
            } else {
                return response()->json([
                    'error' => 'The job field is required',
                    'status' => 400
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => $e,
                'status' => 500
            ], 500);
        };
    }

    /**
     * delete data in database
     * @param id
     */
    public function delete($id = 0)
    {
        try {
            if ($id == 0) {
                return response()->json([
                    'error' => 'No id is given to delete.',
                    'status' => 400
                ], 400);
            }
            $deletedData = Work::find($id);
            if ($deletedData != null) {
                $deletedData->delete();
                return response()->json([
                    'deletedData' => $deletedData,
                    'status' => 200
                ], 200);
            }
            return response()->json([
                'error' => "the data of id $id is not found",
                'status' => '400'
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e,
                'status' => 500
            ], 500);
        };
    }

    /**
     * update data in database
     * @param id, name
     */
    public function update(Request $request)
    {
        try {
            $data = [
                'id' => $request->id,
                'name' => $request->name
            ];
            $validation = Validator::make($data, [
                'id' => 'required|integer|exists:works,id',
                'name' => 'required',
            ]);
            if ($validation->fails()) {
                $errors = collect($validation->errors()->toArray())
                    ->map(function ($error) {
                        return $error[0];  // Get the first error message only
                    });
                return response()->json(['error' => $errors, 'status' => 400], 400);
            }

            $updatedata = ['job' => $request->name];
            $id  = $request->id;
            Work::find($id)->update($updatedata);
            $updatedData = Work::find($id);
            return response()->json([
                'updatedData' => $updatedData,
                'status' => 201
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e,
                'status' => 500
            ], 500);
        };
    }
}
