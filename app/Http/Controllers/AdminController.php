<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandCollection;
use App\Http\Resources\BrandResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderStatusResource;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use App\Models\Brand;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function logout()
    {
        return (new OrderResource(Order::first()))
//        return (new OrderResource(collect(Order::all()->take(rand(1,5)))))
            ->withSuccess(1)
            ->withError(null)
            ->withErrors([])
            ->withExtra([]);

    }

}
