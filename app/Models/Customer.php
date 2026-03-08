<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory, BelongsToTenant, Billable;

    protected $guarded = [];

    public function appointments() { return $this->hasMany(Appointment::class); }
}
