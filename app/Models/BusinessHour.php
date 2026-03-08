<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    use HasFactory, BelongsToTenant;

    protected $guarded = [];

    public function staff() { return $this->belongsTo(Staff::class); }
}
