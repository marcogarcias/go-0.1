<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
  use HasFactory;

  protected $table = 'chat_messages';
  protected $primaryKey = 'idchat';

  protected $fillable = [
        'message',
        'vieweddate',
        'from',
        'userclient_id',
        'userstablishment_id',
        'deleted'
    ];
}
