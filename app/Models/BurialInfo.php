<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BurialInfo extends Model
{
    use HasFactory;

    protected $table = 'burial_info'; // Table name overrides default pluralization if needed

    protected $fillable = [
        'person_id',
        'burial_place',
        'burial_date',
        'death_date_full',
        'gps_coordinates',
        'grave_type',
        'grave_description',
        'grave_photo_path',
    ];

    protected $casts = [
        'burial_date' => 'date',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
