<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model {
    protected $guarded = [];
    protected $primaryKey = 'membership_id';
    protected $table = 'memberships';
    use HasFactory;
}
