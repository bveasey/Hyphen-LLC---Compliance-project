<?php

namespace App\Models;

use App\Services\Perimeter81Service;
use App\Services\SlackService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    const MODEL_TYPE_MAP = [
        'slack' => SlackService::class,
        'perimeter_81' => Perimeter81Service::class,
    ];

    protected $fillable = [
        'name',
        'service_slug',
        'base_url',
        'token',
    ];
}
