<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponser
{
    protected function successResponse($data, int $code, $message = null): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }
    protected function errorResponse(int $code, $message): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => ''
        ], $code);
    }
    protected function arabicToPersian(string $string): array|string
    {
        $characters = [
            'ك' => 'ک',
            'دِ' => 'د',
            'بِ' => 'ب',
            'زِ' => 'ز',
            'ذِ' => 'ذ',
            'شِ' => 'ش',
            'سِ' => 'س',
            'ى' => 'ی',
            'ي' => 'ی',
            '١' => '1',
            '۱' => '1',
            '٢' => '2',
            '۲' => '2',
            '٣' => '3',
            '۳' => '3',
            '٤' => '4',
            '۴' => '4',
            '٥' => '5',
            '۵' => '5',
            '٦' => '6',
            '۶' => '6',
            '٧' => '7',
            '۷' => '7',
            '٨' => '8',
            '۸' => '8',
            '٩' => '9',
            '۹' => '9',
            '٠' => '0',
            '۰' => '0',
        ];
        return str_replace(array_keys($characters), array_values($characters),$string);
    }
}
