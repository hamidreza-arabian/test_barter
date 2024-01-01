<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $sort = 'desc';
            $users =
                User::query()
                ->with([
                    'role:id,title'
                ])
                ->orderBy('id', $sort)
                ->when(\request('roll_id'),function($query, $estate_type_id){
                    $query->where('role_id', request('roll_id'));
                })
                ->when(\request('full_name'),function($query, $estate_type_id){
                    $query->where('full_name', request('full_name'));
                })
                ->when(\request('gender'),function($query, $estate_type_id){
                    $query->where('gender', request('gender'));
                })
                ->when(\request('status')  > -1 ,function($query, $estate_type_id){
                    $query->where('active', request('status'));
                })
                ->when(\request('phone_number'),function($query, $estate_type_id){
                    $query->where('phone_number', request('phone_number'));
                })
            ;
            $paginate=$users->paginate(45);
            return $this->successResponse($paginate, 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = $this->userInformation($request);
            $res = User::create($user);
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            return $this->successResponse(new UserResource(User::find($id)), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $user = $this->userInformation($request);
            $res = User::find($id)->update($user);
            return $this->successResponse($res, 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $res = User::find($id)->delete();
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
    private function userInformation(Request $request): array
    {
        $role_id = (int)$request->role_id;
        $full_name = trim($request->full_name);
        $gender = $request->gender;
        $phone_number = trim($request->phone_number);
        $active = (int)$request->active;
        return [
            'role_id' => $role_id,
            'full_name' => $this->arabicToPersian($full_name),
            'gender' => $gender,
            'phone_number' => $phone_number,
            'active' => $active,
        ];
    }
    public function userSpecial(): JsonResponse {
        try {
            $user= User::all()->whereBetween('role_id', [1, 3]);
            return $this->successResponse($user, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
    public function adviser(){
        try {
            $employee_role_id= auth()->user()->role_id;
            if ($employee_role_id === 2 || $employee_role_id === 1){
                $detail_user=User::whereIn('role_id', [1, 2, 3])->get();
            }else{
                $detail_user[] = auth()->user();
            }
            return $this->successResponse($detail_user, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
}
