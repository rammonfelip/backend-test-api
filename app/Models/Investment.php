<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    CONST GAIN_PERCENT = 0.0052;

    protected $table = 'investment';
    protected $fillable = [
        'person_id', 'initial_value', 'date', 'gain', 'withdraw', 'final_value'
    ];
    protected $appends = [
        'expected_balance'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            $model->gain = self::GAIN_PERCENT;
        });
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdraw::class);
    }

    public function getExpectedBalanceAttribute()
    {
        return $this->movements->last() ? $this->movements->last()->updated_value : 0;
    }
}
