<?php

namespace App\Http\Controllers;

use App\Models\EstateComment;
use App\Http\Requests\StoreEstateCommentRequest;
use App\Http\Requests\UpdateEstateCommentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\DB;

class EstateCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEstateCommentRequest $request)
    {
        try {
            $base_estate_id = $request['base_estate_id'];
            $barter_estate_id = $request['barter_estate_id'];
            $user = auth()->user();
            $barter_type = $request['barter_type'];
            $comment = $request['comment'];
            if ($request->get('barter_type')){
                DB::beginTransaction();
                $res = EstateComment::create([
                    'base_estate_id' => (int)$base_estate_id,
                    'barter_estate_id' => (int)$barter_estate_id,
                    'user_id' => (int)$user['id'],
                    'barter_type' => (int)$barter_type,
                    'comment' => $comment,
                ]);
                $res_2 = EstateComment::create([
                    'base_estate_id' => (int)$barter_estate_id,
                    'barter_estate_id' => (int)$base_estate_id,
                    'user_id' => (int)$user['id'],
                    'barter_type' => (int)$barter_type,
                    'comment' => $comment,
                ]);
                DB::commit();
                return $this->successResponse($res_2, 201);
            }
            $res = EstateComment::create([
                'base_estate_id' => (int)$base_estate_id,
                'barter_estate_id' => (int)$barter_estate_id,
                'user_id' => (int)$user['id'],
                'barter_type' => (int)$barter_type,
                'comment' => $comment,
            ]);
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception);
        }
    }

    /**
     * Display the specified resource.
     */
    public function estateComment(Request $request)
    {
        try {
            $base_estate_id = $request['base_estate_id'];
            $barter_estate_id = $request['barter_estate_id'];
            $comment=EstateComment::select('id','comment','user_id','created_at')
                ->where('base_estate_id', $base_estate_id)
                ->where('barter_estate_id', $barter_estate_id)
                ->with('user:id,full_name')
                ->orderBy('id', 'desc')
                ->get();
            return $this->successResponse($comment, 200);
        }
        catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEstateCommentRequest $request, EstateComment $estateComment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EstateComment $estateComment)
    {
        //
    }
}
