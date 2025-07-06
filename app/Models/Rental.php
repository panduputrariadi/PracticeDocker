<?php

namespace App\Models;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rental extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected $table = 'rentals';

    protected $fillable = [
        'carId',
        'userId',
        'rentalStart',
        'rentalEnd',
        'totalPayment',
        'status',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'carId');
    }
    public function userRental()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
