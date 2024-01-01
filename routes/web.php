<?php

use App\Models\MainRegisterWant;
use App\Models\RegisterWantEstateType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Morilog\Jalali\Jalalian;

Route::get('/', function () {

    return "home";
});

Route::get('test', function (){
    logger("1");
    //observe ha roy 1 item call meshe !!!!
    $user=User::query()->findOrFail( 226);
    $user->full_name='dgsfdhstrsy';
    $user->save();
    dd($user);
    logger("2");
});
