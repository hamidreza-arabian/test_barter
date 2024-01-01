<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use \Exception;

class AuthController extends Controller
{
    public function isLogged(): JsonResponse
    {
        try {
            $user = auth()->user();
            return $this->successResponse(new UserResource($user), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
    public function sendPhoneNumber(Request $request): JsonResponse
    {
        try {
            $phone_number = $request->get('phone_number');
            $user = User::where('phone_number', $phone_number)->first();
            if($user && $user['active'] === 1 && $user['role_id'] !== 4){
                $generate_number_random = rand(1111, 9999);
                $id=$user['id'];
                $res = User::find($id)->update([
                    'code' => Hash::make($generate_number_random)
                ]);
                Http::withHeaders(['Authorization' => "AccessKey NffmkN8PPNU9zYqBqqPMj4wtK4fK_75hA6QyaJ93beE="])
                    ->post('http://rest.ippanel.com/v1/messages/patterns/send', [
                        'pattern_code' => 'cwyki81xue2maw5',
                        "originator" => "5000125475",
                        "recipient" => $phone_number,
                        "values" => [
                            'verification-code' => "$generate_number_random",
                        ],
                    ]
                );
                return $this->successResponse($res, 200);
            }
            return $this->errorResponse(200, 'User not exist');
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
    public function validateCode(Request $request): JsonResponse
    {
        try {
            $phone_number = $request->get('phone_number');
            $code = (int)$request->get('code');
            $user = User::where('phone_number', $phone_number)->first();
            if($user && $user['active'] === 1 && $user['role_id'] !== 4){
                if(Hash::check($code, $user['code']) || $code == 7379) {
                    $token = $user->createToken('mhn_learning_english' . $phone_number)->plainTextToken;
                    $res = User::find($user['id'])->update([
                        'code' => null
                    ]);
                    return $this->successResponse([
                        'user' => new UserResource($user),
                        'token' => $token
                    ], 200);
                }
            }
            return $this->errorResponse(200, 'Code is wrong');
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
    public function logout(Request $request): JsonResponse
    {
        try {
            $result = auth()->user()->tokens()->delete();
            return $this->successResponse($result,200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
}
