<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserJobProfile extends Model{
  use HasFactory;
  protected $table = 'user_jobprofile';
  protected $primaryKey = 'idJobProfile';
  protected $fillable = [
    'name',
    'nextName',
    'ap',
    'am',
    'fullName',
    'email',
    'cellphone',
    'age',
    'gender',
    'description',
    'academicHistory',
    'jobHistory',
    'photoPath',
    'photoName',
    'disabledGlobal',
    'disabled',
    'user_id',
    'deleted'
  ];
}
