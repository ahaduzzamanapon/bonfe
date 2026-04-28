<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MultipleDistrict
 * Stores multiple district access per user (for filtered_by_multi_district permission).
 *
 * @property integer $user_id
 * @property integer $district_id
 */
class MultipleDistrict extends Model
{
    public $table = 'multiple_districts';

    public $fillable = [
        'user_id',
        'district_id',
    ];

    protected $casts = [
        'id'          => 'integer',
        'user_id'     => 'integer',
        'district_id' => 'integer',
    ];

    public static $rules = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
