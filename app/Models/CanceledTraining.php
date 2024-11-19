<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanceledTraining extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'canceled_trainings';
    protected $primaryKey = 'canceled_training_id';
}
