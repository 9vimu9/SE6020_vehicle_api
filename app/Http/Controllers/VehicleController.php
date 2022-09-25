<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleStoreRequest;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VehicleController extends Controller
{
    public function store(VehicleStoreRequest $request): JsonResponse
    {
        $vehicle = Vehicle::create([
                "user_id" => auth()->user()->id,
                "guid" => Str::uuid(16),
                "vehicle_registration_number" => $request->input("vehicle_registration_number"),
                "registered_date" => "2022-12-12",
                "chassis_number" => "sdsafasfasdfasf",
            ]
        );

        return response()->json($vehicle->toArray(),201);


    }


    public function qr(Request $request)
    {

    }
}
