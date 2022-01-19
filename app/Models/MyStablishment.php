<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyStablishment extends Model
{
    use HasFactory;
    protected $table = 'my_stablishments';
    protected $primaryKey = 'idmystablishment';

    protected $fillable = [
        'user_id',
        'stablishment_id',
        'deleted'
    ];
}
