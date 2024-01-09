<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stablishmentMenuPdf extends Model
{
  use HasFactory;
  protected $table = 'stablishments_menus_pdf';
  protected $primaryKey = 'idMenuPdf';
  protected $fillable = [
    'stablishment_id', 
    'name', 
    'description',
    'path',
    'pdf',
    'order',
    'disabled',
    'deleted',
  ];
}
