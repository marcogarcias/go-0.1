<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StablishmentJob extends Model
{
  use HasFactory;
  protected $table = "stablishments_jobs";
  protected $primaryKey = "idjob";
  protected $fillable = [
    "name", 
    "description",
    "requirements",
    "documentation",
    "stablishment_id",
    "jobType_id",
    "deleted"
  ];
}
