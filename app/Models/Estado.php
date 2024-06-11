<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Estado extends Model
{
  use HasFactory;
  protected $table = 'estados';
  protected $primaryKey = 'idestado';
  protected $appends = ['hashEstado', 'md5Estado'];

  public function getHashEstadoAttribute()
  {
    return Crypt::encryptString($this->idestado);
  }

  public function getMd5EstadoAttribute()
  {
    return md5($this->idestado);
  }
}
