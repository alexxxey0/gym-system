<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gym extends Model {
    use HasFactory;
    protected $table = 'gyms';
    protected $guarded = [];
    protected $primaryKey = 'gym_id';

    public function clients() {
        return $this->hasMany(Client::class);
    }
}
