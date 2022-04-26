<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatNotification extends Model{
  use HasFactory;
  protected $table = 'cat_notifications';
  protected $primaryKey = 'idNotification';
  protected $fillable = [
    'name', 
    'description',
    'icon',
    'color',
    'disabled',
    'deleted'
  ];
}
