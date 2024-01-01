<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Exception;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $roles = Role::all();
            return $this->successResponse(RoleResource::collection($roles), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        try {
            $title = trim($request->title);
            $res = Role::create([
                'title' => $this->arabicToPersian($title),
            ]);
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): JsonResponse
    {
        try {
            return $this->successResponse(new RoleResource($role), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        try {
            $title = trim($request->title);
            $res = $role->update([
                'title' => $this->arabicToPersian($title),
            ]);
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): JsonResponse
    {
        try {
            $res = $role->delete();
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

}
