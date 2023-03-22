<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    public function brandStatuses()
    {
        return $this->hasMany(BrandStatus::class);
    }

    public function currentStatus()
    {
        return $this->brandStatuses()->latest()->first();
    }
}
