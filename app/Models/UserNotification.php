<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model{
  use HasFactory;
  protected $table = 'user_notifications';
  protected $primaryKey = 'idUserNotification';
  protected $fillable = [
    'name', 
    'description',
    'view',
    'notification_id',
    'userFrom_id',
    'userTo_id',
    'disabled',
    'deleted'
  ];
}
