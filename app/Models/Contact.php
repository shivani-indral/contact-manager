<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
     // Specify the fields that can be mass assigned
     protected $fillable = [
        'name',
        'lastname',
        'phone',
    ];

}
