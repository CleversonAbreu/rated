<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Repository\UserRepository;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;
    public function __construct(User $user){
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $user = $this->user;
            $userRepository = new UserRepository($user);
    
            if($request->has('conditions')){
                $userRepository->selectConditions($request->get('conditions'));
            }
            
            if($request->has('fields')){
                $userRepository->selectFilter($request->get('fields'));
            }
            return response()->json($userRepository->getResult()->paginate(10),200);
        } catch (\Exception $e) {
            return response()->json(
                ApiMessages::getErrorMessage(config('constants.users.search_error'),$e->getMessage()),401);
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
        try {
            $data    = $request->all();
            $user = $this->user->create($data);

            return response()->json(
                ApiMessages::getSuccessMessage(config('constants.users.store'),$user),200);
        } catch (\Exception $e) {
            return response()->json(
                ApiMessages::getErrorMessage(config('constants.users.store_error'),$e->getMessage()),401);
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
        try {
            $user = $this->user->find($id);

            if ($user){
                return response()->json(
                    ApiMessages::getSuccessMessage(config('constants.users.found'),$user),200);
            }else{
                return response()->json(ApiMessages::getErrorMessage(config('constants.users.not_found')),404);
            }
            
        } catch (\Exception $e) {
            return response()->json(
                ApiMessages::getErrorMessage(config('constants.users.search_error'),$e->getMessage()),401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,Request $request)
    {
        try {
            $data    = $request->all();
            $user = $this->user->find($id);

            if (!$user){
                return response()->json(
                    ApiMessages::getErrorMessage(config('constants.users.not_found')),404);
            }

            $user->update($data);

            return response()->json(
                ApiMessages::getSuccessMessage(config('constants.users.update'),$user),200);
    
        } catch (\Exception $e) {
            return response()->json(
                ApiMessages::getErrorMessage(config('constants.users.update_error'),$e->getMessage()),401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = $this->user->find($id);

            if (!$user){
                return response()->json(
                    ApiMessages::getErrorMessage(config('constants.users.not_found')),404);
            }

            $user->delete();

            return response()->json(
                ApiMessages::getSuccessMessage(config('constants.users.delete')),200);
        } catch (\Exception $e) {
            return response()->json(
                ApiMessages::getErrorMessage(config('constants.users.delete'),$e->getMessage()),401);
        }
    }
}
