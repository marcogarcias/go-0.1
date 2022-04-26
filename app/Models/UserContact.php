<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserContact extends Model{
  use HasFactory;
  protected $table = 'user_contacts';
  protected $primaryKey = 'idContact';
  protected $fillable = [
    'name',
    'description',
    'birthday',
    'phone',
    'whatsapp',
    'facebook',
    'instagram',
    'telegram',
    'snapchat',
    'tiktok',
    'youtube',
    'userFrom_id',
    'userTo_id',
    'disabled',
    'deleted'
  ];
}
