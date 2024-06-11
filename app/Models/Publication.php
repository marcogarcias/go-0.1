<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Publication extends Model
{
  use SoftDeletes;
  use HasFactory;
  protected $table = 'publications';
  protected $primaryKey = 'idPublication';
  protected $fillable = [
    'title', 
    'subtitle',
    'pseudonym',
    'datetime',
    'synopsis',
    'description',
    'price',
    'address',
    'lat',
    'lng',
    'image',
    'facebook',
    'instagram',
    'twitter',
    'youtube',
    'web',
    'likes',
    'visits',
    'disabled',
    'disabledGlobal',
    'user_id',
    'municipio_id',
    'section_id',
    'deleted_at',
  ];
  protected $appends = ['hashPublication', 'md5Publication', 'md5Section'];

  public function getHashPublicationAttribute()
  {
    return Crypt::encryptString($this->idPublication);
  }

  public function getMd5PublicationAttribute()
  {
    return md5($this->idPublication);
  }

  public function getMd5SectionAttribute()
  {
    return md5($this->section_id);
  }

  public function gallery()
  {
    return $this->hasMany(PublicationGallery::class, 'publication_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function municipio()
  {
    return $this->belongsTo(Municipio::class, 'municipio_id');
  }

  public function section()
  {
    return $this->belongsTo(Section::class, 'section_id');
  }

  public function tags(){
    return $this->belongsToMany(Tag::class, 'publications_tags', 'publication_id', 'tag_id');
  }
}
