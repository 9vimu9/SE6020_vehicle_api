<?php

namespace App\Services\QuotaData;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use RuntimeException;

class QuotaDataClient
{
    /**
     * @throws GuzzleException
     */
    public function addQuota(int $vehicleID)
    {

        $response = (new Client([
            'headers' => [
                'Authorization' => 'Bearer ' . request()->bearerToken()
            ]
        ]))->get(
            config("vehicles.quota_data_api_url"),
            [
                RequestOptions::JSON =>
                    [
                        'vehicle_id' => $vehicleID,
                        'quota' => config("vehicles.initial_quota"),
                    ],
                'http_errors' => false
            ]
        );
        dd("Fff");
        $statusCode = $response->getStatusCode();

        if ($statusCode !== 201) {
            throw new RuntimeException("Initial Quota insert error");
        }

    }

}
