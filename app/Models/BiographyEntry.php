<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiographyEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'content',
        'time_period',
        'display_order',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
