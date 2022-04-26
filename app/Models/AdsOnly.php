<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdsOnly extends Model{
  use HasFactory;
  protected $table = 'ads_only';
  protected $primaryKey = 'idAdOnly';
  protected $fillable = [
    'name', 
    'description',
    'path',
    'image',
    'url',
    'stablishment_id',
    'disabled',
    'deleted'
  ];
}
