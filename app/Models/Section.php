<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Section extends Model
{
  use HasFactory;
  protected $table = 'sections';
  protected $primaryKey = 'idsection';
  protected $fillable = [
    'name', 
    'description',
    'image',
    'deleted'
  ];
  protected $appends = ['hashSection', 'md5Section'];

  public function getHashSectionAttribute()
  {
    return Crypt::encryptString($this->idsection);
  }

  public function getMd5SectionAttribute()
  {
    return md5($this->idsection);
  }
}
