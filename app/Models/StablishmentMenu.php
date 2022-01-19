<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StablishmentMenu extends Model{
  use HasFactory;

  protected $table = 'stablishments_menus';
  protected $primaryKey = 'idmenu';
  protected $fillable = [
    'name', 
    'description',
    'disabled',
    'stablishment_id',
    'deleted'
  ];
}
