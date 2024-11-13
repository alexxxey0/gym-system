<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientTraining extends Model {
    protected $table = 'client_training';
    protected $guarded = [];
    protected $primaryKey = 'record_id';
    use HasFactory;
}
