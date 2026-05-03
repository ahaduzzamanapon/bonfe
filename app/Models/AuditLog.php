<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id',
        'old_values', 'new_values', 'ip_address', 'user_agent', 'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log a critical action.
     */
    public static function log(
        string $action,
        $model = null,
        $modelId = null,
        array $oldValues = [],
        array $newValues = [],
        string $description = ''
    ): void {
        try {
            static::create([
                'user_id'    => auth()->check() ? auth()->id() : null,
                'action'     => $action,
                'model_type' => $model,
                'model_id'   => $modelId,
                'old_values' => empty($oldValues) ? null : $oldValues,
                'new_values' => empty($newValues) ? null : $newValues,
                'ip_address' => request()->ip(),
                'user_agent' => substr(request()->userAgent() ?? '', 0, 500),
                'description' => $description,
                'created_at'  => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::error('AuditLog::log failed: ' . $e->getMessage());
        }
    }
}
