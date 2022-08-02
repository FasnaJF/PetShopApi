<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Payment extends Model
{
    use HasFactory;
    use HasJsonRelationships;


    protected $casts = [
        'details' => 'json',
    ];
}
