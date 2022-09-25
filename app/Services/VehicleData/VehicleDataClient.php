<?php

namespace App\Services\VehicleData;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Validation\ValidationException;
use JsonException;
use RuntimeException;

class VehicleDataClient
{
    /**
     * @throws GuzzleException
     * @throws JsonException
     * @throws ValidationException
     */
    public function vehicleDataByVehicleRegistrationNumber(string $vehicleRegistrationNumber): mixed
    {
        $response = (new Client())->post(
            config("vehicles.vehicle_data_api_url"),
            [
                RequestOptions::JSON =>
                    ['vehicle_registration_number' => $vehicleRegistrationNumber],
                'http_errors' => false
            ]
        );
        $statusCode = $response->getStatusCode();
        if ($statusCode === 404) {
            throw ValidationException::withMessages([
                'vehicle_registration_number' => ['no vehicle is registered under this number'],
            ]);
        }

        if ($statusCode === 200) {
            return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        }
        throw new RuntimeException("vehicle registration data fetch error");
    }

}
