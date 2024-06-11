<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PublicationTag extends Model
{
  use SoftDeletes;
  use HasFactory;
  protected $table = 'publications_tags';
  protected $primaryKey = 'id';
  protected $fillable = ['publication_id', 'tag_id', 'deleted_at'];

  public function publication(){
    return $this->belongsTo(Publication::class);
  }

  public function tag(){
    return $this->belongsTo(Tag::class);
  }
}
