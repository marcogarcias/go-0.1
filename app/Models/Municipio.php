<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Municipio extends Model
{
  use HasFactory;
  protected $table = 'municipios';
  protected $primaryKey = 'idmunicipio';
  protected $appends = ['hashMunicipio', 'md5Municipio'];

  public function getHashMunicipioAttribute()
  {
    return Crypt::encryptString($this->idmunicipio);
  }

  public function getMd5MunicipioAttribute()
  {
    return md5($this->idmunicipio);
  }

  // Modelo Municipio
  public function estado()
  {
    return $this->belongsTo(Estado::class, 'estado_id');
  }
}
