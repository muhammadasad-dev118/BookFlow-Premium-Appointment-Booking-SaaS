<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory, BelongsToTenant;

    protected $guarded = [];

    public function user() { return $this->belongsTo(User::class); }
    public function services() { return $this->belongsToMany(Service::class); }
    public function businessHours() { return $this->hasMany(BusinessHour::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
}
