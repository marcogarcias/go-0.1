<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobProfileJob extends Model{
  use HasFactory;
  protected $table = 'jobprofile_jobs';
  protected $primaryKey = 'idProfileJob';
  protected $fillable = [
    'jobProfile_id', 
    'job_id',
    'disabled',
    'deleted'
  ];
}
