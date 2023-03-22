<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandStatus extends Model
{
    use HasFactory;

    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    const STATUSES = [
        self::ACTIVE,
        self::INACTIVE,
    ];

    protected $fillable = [
        'status',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
