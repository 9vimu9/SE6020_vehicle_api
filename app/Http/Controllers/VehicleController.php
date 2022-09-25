<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleStoreRequest;
use App\Models\Vehicle;
use App\Services\VehicleData\VehicleDataClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use JsonException;

class VehicleController extends Controller
{
    /**
     * @throws GuzzleException
     * @throws JsonException
     * @throws ValidationException
     */
    public function store(VehicleStoreRequest $request): JsonResponse
    {
        try {
            $vehicleRegistrationNumber = $request->input("vehicle_registration_number");
            $registeredVehicle = (new VehicleDataClient())
                ->vehicleDataByVehicleRegistrationNumber($vehicleRegistrationNumber);
            $vehicle = Vehicle::create([
                    "user_id" => auth()->user()->id,
                    "guid" => Str::uuid(16),
                    "vehicle_registration_number" => $vehicleRegistrationNumber,
                    "registered_date" => $registeredVehicle['registered_date'],
                    "chassis_number" => $registeredVehicle['chassis_number'],
                ]
            );
            return response()->json($vehicle->toArray(), 201);
        } catch (GuzzleException|JsonException  $exception) {
            return response()->json(["message" => "something went wrong"], 500);
        }

    }


    public function qr(Request $request)
    {

    }
}
