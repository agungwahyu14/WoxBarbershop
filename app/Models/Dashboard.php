<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'metric_name',
        'metric_value',
        'metric_type',
        'date',
        'period',
        'additional_data',
    ];

    protected $casts = [
        'date' => 'date',
        'additional_data' => 'array',
    ];

    public static function recordMetric(string $metricName, $value, string $type = 'count', string $period = 'daily', ?array $additionalData = null): void
    {
        self::updateOrCreate(
            [
                'metric_name' => $metricName,
                'date' => today(),
                'period' => $period,
            ],
            [
                'metric_value' => $value,
                'metric_type' => $type,
                'additional_data' => $additionalData,
            ]
        );
    }
}
