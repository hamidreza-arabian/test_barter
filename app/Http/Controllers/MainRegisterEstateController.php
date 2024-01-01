<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegisterEstateRequest;
use App\Http\Resources\DetailsResource;
use App\Http\Resources\MainRegisterEstateResource;
use App\Http\Resources\RegisterEstateResource;
use App\Models\EstateComment;
use App\Models\EstateField;
use App\Models\MainRegisterEstate;
use App\Http\Requests\StoreMainRegisterEstateRequest;
use App\Http\Requests\UpdateMainRegisterEstateRequest;
use App\Models\MainRegisterWant;
use App\Models\RegisterEstate;
use App\Models\RegisterEstateField;
use App\Models\RegisterEstateItem;
use App\Models\RegisterWantEstateField;
use App\Models\RegisterWantEstateItem;
use App\Models\RegisterWantEstateType;
use App\Models\User;
use App\Models\WantEstateField;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\throwException;
use function Spatie\FlareClient\exceptionClass;

class MainRegisterEstateController extends Controller
{
    public function index(Request $request)
    {
        try {
            $employee_role_id= auth()->user()->role_id;
            if ($employee_role_id === 2 || $employee_role_id === 1){
                $detail_user=User::select('id', 'full_name', 'phone_number')->adviser()->get();
            }else{
                $detail_user[] = auth()->user();
            }
            $sort = 'desc';
            $estates = MainRegisterEstate::query()
                ->with([
                    'assets' => [
                        'estateType:id,title',
                        'province:id,title',
                        'city:id,title',
                        'region:id,title',
                        'registerEstateFields'=> ['estateFields'/*,'registerFieldItems' => ['estateFieldItems']*/]
                    ] ,
                    'mainRegisterWant' => [
                        'wantEstate' => [
                            'estateType:id,title',
                            'province:id,title',
                            'city:id,title',
                            'region:id,title',
                            'wantFields' => ['estateFields'/*,'wantItems' => ['estateFieldItems']*/]
                        ],
                    ],

                    'employee:id,full_name,phone_number',
                    'customer:id,full_name,phone_number'])
                ->orderBy('id', $sort)
                ->when(\request('estate_type_id') and count(json_decode(\request('estate_type_id'))) > 0,function($query, $estate_type_id){
                    $query->whereHas('assets', function ($query) use($estate_type_id){
                        $query->whereIn('estate_type_id', json_decode(\request('estate_type_id')));
                    });
                })
                ->when(\request('province_id'),function($query, $province){
                    $query->whereHas('assets', function ($query) use($province){
                        $query->where('province_id', $province);
                    });
                })
                ->when(\request('city_id'),function($query, $city){
                    $query->whereHas('assets', function ($query) use($city){
                        $query->where('city_id', $city);
                    });
                })
                ->when(\request('region_id') and count(json_decode(\request('region_id'))) > 0,function($query, $region_id){
                    $query->whereHas('assets', function ($query) use($region_id){
                        $query->whereIn('region_id', json_decode(\request('region_id')));
                    });
                })
                ->when(\request('customer_tell'),function($query, $customer_tell){
                    $query->whereHas('customer', function ($query) use($customer_tell){
                        $query->where('phone_number', 'like', "%{$customer_tell}%");
                    });
                })
                ->when(\request('customer_full_name'),function($query, $customer_full_name){
                    $query->whereHas('customer', function ($query) use($customer_full_name){
                        $query->where('full_name', 'like', "%{$customer_full_name}%");
                    });
                })
                ->when(\request('code'),function($query, $code){
                    $query->whereHas('assets.registerEstateFields.estateFields', function ($query) use($code){
                        $query->where('text', $code)->where('estate_field_id', 1);
                    });
                })
                ->when(\request('price_az'),function($query, $price){
                    $query->whereHas('assets', function ($query) use($price){
                        $query->where('price', '>=',  $price);
                    });
                })
                ->when(\request('price_ta'),function($query, $price_ta){
                    $query->whereHas('assets', function ($query) use($price_ta){
                        $query->where('price', '<=',  $price_ta);
                    });
                })
                ->when(\request('timestamp_az'),function($query, $timestamp_az){
                    $query->whereHas('assets', function ($query) use($timestamp_az){
                        $query->whereDate('created_at', '>=', $timestamp_az);
                    });
                })
                ->when(\request('timestamp_ta'),function($query, $timestamp_ta){
                    $query->whereHas('assets', function ($query) use($timestamp_ta){
                        $query->whereDate('created_at', '<=', $timestamp_ta);
                    });
                })
                ->when(\request('status') > -1 ,function($query, $status){
                        $query->whereHas('assets', function ($query) use($status){
                            $query->where('status', request('status'));
                        });
                })
                ->when(\request('adviser'),function($query, $adviser){
                        $query->where('employee_id', (int)$adviser);
                })->withCount(['FileComments']);
            $paginate=$estates->paginate(45);
            return $this->successResponse([
                'advisers' => $detail_user,
                'estates' => $paginate,
            ], 200);
        }catch (Exception $exception){
            return $exception;
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
    public function store(StoreMainRegisterEstateRequest $request)
    {
        try {
            //inputs
                //assets
                //main => wants
                //costumer

            //insert customer data
            //insert assets data
            //insert wants data


            DB::beginTransaction();
            $want = $request->main_want;
            $res = $this->insertAssetsInformation($request);
            $register_estate_main_id = $res['id'];
            $employee_id= auth()->user()->id;
            foreach ($want as $val){
                $main_want = MainRegisterWant::create([
                    'employee_id' => $employee_id,
                    'main_register_estate_id' => $register_estate_main_id,
                    'barter_type' => $val['barter_type_id'],
                    'barter_price' => $val['barter_price'],

                ]);
                foreach ($val['wants'] as $asset) {
                    $want_res = RegisterWantEstateType::create([
                        'employee_id' => $employee_id,
                        'estate_type_id' => $asset['estate_type_id'],
                        'main_register_estate_id' => $register_estate_main_id,
                        'main_register_want_id' => $main_want->id,
                        'barter_type' => $val['barter_type_id'],
                        'barter_price' => $val['barter_price'],
                        'province_id' => $asset['province_id'],
                        'city_id' => $asset['city_id'],
                        'region_id' => $asset['region_id'] > 0 ? $asset['region_id'] : null,
                        'district_id' => null,
                    ]);
                    foreach ($asset['fields'] as $field) {
                        if (!isset($field) || !$field) continue;
                        if (!isset($field['from_text']) || !isset($field['to_text'])) continue;
                        $field_id = (int)$field['id'];
                        $filed_res = RegisterWantEstateField::create([
                            'main_register_want_id' => $main_want->id,
                            'main_register_estate_id' => $register_estate_main_id,
                            'estate_field_id' => $field_id,
                            'register_want_estate_type_id' => $want_res['id'],
                            'from_text' => $field['from_text'],
                            'to_text' => $field['to_text'],
                        ]);
                        foreach ($field['items'] as $item) {
                            $res_item = RegisterWantEstateItem::create([
                                'main_register_want_id' => $main_want->id,
                                'main_register_estate_id' => $register_estate_main_id,
                                'register_want_estate_type_id' => $want_res['id'],
                                'register_want_estate_field_id' => $filed_res['id'],
                                'estate_field_id' => $field_id,
                                'estate_field_item_id' => (int)$item,
                            ]);
                        }
                    }
                }
            }
            DB::commit();
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            DB::rollback();
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
    public function show(Request $request, int $register_id)
    {
        try {
            /*
             * main --> want
             * loop want --> Consonant & nonConsonant
             *
             * */
            $main_estate = MainRegisterEstate::query()
                ->with([
                    'assets' => [
                        'estateType:id,title',
                        'province:id,title',
                        'city:id,title',
                        'region:id,title',
                        'employee:id,full_name,phone_number',
                        'registerEstateFields'=> ['estateFields','registerFieldItems' => ['estateFieldItems']]
                    ] ,
                    'mainRegisterWant' =>[
                        'wantEstate' => [
                            'estateType:id,title',
                            'province:id,title',
                            'city:id,title',
                            'region:id,title',
                            'employee:id,full_name,phone_number',
                            'wantFields' => ['estateFields','wantItems' => ['estateFieldItems']]
                        ],
                    ],
                    'employee:id,full_name,phone_number',
                    'customer:id,full_name,phone_number'])
                ->withCount(['FileComments'])
                ->find($register_id);

            $assets_price =  $main_estate->assets->sum('price');
            $assets_id = $main_estate->assets->pluck('id');
            $estate = RegisterEstate::where('main_register_estate_id', $main_estate->id)->first();

            foreach ($main_estate->mainRegisterWant as $main_want){
                $main_price = match ($main_want['barter_type']) {
                    1 => $assets_price - $main_want['barter_price'],
                    2 => $assets_price + $main_want['barter_price'],
                    3 => $assets_price,
                };

                $percent = $main_price * 10 / 100;
                $min_price = $main_price - $percent;
                $max_price = $main_price + $percent;
                $main_query = MainRegisterEstate::select(DB::raw('SUM(price) as total_price , register_estates.main_register_estate_id'), 'main_register_estates.*')
                    ->with([
                        'assets' => [
                            'estateType:id,title',
                            'province:id,title',
                            'city:id,title',
                            'region:id,title',
                            'registerEstateFields'=> ['estateFields'/*,'registerFieldItems' => ['estateFieldItems']*/]
                        ] ,
                        'mainRegisterWant' => [
                            'wantEstate' => [
                                'estateType:id,title',
                                'province:id,title',
                                'city:id,title',
                                'region:id,title',
                                'wantFields' => ['estateFields'/*,'wantItems' => ['estateFieldItems']*/]
                            ],
                        ],
                        'employee:id,full_name,phone_number',
                        'customer:id,full_name,phone_number'])
                    ->join('register_estates', 'main_register_estates.id', '=', 'register_estates.main_register_estate_id')
                    ->groupBy('register_estates.main_register_estate_id')
                    ->havingBetween('total_price', [$min_price,$max_price])
                ;
                foreach ($main_want['wantEstate'] as $j=>$want){
                     $main_query
                        ->whereHas('assets', function($query) use($want, $assets_id, $min_price, $max_price){
                            $query->where('province_id', $want['province_id'])
                                ->where('city_id', $want['city_id'])
                                ->where('estate_type_id', $want['estate_type_id'])
                                ->whereNotIn('id', $assets_id) // go out from loop
                                ->when($want['region_id'], function ($query, $region_id) {
                                    $query->where('region_id', $region_id);
                                });
                        })
                        ->withCount(['BarterEstateComments' => function($query) use($register_id){
                            $query->where('base_estate_id', $register_id);

                    }]);




                }

                $estates_match_with_want = $main_query->get();

                foreach ($estates_match_with_want as $z=>$estate){
                    $users = EstateComment::query()
                        ->select('users.id', 'users.full_name', 'users.phone_number')
                        ->join('users','estate_comments.user_id', '=', 'users.id')
                        ->where(['base_estate_id'=>$register_id, 'barter_estate_id'=>$estate->id])
                        ->groupBy('users.id', 'users.full_name', 'users.phone_number')
                        ->get();
                    $estate['users'] = $users;



                    $assets_price =  $estate->assets->sum('price');

                    foreach ($estate->mainRegisterWant as $main_want)
                    $main_price = match ($main_want['barter_type']) {
                        1 => $assets_price - $main_want['barter_price'],
                        2 => $assets_price + $main_want['barter_price'],
                        3 => $assets_price,

                    };
                    $percent = $main_price * 10 / 100;
                    $min_price = $main_price - $percent;
                    $max_price = $main_price + $percent;

                    $main_query =MainRegisterEstate::select(DB::raw('SUM(price) as total_price'), 'main_register_estates.*')
                        ->with(['assets', 'mainRegisterWant' => ['wantEstate']])
                        ->where('main_register_estates.id', $register_id)
                        ->join('register_estates', 'main_register_estates.id', '=', 'register_estates.main_register_estate_id')
                        ->groupBy('register_estates.main_register_estate_id')
                        ->havingBetween('total_price', [$min_price,$max_price]);

                    foreach ($main_want['wantEstate'] as $j=>$want){
                        $main_query->whereHas('assets', function ($query) use ($want, $min_price, $max_price) {
                            $query->where('province_id', $want['province_id'])
                                ->where('city_id', $want['city_id'])
                                ->where('estate_type_id', $want['estate_type_id'])
                                ->when($want['region_id'], function ($query, $region_id) {
                                    $query->where('region_id', $region_id);
                                });
                        });
                    }
                    $main_query->count() ? $constant_estates[] = $estate : $non_constant_estates[] = $estate;
                    logger($main_query->toRawSql());
                }
            }
//
            return $this->successResponse([
                'estate' => $main_estate,
                'constant_estates' => $constant_estates ?? [],
                'non_constant_estates' => $non_constant_estates ?? [],
            ], 200);
        }catch (Exception $exception){
            return $exception;
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
    public function update(UpdateMainRegisterEstateRequest $request, int $register_id)
    {
        try {
            DB::beginTransaction();
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
                        'region_id' => $region['region_id'] > 0 ? $region['region_id'] : null,
                        'district_id' => $region['district_id'] > 0 ? $region['district_id'] : null,
                        'address' => $region['address'],
                        'price' => $price,
                        'status' => $request->status
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
                    $want = $request->main_want;
                    $id = $want['id'];
                    MainRegisterWant::destroy($id);
                    RegisterWantEstateType::where(['main_register_want_id' => $id])->delete();
                    RegisterWantEstateField::where(['main_register_want_id' => $id])->delete();
                    RegisterWantEstateItem::where(['main_register_want_id' => $id])->delete();

                    $id_main = MainRegisterWant::create([
                        'main_register_estate_id' => $register_id,
                        'barter_type' => $want['barter_type_id'],
                        'barter_price' => $want['barter_price'],
                        'employee_id' => auth()->id()
                    ])->id;
                    foreach ($want['wants'] as $asset) {
                        $want_res = RegisterWantEstateType::create([
                            'estate_type_id' => $asset['estate_type_id'],
                            'main_register_estate_id' => $register_id,
                            'main_register_want_id' => $id_main,
                            'barter_type' => $want['barter_type_id'],
                            'barter_price' => $want['barter_price'],
                            'province_id' => $asset['province_id'] ?? 4,
                            'city_id' => $asset['city_id'] ? $asset['city_id'] : null,
                            'region_id' => $asset['region_id'] ?? null,
                            'district_id' => $asset['district_id'] ??  null,
                        ]);
                        foreach ($asset['fields'] as $field) {
                            if (!isset($field['from_text']) || !isset($field['to_text'])) continue;
                            $field_id = (int)$field['id'];
                            $filed_res = RegisterWantEstateField::create([
                                'main_register_estate_id' => $register_id,
                                'main_register_want_id' => $id_main,
                                'estate_field_id' => $field_id,
                                'register_want_estate_type_id' => $want_res['id'],
                                'from_text' => $field['from_text'],
                                'to_text' => $field['to_text'],
                            ]);
                            foreach ($field['items'] as $item) {
                                $res = RegisterWantEstateItem::create([
                                    'main_register_estate_id' => $register_id,
                                    'main_register_want_id' => $id_main,
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
                    $user_customer = User::where('id', $customer['id'])
                            ->update([
                                'full_name'=>$customer['full_name'],
                                'phone_number' => $customer['phone_number']
                            ]);
                    logger("controller updated");

                    break;
                default: $res = '';
            }
            DB::commit();
            return $this->successResponse($res, 200);
        }catch (Exception $exception){
            DB::rollBack();
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $res = MainRegisterEstate::destroy($id);
            return $this->successResponse($res, 200);
        }
        catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
    private function insertAssetsInformation(StoreMainRegisterEstateRequest $request)
    {
        $employee_id = auth()->user()->id;
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
            $customer_id = User::create([
                'role_id' => $role_id,
                'full_name' => $this->arabicToPersian($full_name),
                'gender' => $gender,
                'phone_number' => $phone_number,
                'active' => $active,
            ])->id;
        }

        $res_main = MainRegisterEstate::create([
            'customer_id' => $customer_id,
            'employee_id' => $employee_id,
            'type' => 0,
        ]);
        $register_fields_model = new RegisterEstateField();
        foreach ($request->assets as $asset){
            $region = $asset['region'];
            $register_estate_id = RegisterEstate::create([
                'main_register_estate_id' => $res_main['id'],
                'customer_id' => $customer_id,
                'employee_id' => $employee_id,
                'estate_type_id' => $asset['estate_type_id'],
                'province_id' => $region['province_id'],
                'city_id' => $region['city_id'],
                'district_id' => $region['district_id'] > 0 ? $region['district_id'] : null,
                'region_id' => $region['region_id'] > 0 ? $region['region_id'] : null,
                'address' => $region['address'],
                'price' => $asset['price'],
                'status' => 1
            ])->id;
            foreach ($asset['fields'] as $field){
                if (!isset($field) || !$field) continue;
                if (is_null($field)) abort(400, "field is null");
                if (!($field['text'] || $field['text'] == 0 || count($field['items']))){
                    abort(400, "{$field['id']} is empty");
                }
                if ($field['id'] == EstateField::CODE){
                    if (!$register_fields_model->isCodeUnique($field['text'])){
                        abort(400, "code {$field['text']} already exist");
                    }
                }
                $field_id = (int)$field['id'];
                $filed_res = RegisterEstateField::create([
                    'register_estate_id' => $register_estate_id,
                    'estate_field_id' => $field_id,
                    'text' => $field['text'],
                ]);
                foreach ($field['items'] as $item){
                    RegisterEstateItem::create([
                        'register_estate_id' => $register_estate_id,
                        'register_estate_field_id' => $filed_res['id'],
                        'estate_field_id' => $field_id,
                        'estate_field_item_id' => (int)$item,
                    ]);
                }
            }
        }
        return $res_main;
    }
    public function estateByPhone(Request $request){
            try {
                $phone_number=$request->get('phone_number');
                $result = DB::table('users')
                    ->select(
                        'users.full_name AS customer_name', 'users.phone_number AS customer_phone_number',
                        'users2.full_name AS employee_name', 'users2.phone_number AS employee_phone_number',
                        'estate_types.title AS estate_title', 'provinces.title AS title_provinces',
                        'cities.title AS title_cities',
                        'regions.title AS title_regions', 'register_estates.address', 'register_estates.price',
                        'register_estates.main_register_estate_id')
                    ->join('register_estates', 'register_estates.customer_id', '=', 'users.id')
                    ->join('users AS users2', 'users2.id', '=', 'register_estates.employee_id')
                    ->join('estate_types', 'register_estates.estate_type_id', '=', 'estate_types.id')
                    ->join('provinces', 'register_estates.province_id', '=', 'provinces.id')
                    ->join('cities', 'register_estates.city_id', '=', 'cities.id')
                    ->join('regions', 'register_estates.region_id', '=', 'regions.id')
                    ->join('main_register_estates', 'register_estates.main_register_estate_id', '=', 'main_register_estates.id')
                    ->where('users.phone_number', $phone_number)
                    ->whereNull('main_register_estates.deleted_at')
                    ->get();
                return $this->successResponse(DetailsResource::collection($result), 200);
            }
            catch (Exception $exception){
                return $this->errorResponse(500, $exception->getMessage());
            }
    }
    public function addWant(Request $request){
        try {
            DB::beginTransaction();
            $employee_id= auth()->user()->id;

            switch ($request->type){
                case 1:
                    foreach ($request->main_want as $main)
                    $main_want = MainRegisterWant::create([
                        'employee_id' => $employee_id,
                        'main_register_estate_id' => $request->id,
                        'barter_type' => $main['barter_type_id'],
                        'barter_price' => $main['barter_price'],

                    ]);
                    foreach ($main['wants'] as $asset) {
                        $want_res = RegisterWantEstateType::create([
                            'employee_id' => $employee_id,
                            'estate_type_id' => $asset['estate_type_id'],
                            'main_register_estate_id' => $request->id,
                            'main_register_want_id' => $main_want->id,
                            'barter_type' => $main['barter_type_id'],
                            'barter_price' => $main['barter_price'],
                            'province_id' => $asset['province_id'],
                            'city_id' => $asset['city_id'],
                            'region_id' => $asset['region_id'] > 0 ? $asset['region_id'] : null,
                            'district_id' => null,
                        ]);
                        foreach ($asset['fields'] as $field) {
                            if (!isset($field) || !$field) continue;
                            if (!isset($field['from_text']) || !isset($field['to_text'])) continue;
                            $field_id = (int)$field['id'];
                            $filed_res = RegisterWantEstateField::create([
                                'main_register_want_id' => $main_want->id,
                                'main_register_estate_id' => $request->id,
                                'estate_field_id' => $field_id,
                                'register_want_estate_type_id' => $want_res['id'],
                                'from_text' => $field['from_text'],
                                'to_text' => $field['to_text'],
                            ]);
                            foreach ($field['items'] as $item) {
                                $res_item = RegisterWantEstateItem::create([
                                    'main_register_want_id' => $main_want->id,
                                    'main_register_estate_id' => $request->id,
                                    'register_want_estate_type_id' => $want_res['id'],
                                    'register_want_estate_field_id' => $filed_res['id'],
                                    'estate_field_id' => $field_id,
                                    'estate_field_item_id' => (int)$item,
                                ]);
                            }
                        }
                    }

                    break;
                case 2:
                    foreach ($request->want as $want)
                    $want_res = RegisterWantEstateType::create([
                        'employee_id' => $employee_id,
                        'estate_type_id' => $want['estate_type_id'],
                        'main_register_estate_id' => $request->id,
                        'main_register_want_id' => $request->id_main_want,
                        'barter_type' => $want['barter_type_id'],
                        'barter_price' => $want['barter_price'],
                        'province_id' => $want['province_id']?? 4,
                        'city_id' => $want['city_id'],
                        'region_id' => $want['region_id'] > 0 ? $want['region_id'] : null,
                        'district_id' => null,
                    ]);
                    foreach ($want['fields'] as $field) {
                        if (!isset($field) || !$field) continue;
                        if (!isset($field['from_text']) || !isset($field['to_text'])) continue;
                        $field_id = (int)$field['id'];
                        $filed_res = RegisterWantEstateField::create([
                            'main_register_want_id' => $request->id_main_want,
                            'main_register_estate_id' => $request->id,
                            'estate_field_id' => $field_id,
                            'register_want_estate_type_id' => $want_res['id'],
                            'from_text' => $field['from_text'],
                            'to_text' => $field['to_text'],
                        ]);
                        foreach ($field['items'] as $item) {
                            $res_item = RegisterWantEstateItem::create([
                                'main_register_want_id' => $request->id_main_want,
                                'main_register_estate_id' => $request->id,
                                'register_want_estate_type_id' => $want_res['id'],
                                'register_want_estate_field_id' => $filed_res['id'],
                                'estate_field_id' => $field_id,
                                'estate_field_item_id' => (int)$item,
                            ]);
                        }
                    }
                    break;
            }
//            $want = $request->main_want;
//            $id_main=$request->id;
//            $employee_id= auth()->user()->id;
//            foreach ($want as $val){
//                $want_res = RegisterWantEstateType::create([
//                    'employee_id' => $employee_id,
//                    'estate_type_id' => $val['estate_type_id'],
//                    'main_register_estate_id' => $id_main,
//                    'barter_type' => $val['barter_type_id'],
//                    'barter_price' => $val['barter_price'],
//                    'province_id' => $val['province_id'],
//                    'city_id' => $val['city_id'],
//                    'region_id' => $val['region_id'] > 0 ? $val['region_id'] : null,
//                    'district_id' => null,
//                ]);
//                foreach ($val['fields'] as $field){
//                    if (!isset($field) || !$field) continue;
//                    if (!isset($field['from_text']) || !isset($field['to_text'])) continue;
//                    $field_id = (int)$field['id'];
//                    $filed_res = RegisterWantEstateField::create([
//                        'main_register_estate_id' => $id_main,
//                        'estate_field_id' => $field_id,
//                        'register_want_estate_type_id' => $want_res['id'],
//                        'from_text' => $field['from_text'],
//                        'to_text' => $field['to_text'],
//                    ]);
//                    foreach ($field['items'] as $item){
//                        $res_item = RegisterWantEstateItem::create([
//                            'main_register_estate_id' => $id_main,
//                            'register_want_estate_type_id' => $want_res['id'],
//                            'register_want_estate_field_id' => $filed_res['id'],
//                            'estate_field_id' => $field_id,
//                            'estate_field_item_id' => (int)$item,
//                        ]);
//                    }
//                }
//            }
            DB::commit();
            return $this->successResponse($want_res, 201);
        }catch (Exception $exception){
            DB::rollback();
            return $exception;
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
    public function addAssets(Request $request)
    {
        try {
            DB::beginTransaction();
            $id_main = $request->id;
            $customer_id = MainRegisterEstate::find($request->id)->customer_id;
            $employee_id = auth()->user()->id;
            $register_fields_model = new RegisterEstateField();
            foreach ($request->assets as $asset) {
                $region = $asset['region'];
                $register_estate_id = RegisterEstate::create([
                    'main_register_estate_id' => $id_main,
                    'customer_id' => $customer_id,
                    'employee_id' => $employee_id,
                    'estate_type_id' => $asset['estate_type_id'],
                    'province_id' => $region['province_id'],
                    'city_id' => $region['city_id'],
                    'district_id' => $region['district_id'] > 0 ? $region['district_id'] : null,
                    'region_id' => $region['region_id'] > 0 ? $region['region_id'] : null,
                    'address' => $region['address'],
                    'price' => $asset['price'],
                    'status' => 1
                ])->id;
                foreach ($asset['fields'] as $field) {
                    if (!isset($field) || !$field) continue;
                    if (is_null($field)) abort(400, "field is null");
                    if (!($field['text'] || $field['text'] == 0 || count($field['items']))) {
                        abort(400, "{$field['id']} is empty");
                    }
                    if ($field['id'] == EstateField::CODE) {
                        if (!$register_fields_model->isCodeUnique($field['text'])) {
                            abort(400, "code {$field['text']} already exist");
                        }
                    }
                    $field_id = (int)$field['id'];
                    $filed_res = RegisterEstateField::create([
                        'register_estate_id' => $register_estate_id,
                        'estate_field_id' => $field_id,
                        'text' => $field['text'],
                    ]);
                    foreach ($field['items'] as $item) {
                        RegisterEstateItem::create([
                            'register_estate_id' => $register_estate_id,
                            'register_estate_field_id' => $filed_res['id'],
                            'estate_field_id' => $field_id,
                            'estate_field_item_id' => (int)$item,
                        ]);
                    }
                }
            }
            DB::commit();
            return $this->successResponse($filed_res, 201);
        } catch (Exception $exception) {
            DB::rollback();
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
    public function wantSearch(Request $request)
    {
        try {
            $want = json_decode($request->want);
            $query = MainRegisterEstate::query()
                ->with([
                    'assets' => [
                        'estateType:id,title',
                        'province:id,title',
                        'city:id,title',
                        'region:id,title',
                        'registerEstateFields'=> ['estateFields'/*,'registerFieldItems' => ['estateFieldItems']*/]
                    ] ,
                    'mainRegisterWant' => [
                        'wantEstate' => [
                            'estateType:id,title',
                            'province:id,title',
                            'city:id,title',
                            'region:id,title',
                            'wantFields' => ['estateFields','wantItems' => ['estateFieldItems']]
                        ],
                    ],
                    'employee:id,full_name,phone_number',
                    'customer:id,full_name,phone_number'])
                ->join('register_estates', 'main_register_estates.id','=', 'register_estates.main_register_estate_id')
                ->join('main_register_wants','main_register_estates.id','=', 'main_register_wants.main_register_estate_id')
                ->when($request->estate_type_id, function ($query, $estate_type_id){
                    $query->whereHas('mainRegisterWant.wantEstate', function ($query) use($estate_type_id){
                        $query->where('estate_type_id', $estate_type_id);
                    });
                })
                ->when(\request('province_id'),function($query, $province){
                    $query->whereHas('mainRegisterWant.wantEstate', function ($query) use($province){
                        $query->where('province_id', $province);
                    });
                })
                ->when(\request('city_id'),function($query, $city){
                    $query->whereHas('mainRegisterWant.wantEstate', function ($query) use($city){
                        $query->where('city_id', $city);
                    });
                })
                ->when(\request('region_id'),function($query, $region_id){
                    $query->whereHas('mainRegisterWant.wantEstate', function ($query) use($region_id){
                        $query->where('region_id', $region_id);
                    });
                })
                ->select(
                    'main_register_estates.*',
                    DB::raw('
                        sum(register_estates.price) as total_price
                    ')
                )
                ->distinct()
                ->groupByRaw('main_register_estates.id,register_estates.main_register_estate_id,main_register_estates.employee_id,main_register_estates.updated_at,main_register_estates.deleted_at,main_register_estates.created_at,main_register_estates.type,main_register_estates.status, main_register_estates.customer_id  ,main_register_wants.barter_type, main_register_wants.barter_price')
                ->when($request->price, function ($query, $price){
                    $min_price = $price - (0.1 * $price);
                    $max_price = $price + (0.1 * $price);
                    $query->havingRaw("
                        CASE
                            WHEN barter_type = 1 THEN total_price - barter_price between $min_price and $max_price
                            WHEN barter_type = 2 THEN total_price + barter_price between $min_price and $max_price
                            ELSE total_price between $min_price and $max_price
                        END");


                })
                ->when($want->estate_type_id, function ($query, $estate_type_id){
                    $query->whereHas('assets', function ($query) use($estate_type_id){
                        $query->where('estate_type_id', $estate_type_id);
                    });
                })
                ->when($want->city_id, function ($query, $city_id){
                    $query->whereHas('assets', function ($query) use($city_id){
                        $query->where('city_id', $city_id);
                    });
                })
                ->when($want->region_id, function ($query, $region_id){
                    $query->whereHas('assets', function ($query) use($region_id){
                        $query->where('region_id', $region_id);
                    });
                })
                ->when($want->barter_type, function ($query, $barter_type) use ($want){
                    $total_price = match ($barter_type){
                        1 => \request('price') - $want->barter_price,
                        2 => \request('price') + $want->barter_price,
                        3 => \request('price'),
                    };
                    $min_price = $total_price - (0.1 * $total_price);
                    $max_price = $total_price + (0.1 * $total_price);
                   $query->havingBetween('total_price',[$min_price, $max_price]);

                })

                ;
//            logger($want->barter_price);
            logger($query->toRawSql());
            $estates = $query->paginate(45);
            return $this->successResponse([
                'estates' => $estates,
            ], 200);
        }catch (Exception $exception){
            return $exception;
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
    public function searchByAsset(Request $request){
        try {
            $employee_role_id= auth()->user()->role_id;
            if ($employee_role_id === 2 || $employee_role_id === 1){
                $detail_user=User::select('id', 'full_name', 'phone_number')->adviser()->get();
            }else{
                $detail_user[] = auth()->user();
            }
            $sort = 'desc';
            $want = json_decode($request->want);
            $estates = MainRegisterEstate::query()
                ->with([
                    'assets' => [
                        'estateType:id,title',
                        'province:id,title',
                        'city:id,title',
                        'region:id,title',
                        'registerEstateFields'=> ['estateFields'/*,'registerFieldItems' => ['estateFieldItems']*/]
                    ] ,
                    'mainRegisterWant' => [
                        'wantEstate' => [
                            'estateType:id,title',
                            'province:id,title',
                            'city:id,title',
                            'region:id,title',
                            'wantFields' => ['estateFields'/*,'wantItems' => ['estateFieldItems']*/]
                        ],
                    ],
                    'employee:id,full_name,phone_number',
                    'customer:id,full_name,phone_number'])
                ->orderBy('id', $sort)
                ->when(\request('estate_type_id') and count(json_decode(\request('estate_type_id'))),function($query, $estate_type_id){
                    $query->whereHas('assets', function ($query) use($estate_type_id){
                        $query->whereIn('estate_type_id', json_decode(\request('estate_type_id')));
                    });
                })
                ->when(\request('province_id'),function($query, $province){
                    $query->whereHas('assets', function ($query) use($province){
                        $query->where('province_id', json_decode($province));
                    });
                })
                ->when(\request('city_id'),function($query, $city){
                    $query->whereHas('assets', function ($query) use($city){
                        $query->where('city_id', $city);
                    });
                })
                ->when(\request('region_id') and count(json_decode(\request('region_id'))),function($query, $region_id){
                    $query->whereHas('assets', function ($query) use($region_id){
                        $query->whereIn('region_id', json_decode(\request('region_id')));
                    });
                })
                ->when(\request('price_az'),function($query, $price){
                    $query->whereHas('assets', function ($query) use($price){
                        $query->where('price', '>=',  $price);
                    });
                })
                ->when(\request('price_ta'),function($query, $price_ta){
                    $query->whereHas('assets', function ($query) use($price_ta){
                        $query->where('price', '<=',  $price_ta);
                    });
                })
                ->when(\request('timestamp_az'),function($query, $timestamp_az){
                    $query->whereHas('assets', function ($query) use($timestamp_az){
                        $query->whereDate('created_at', '>=', $timestamp_az);
                    });
                })
                ->when(\request('timestamp_ta'),function($query, $timestamp_ta){
                    $query->whereHas('assets', function ($query) use($timestamp_ta){
                        $query->whereDate('created_at', '<=', $timestamp_ta);
                    });
                })
                ->when(\request('status') > -1 ,function($query, $status){
                    $query->whereHas('assets', function ($query) use($status){
                        $query->where('status', request('status'));
                    });
                })
                ->when($want->barter_type,function($query, $barterTypet){
                    $query->whereHas('mainRegisterWant', function ($query) use($barterTypet){
                        $query->where('barter_type', $barterTypet);
                    });
                })
                ->when($want->barter_price,function($query, $barterPriceWant){
                    $query->whereHas('mainRegisterWant', function ($query) use($barterPriceWant){
                        $query->where('barter_price', $barterPriceWant);
                    });
                })
                ->when($want->city_id,function($query, $cityWant){
                    $query->whereHas('mainRegisterWant.wantEstate', function ($query) use($cityWant){
                        $query->where('city_id', $cityWant);
                    });
                })
                ->when($want->region_id,function($query, $regionWant){
                    $query->whereHas('mainRegisterWant.wantEstate', function ($query) use($regionWant){
                        $query->where('region_id', $regionWant);
                    });
                })
                ->when($want->estate_type_id,function($query, $estateTypeWantId){
                    $query->whereHas('mainRegisterWant.wantEstate', function ($query) use($estateTypeWantId){
                        $query->where('estate_type_id', $estateTypeWantId);
                    });
                })
                ->when(\request('adviser'),function($query, $adviser){
                    $query->where('employee_id', (int)$adviser);
                })->withCount(['FileComments']);
                logger($estates->toRawSql());
                $paginate=$estates->paginate(45);
            return $this->successResponse([
                'advisers' => $detail_user,
                'estates' => $paginate,
            ], 200);
        }catch (Exception $exception){
            return $exception;
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
    public function deleteWant()
    {
        try {
            DB::beginTransaction();
            $id = \request('id');
            switch (\request('type')){
                case 1:
                    MainRegisterWant::destroy($id);
                    RegisterWantEstateType::where(['main_register_want_id' => $id])->delete();
                    RegisterWantEstateField::where(['main_register_want_id' => $id])->delete();
                    RegisterWantEstateItem::where(['main_register_want_id' => $id])->delete();

                    break;
                case 2:
                    RegisterWantEstateType::destroy($id);
                    RegisterWantEstateField::where('register_want_estate_type_id', $id)->delete();
                    RegisterWantEstateItem::where('register_want_estate_type_id', $id)->delete();

            }
            DB::commit();
            return $this->successResponse($id, 201);
        }
        catch (Exception $exception) {
            DB::rollback();
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
}
