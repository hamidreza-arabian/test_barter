<?php

namespace App\Http\Controllers;

use App\Models\FileComment;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFileCommentRequest;
use App\Http\Requests\UpdateFileCommentRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FileCommentController extends Controller
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
    public function store(StoreFileCommentRequest $request)
    {
        try {
                $employee_id = auth()->user()->id;
                $main_register_estate_id = $request->get('main_register_estate_id');
                $comment = $request->get('comment');
                    $res = FileComment::create([
                        'user_id' => $employee_id,
                        'main_register_estate_id' => $main_register_estate_id,
                        'comment' => $comment,
                    ]);

            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $main_register_estate_id)
    {
        try {
            $fileComment=FileComment::select('*')
                ->where('main_register_estate_id', $main_register_estate_id)
                ->with('user:id,full_name')
                ->orderBy('id', 'desc')
                ->get();
            return $this->successResponse($fileComment, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFileCommentRequest $request, FileComment $fileComment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FileComment $fileComment)
    {
        //
    }
}
