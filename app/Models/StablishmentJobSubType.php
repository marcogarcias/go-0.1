<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StablishmentJobSubType extends Model{
  use HasFactory;

  protected $table = "stablishments_jobs_subtype";
  protected $primaryKey = "idStabJobSubType";
  protected $fillable = [
    "job_id", 
    "jobSubType_id",
    "deleted"
  ];
}
