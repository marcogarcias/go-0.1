<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StablishmentTag extends Model
{
  use HasFactory;
  protected $table = 'stablishments_tags';
  protected $primaryKey = 'id';
  protected $fillable = ['stablishment_id', 'tag_id', 'deleted'];
}
