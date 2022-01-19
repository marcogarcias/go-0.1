<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StablishmentAd extends Model
{
  use HasFactory;
  protected $table = 'stablishments_ads';
  protected $primaryKey = 'idad';
  protected $fillable = [
    'name', 
    'description',
    'stablishment_id',
    'deleted'
  ];
}
