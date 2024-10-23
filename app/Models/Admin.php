<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable {
    protected $table = 'admin';
    protected $primaryKey = 'id';
    use HasFactory;

    public function username() {
        return 'login';
    }
}
