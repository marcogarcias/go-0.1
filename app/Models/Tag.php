<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Tag extends Model
{
  use HasFactory;
  protected $table = 'tags';
  protected $primaryKey = 'idtag';
  protected $appends = ['hashTag', 'md5Tag'];

  public function getHashTagAttribute()
  {
    return Crypt::encryptString($this->idtag);
  }

  public function getMd5TagAttribute()
  {
    return md5($this->idtag);
  }

  public function publications(){
    return $this->belongsToMany(Publication::class, 'publications_tags', 'tag_id', 'publication_id');
  }
}
