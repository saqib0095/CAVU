<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['space_id','start_time','end_time'];
    protected $table = 'Reservation';
    protected $primaryKey = 'reservation_id';
    public $timestamps = false;

    public function parkingSpace()
    {
        return $this->belongsTo(ParkingSpace::class);
    }
}
