<?php

namespace App\Models;

use App\Models\Car;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarImage extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'car_images';

    protected $fillable = [
        'carId',
        'imagePath',
        'imageType',
        'originalName'
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'carId');
    }
}
