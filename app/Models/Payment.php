<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $guarded = [];
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    use HasFactory;

    public function client() {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }
}
