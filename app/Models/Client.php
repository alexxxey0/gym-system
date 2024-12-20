<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable {
    protected $table = 'clients';
    protected $guarded = [];
    protected $primaryKey = 'client_id';
    use HasFactory;

    public function username() {
        return 'personal_id';
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }
}
