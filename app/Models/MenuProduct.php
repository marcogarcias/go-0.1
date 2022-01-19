<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuProduct extends Model{
  use HasFactory;

  protected $table = 'menus_products';
  protected $primaryKey = 'idproduct';
  protected $fillable = [
    'name', 
    'description',
    'price',
    'price_discount',
    'disabled',
    'menu_id',
    'deleted'
  ];
}
