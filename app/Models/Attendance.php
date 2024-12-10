<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model {
    protected $guarded = [];
    protected $table = 'attendance';
    protected $primaryKey = 'attendance_id';
    use HasFactory;

    public function client() {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }
}
