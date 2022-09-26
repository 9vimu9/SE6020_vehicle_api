<?php

namespace App\Services\QRCode;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeBuilder
{
    private Vehicle $vehicle;

    public function __construct(int $vehicleID)
    {
        $this->vehicle = Vehicle::findOrFail($vehicleID);
    }

    private function createQR(): string
    {
        $vehicle = $this->vehicle;
        return QrCode::format('png')
            ->size(200)->errorCorrection('H')
            ->generate($vehicle->vehicle_registration_number . " | " . $vehicle->guid);
    }

    private function storeQR(string $image): void
    {
        $imagePath = "/user_data/qr_codes/{$this->vehicle->guid}-" . time() . ".png";
        Storage::disk('s3')->put($imagePath, $image);

    }

    public function s3URL(): string
    {
        $S3RelativePath = "/user_data/qr_codes/{$this->vehicle->guid}-" . time() . ".png";
        return Storage::disk('s3')
            ->getClient()
            ->getObjectUrl(config('filesystems.disks.s3.bucket'), $S3RelativePath);

    }

    private function saveQRPath(): Vehicle
    {
        $this->vehicle->update(["qr_code_path" => $this->s3URL()]);
        return $this->vehicle;
    }

    public function build(): Vehicle
    {
        $this->storeQR($this->createQR());
        return $this->saveQRPath();
    }

}
