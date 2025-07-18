<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleImage extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'vehicle_images';

    protected $fillable = [
        'vehicle_id',
        'imagePath',
        'imageType',
        'originalName',
    ];

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }
}
