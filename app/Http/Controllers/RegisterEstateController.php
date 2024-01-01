<?php

namespace App\Http\Controllers;

use App\Http\Resources\MainRegisterEstateResource;
use App\Http\Resources\RegisterEstateResource;
use App\Models\City;
use App\Models\MainRegisterEstate;
use App\Models\Province;
use App\Models\RegisterEstate;
use App\Http\Requests\StoreRegisterEstateRequest;
use App\Http\Requests\UpdateRegisterEstateRequest;
use App\Models\RegisterEstateField;
use App\Models\RegisterEstateItem;
use App\Models\RegisterWantEstateField;
use App\Models\RegisterWantEstateItem;
use App\Models\RegisterWantEstateType;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterEstateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $per_page = 20;
            $page = 0;
            $skip = $page * $per_page;
            $take = $per_page + $skip;
            $sort = 'desc';
            $estates = RegisterEstate::orderBy('id', $sort)->skip($skip)->take($take)->get();
            $estates = MainRegisterEstate::with(['assets'])->orderBy('id', $sort)
//                ->when($request->province)
                ->paginate(15);
            return $this->successResponse([
                'advisers' => User::select('id', 'full_name', 'phone_number')->adviser()->get(),
                'province' => Province::get(),
                'city' => City::esfahanCities()->get(),
                'estates' => MainRegisterEstateResource::collection($estates),
                'all_count' => RegisterEstate::all()->count(),
                'page' => $page,
                'per_page' => $per_page
                ], 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    public function getEstatesWithFilter(Request $request): JsonResponse {
        try {

            $per_page = 20;
            $page = 0;
            if($request->page){
                $page = $request->page;
            }
            if($request->per_page){
                $per_page = $request->per_page;
            }
            $skip = $page * $per_page;
            $take = $per_page + $skip;
            $sort = 'desc';
            if($request->sort === 'asc'){
                $sort = 'asc';
            }
            $where = [];
            $active = [];
            if((int)$request->active === 1){
                $active = ['active', '=', 1];
            }elseif((int)$request->active === 0){
                $active = ['active', '=', 0];
            }
            $min_price = [];
            $max_price = [];
            if($request->price){
                $split_price = explode('-', $request->price);
                if($split_price[0] > $split_price[1]){
                    $max_price = ['price', '<=', $split_price[0]];
                    $min_price = ['price', '<=', $split_price[1]];
                }else{
                    $max_price = ['price', '<=', $split_price[1]];
                    $min_price = ['price', '<=', $split_price[0]];
                }
            }
            array_push($where, $min_price, $max_price, $active);
            $estates = MainRegisterEstate::with(['assets'])->orderBy('id', $sort)
//                ->when($request->province)
                ->paginate(15);

            return $this->successResponse([
                'estates' => RegisterEstateResource::collection($estates),
                'all_count' => RegisterEstate::all()->count(),
                'page' => $page,
                'per_page' => $per_page
            ], 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegisterEstateRequest $request): JsonResponse
    {
        try {
//            $res = DB::transaction(function () use ($request) {
//            return $this->successResponse($request->all(), 201);
                $fields = $request->fields;
                $want = $request->want;
                $res = $this->userEstateInformation($request);
                $register_estate_id = $res['id'];
                foreach ($fields as $field){
                    $field_id = (int)$field['id'];
                    $filed_res = RegisterEstateField::create([
                        'register_estate_id' => $register_estate_id,
                        'estate_field_id' => $field_id,
                        'text' => $field['text'],
                    ]);
                    foreach ($field['items'] as $item){
                        $res = RegisterEstateItem::create([
                            'register_estate_id' => $register_estate_id,
                            'register_estate_field_id' => $filed_res['id'],
                            'estate_field_id' => $field_id,
                            'estate_field_item_id' => (int)$item,
                        ]);
                    }
                }
                foreach ($want as $val){
                    $want_res = RegisterWantEstateType::create([
                        'estate_type_id' => $val['estate_type_id'],
                        'register_estate_id' => $register_estate_id,
                        'barter_type' => $val['barter_type_id'],
                        'barter_price' => $val['barter_price'],
                        'province_id' => $val['province_id'],
                        'city_id' => $val['city_id'],
                        'region_id' => $val['region_id'] ? $val['region_id'] : null,
                        'district_id' => $val['district_id'] ? $val['district_id'] : null,
                    ]);
                    foreach ($val['fields'] as $field){
                        $field_id = (int)$field['id'];
                        $filed_res = RegisterWantEstateField::create([
                            'register_estate_id' => $register_estate_id,
                            'estate_field_id' => $field_id,
                            'register_want_estate_type_id' => $want_res['id'],
                            'from_text' => $field['from_text'],
                            'to_text' => $field['to_text'],
                        ]);
                        foreach ($field['items'] as $item){
                            $res_item = RegisterWantEstateItem::create([
                                'register_estate_id' => $register_estate_id,
                                'register_want_estate_type_id' => $want_res['id'],
                                'register_want_estate_field_id' => $filed_res['id'],
                                'estate_field_id' => $field_id,
                                'estate_field_item_id' => (int)$item,
                            ]);
                        }
                    }
//                    return $this->successResponse($res, 201);
                }
//                return $res;
//            }, 5);
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $register_id): JsonResponse
    {
        try {
            $estate = RegisterEstate::find($register_id);
            $main_price = $estate['price'];
            $percent = $main_price * 10 / 100;
            $min_price = $main_price - $percent;
            $max_price = $main_price + $percent;
            $re_same = DB::select('
                SELECT re1.*, wa1.id AS id_want_first, wa1.province_id AS want_province_id_first, wa1.city_id AS want_city_id_first,
                       wa1.region_id AS want_region_id_first, wa1.barter_type AS barter_type_first, wa1.barter_price AS barter_price_first,
                       wa2.id AS id_want_second, wa2.barter_type AS barter_type_second, wa2.barter_price AS barter_price_second,
                       wa2.province_id AS want_province_id_second, wa2.city_id AS want_city_id_second,
                       wa2.region_id AS want_region_id_second, re1.id AS id
                FROM `register_want_estate_types` AS wa1
                    JOIN `register_estates` AS re1 ON
                        re1.estate_type_id = wa1.estate_type_id AND
                        re1.id <> ? AND
                        re1.deleted_at IS NULL AND
                        re1.province_id = wa1.province_id AND
                        (CASE
                            WHEN wa1.city_id > 0 THEN
                                re1.city_id = wa1.city_id
                            ELSE wa1.city_id IS NULL
                        END) AND
                        (CASE
                            WHEN wa1.region_id > 0 THEN
                                re1.region_id = wa1.region_id
                            ELSE wa1.region_id IS NULL
                        END)
                    JOIN `register_want_estate_types` AS wa2 ON
                        re1.id = wa2.register_estate_id AND
                        wa2.estate_type_id = ? AND
                        wa2.deleted_at IS NULL
                    WHERE wa1.register_estate_id = ?',
                [$register_id, $estate->estate_type_id, $register_id]);
            $re_dont_same = DB::select('
                SELECT re1.*, wa1.*, re1.id AS id
                FROM `register_want_estate_types` AS wa1
                    JOIN `register_estates` AS re1 ON
                        re1.estate_type_id = wa1.estate_type_id AND
                        re1.id <> ? AND
                        re1.deleted_at IS NULL AND
                        re1.province_id = wa1.province_id AND
                        (CASE
                            WHEN wa1.city_id > 0 THEN
                                re1.city_id = wa1.city_id
                            ELSE wa1.region_id IS NULL
                        END) AND
                        (CASE
                            WHEN wa1.region_id > 0 THEN
                                re1.region_id = wa1.region_id
                            ELSE wa1.region_id IS NULL
                        END)
                    WHERE wa1.register_estate_id = ?',
                [$register_id, $register_id]);

            if(count($re_same)){
                $barter_price_first = $re_same[0]->barter_price_first;
                if($re_same[0]->barter_type_first === 1){
                    $main_price = $main_price - $barter_price_first;
                }elseif ($re_same[0]->barter_type_first === 2){
                    $main_price = $main_price + $barter_price_first;
                }
                $min_price = $main_price - ($main_price / 10);
                $max_price = $main_price + ($main_price / 10);
            }elseif(count($re_dont_same)){
                $barter_price_first = $re_dont_same[0]->barter_price;
                if($re_dont_same[0]->barter_type === 1){
                    $main_price = $main_price - $barter_price_first;
                }elseif ($re_dont_same[0]->barter_type === 2){
                    $main_price = $main_price + $barter_price_first;
                }
                $min_price = $main_price - ($main_price / 10);
                $max_price = $main_price + ($main_price / 10);
            }
            $new_same = [];
            foreach ($re_same as $item){
                $barter_price_second = $item->barter_price_second;
                if($item->barter_type_second === 1){
                    $barter_price_second = $item->price - $barter_price_second;
                }elseif ($item->barter_type_second === 2){
                    $barter_price_second = $item->price + $barter_price_second;
                }else{
                    $barter_price_second = $item->price;
                }
                $min_price_second = $barter_price_second - ($barter_price_second / 10);
                $max_price_second = $barter_price_second + ($barter_price_second / 10);
                if($estate['price'] < $min_price_second || $estate['price'] > $max_price_second){
                    continue;
                }
                if($item->price < $min_price || $item->price > $max_price){
                    continue;
                }
                if($estate['province_id'] !== $item->province_id){
                    continue;
                }
                if($item->want_city_id_second && ($item->want_city_id_second !== $estate['city_id'])){
                    continue;
                }
                if($item->want_region_id_second && ($item->want_region_id_second !== $estate['region_id'])){
                    continue;
                }
                array_push($new_same, $item);
            }

            function findInArrayById($array, $id) {
                foreach ($array as $item) {
                    if ($item->id == $id) {
                        return $item;
                    }
                }
                return null;
            }

            $new_dont_same = [];
            foreach ($re_dont_same as $item){
                $result = findInArrayById($re_same, $item->id);
                if($result !== null){
                    continue;
                }
                if($item->price < $min_price || $item->price > $max_price){
                    continue;
                }
                if($estate['province_id'] !== $item->province_id){
                    continue;
                }
                if($estate['city_id'] && $item->city_id && ($item->city_id !== $estate['city_id'])){
                    continue;
                }
                if($estate['region_id'] && $item->region_id && ($item->region_id !== $estate['region_id'])){
                    continue;
                }
                array_push($new_dont_same, $item);
            }
            return $this->successResponse([
                'estate' => new RegisterEstateResource($estate),
                'same_barter' => RegisterEstateResource::collection($new_same),
                'dont_same_barter' => RegisterEstateResource::collection($new_dont_same),
            ], 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegisterEstateRequest $request, int $register_id): JsonResponse
    {
        try {
            $res = '';
            switch ($request->type){
                case 1:
                    $fields = $request->fields;
                    $estate_type_id = $request->estate_type_id;
                    $region = $request->region;
                    $price = $request->price;
                    $res = RegisterEstate::find($register_id)->update([
                        'estate_type_id' => $estate_type_id,
                        'province_id' => $region['province_id'],
                        'city_id' => $region['city_id'],
                        'district_id' => $region['district_id'] > 0 ? $region['district_id'] : null,
                        'address' => $region['address'],
                        'price' => $price,
                    ]);
                    RegisterEstateField::where(['register_estate_id' => $register_id])->delete();
                    RegisterEstateItem::where(['register_estate_id' => $register_id])->delete();
                    foreach ($fields as $field){
                        $field_id = (int)$field['id'];
                        $filed_res = RegisterEstateField::create([
                            'register_estate_id' => $register_id,
                            'estate_field_id' => $field_id,
                            'text' => $field['text'],
                        ]);
                        foreach ($field['items'] as $item){
                            $res = RegisterEstateItem::create([
                                'register_estate_id' => $register_id,
                                'register_estate_field_id' => $filed_res['id'],
                                'estate_field_id' => $field_id,
                                'estate_field_item_id' => (int)$item,
                            ]);
                        }
                    }
                    break;
                case 2:
                    $want = $request->want;
                    RegisterWantEstateType::where(['register_estate_id' => $register_id])->delete();
                    RegisterWantEstateField::where(['register_estate_id' => $register_id])->delete();
                    RegisterWantEstateItem::where(['register_estate_id' => $register_id])->delete();
                    foreach ($want as $val){
                        $want_res = RegisterWantEstateType::create([
                            'estate_type_id' => $val['estate_type_id'],
                            'register_estate_id' => $register_id,
                            'barter_type' => $val['barter_type_id'],
                            'barter_price' => $val['barter_price'],
                            'province_id' => $val['province_id'],
                            'city_id' => $val['city_id'] ? $val['city_id'] : null,
                            'region_id' => $val['region_id'] ? $val['region_id'] : null,
                            'district_id' => $val['district_id'] ? $val['district_id'] : null,
                        ]);
                        foreach ($val['fields'] as $field){
                            $field_id = (int)$field['id'];
                            $filed_res = RegisterWantEstateField::create([
                                'register_estate_id' => $register_id,
                                'estate_field_id' => $field_id,
                                'register_want_estate_type_id' => $want_res['id'],
                                'from_text' => $field['from_text'],
                                'to_text' => $field['to_text'],
                            ]);
                            foreach ($field['items'] as $item){
                                $res = RegisterWantEstateItem::create([
                                    'register_estate_id' => $register_id,
                                    'register_want_estate_type_id' => $want_res['id'],
                                    'register_want_estate_field_id' => $filed_res['id'],
                                    'estate_field_id' => $field_id,
                                    'estate_field_item_id' => (int)$item,
                                ]);
                            }
                        }
                    }
                    break;
                case 3:
                    $customer = $request->customer;
                    $user_customer = User::where('phone_number', $customer['phone_number'])->first();
                    if($user_customer){
                        $customer_id = $user_customer['id'];
                    }else{
                        $role_id = (int)$customer['role_id'];
                        $full_name = trim($customer['full_name']);
                        $gender = $customer['gender'];
                        $phone_number = trim($customer['phone_number']);
                        $active = (int)$customer['active'];
                        $res = User::create([
                            'role_id' => $role_id,
                            'full_name' => $this->arabicToPersian($full_name),
                            'gender' => $gender,
                            'phone_number' => $phone_number,
                            'active' => $active,
                        ]);
                        $customer_id = $res['id'];
                        $res = RegisterEstate::find($register_id)->update([
                            'customer_id' => $customer_id,
                        ]);
                    }
                    break;
                default: $res = '';
            }
            return $this->successResponse($res, 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegisterEstate $registerEstate)
    {
        //
    }
    private function userEstateInformation(StoreRegisterEstateRequest $request): RegisterEstate
    {
        $res = [];
        $customer = $request->customer;
        $employee_id = auth()->user()->id;
        $user_customer = User::where('phone_number', $customer['phone_number'])->first();
        if($user_customer){
            $customer_id = $user_customer['id'];
        }else{
            $role_id = (int)$customer['role_id'];
            $full_name = trim($customer['full_name']);
            $gender = $customer['gender'];
            $phone_number = trim($customer['phone_number']);
            $active = (int)$customer['active'];
            $res = User::create([
                'role_id' => $role_id,
                'full_name' => $this->arabicToPersian($full_name),
                'gender' => $gender,
                'phone_number' => $phone_number,
                'active' => $active,
            ]);
            $customer_id = $res['id'];
        }

        $estate_type_id = $request->estate_type_id;
        $region = $request->region;
        $price = $request->price;

        return RegisterEstate::create([
            'customer_id' => $customer_id,
            'employee_id' => $employee_id,
            'estate_type_id' => $estate_type_id,
            'province_id' => $region['province_id'],
            'city_id' => $region['city_id'],
            'district_id' => $region['district_id'] > 0 ? $region['district_id'] : null,
            'region_id' => $region['region_id'] > 0 ? $region['region_id'] : null,
            'address' => $region['address'],
            'price' => $price,
            'active' => 1,
        ]);
    }

}
