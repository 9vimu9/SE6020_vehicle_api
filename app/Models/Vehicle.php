<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "guid",
        "vehicle_registration_number",
        "registered_date",
        "chassis_number"];
}
