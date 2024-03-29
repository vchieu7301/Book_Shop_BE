<?php

namespace App\Http\Controllers;

use App\Models\customers;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Customers::whereNull('deleted_at')->get();
        if(empty($records)){
            return response()->json([
                'error' => 'true',
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Can find user'
            ]);
        }else{
            return response()->json([
                'error' => 'false',
                'code' => Response::HTTP_OK,
                'result' => $records
            ]);
        }   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->input();
        $validator = Validator::make($request->input(), [
        'name' => 'required',
        'email' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'code'=> Response::HTTP_BAD_REQUEST,
                'mesage' => $validator->errors()
            ]);
        }
        try{
            $user = new customers();
            $user->name = $params['name'];
            $user->email = $params['email'];
            $user->save();
            return response()->json([
                'error' => false,
                'message' => 'Successfull',
            ]);
        }catch(Exception $e){
            Log::info($e);
            return response()->json([
                'error' => true,
                'code'=> Response::HTTP_BAD_REQUEST,
                'message' => 'Add fail',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $records = customers::where('id', $id)->whereNull('deleted_at')->first();
        if(empty($records)){
            return response()->json([
                'error' => true,
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Can find Customer'
            ]);
        }else{
            return response()->json([
                'error' => false,
                'code' => Response::HTTP_OK,
                'result' => $records
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $params = $request->input();
        $id = $params['id'];
        $validator = Validator::make($request->input(), [
         'name' => 'required',
         'email' => 'required'
         ]);
         if ($validator->fails()) {
             return response()->json([
                 'error' => true,
                 'code'=> Response::HTTP_BAD_REQUEST,
                 'mesage' => $validator->errors()
             ]);
         }
         
         try{
             $user = customers::where('id', $id)->whereNull('deleted_at')->first();
             $user->name = $params['name'];
             $user->email = $params['email'];
             $user->save();
             return response()->json([
                 'error' => false,
                 'message' => 'Successfull',
             ]);
         }catch(Exception $e){
             Log::info($e);
             return response()->json([
                 'error' => true,
                 'code'=> Response::HTTP_BAD_REQUEST,
                 'message' => 'Update fail',
             ]);
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $id = $request->input('id');
        $validator = Validator::make($request->input(), [
         'id' => 'required',
         ]);
         if ($validator->fails()) {
             return response()->json([
                 'error' => true,
                 'code'=> Response::HTTP_BAD_REQUEST,
                 'mesage' => $validator->errors()
             ]);
         }
         try{
             $user = customers::where('id', $id)->whereNull('deleted_at')->first();
             $user->deleted_at = Carbon::now();
             $user->save();
             return response()->json([
                 'error' => false,
                 'message' => 'Successfull',
             ]);
         }catch(Exception $e){
             Log::info($e);
             return response()->json([
                 'error' => true,
                 'code'=> Response::HTTP_BAD_REQUEST,
                 'message' => 'Delete fail',
             ]);
         }
    }
}
