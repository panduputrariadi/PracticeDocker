<?php

namespace App\Models;

use App\Models\CarImage;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes, HasUuids;
    // protected $fillable = [
    //     'brand',
    //     'model',
    //     'year',
    //     'status',
    //     'price',
    //     'color',
    //     'type',
    //     'description',
    //     'plateNumber',
    // ];
    protected $fillable = [
        'brand', 'model', 'year', 'status', 'price',
        'color', 'type', 'description', 'plateNumber'
    ];


    public function images()
    {
        return $this->hasMany(CarImage::class, 'carId');
    }
}
