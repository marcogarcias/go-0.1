<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StablishmentGallery extends Model{
  use HasFactory;
  protected $table = 'stablishments_gallery';
  protected $primaryKey = 'idgallery';
  protected $fillable = [
    'name',
    'description',
    'path',
    'image',
    'order',
    'disabled',
    'stablishment_id',
    'deleted'
  ];
}
