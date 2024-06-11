<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PublicationGallery extends Model
{
  use SoftDeletes;
  use HasFactory;
  protected $table = 'publications_gallery';
  protected $primaryKey = 'id';
  protected $fillable = ['name', 'description', 'order', 'disabled', 'publication_id', 'deleted_at'];
  protected $appends = ['hashGallery', 'md5Gallery'];

  public function getHashGalleryAttribute()
  {
    return Crypt::encryptString($this->id);
  }

  public function getMd5GalleryAttribute()
  {
    return md5($this->id);
  }

  public function publication()
  {
    return $this->belongsTo(Publication::class, 'publication_id');
  }
}
