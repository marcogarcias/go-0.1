<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stablishment extends Model
{
  use HasFactory;
  protected $table = 'stablishments';
  protected $primaryKey = 'idstablishment';
  protected $fillable = [
    'name', 
    'description',
    'description2',
    'direction',
    'lat',
    'lng',
    'image',
    'summary',
    'phone',
    'whatsapp',
    'facebook',
    'instagram',
    'twitter',
    'youtube',
    'hour',
    'likes',
    'range',
    'offer',
    'disabled',
    'disabledGlobal',
    'visible',
    'expiration',
    'municipio_id',
    'zone_id',
    'section_id',
    'user_id',
    'deleted'
  ];
}
