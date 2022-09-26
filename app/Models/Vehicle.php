<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "qr_code_path",
        "guid",
        "vehicle_registration_number",
        "registered_date",
        "chassis_number"];
}
