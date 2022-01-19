<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
  use HasFactory;
  protected $table = 'advertisements';
  protected $primaryKey = 'idadvertisements';

  protected $fillable = [
        'name',
        'description',
        'data',
        'notes',
        'image',
        'deleted'
    ];
}
