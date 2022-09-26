<?php

namespace App\Http\Controllers;

use App\Http\Requests\QRRequest;
use App\Http\Requests\VehicleStoreRequest;
use App\Models\Vehicle;
use App\Services\QuotaData\QuotaDataClient;
use App\Services\VehicleData\VehicleDataClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
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
//            (new QuotaDataClient())->addQuota($vehicle->id);//temp remove
            return response()->json($vehicle->toArray(), 201);
        } catch (GuzzleException|JsonException  $exception) {
            return response()->json(["message" => "Something went wrong","error"=>(array)$exception], 500);
        }

    }


    public function qr(QRRequest $request): JsonResponse
    {
        try {
            $vehicleRegistrationNumber = $request->input("vehicle_registration_number");

            $vehicle = Vehicle::where("vehicle_registration_number", $vehicleRegistrationNumber)
                ->where("user_id", auth()->user()->id)
                ->firstOrFail();

            return response()->json(
                [
                    "qr" => $vehicle->vehicle_registration_number . " | " . $vehicle->guid
                ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(["message" => "no vehicle registered"], 404);
        }

    }
}
