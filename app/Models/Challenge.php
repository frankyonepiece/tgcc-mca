<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Challenge extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'name',
    ];


    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    /**
     * Get all played challenges
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'challenge_id', 'id');
    }

    public function activeRoom(): HasOne
    {
        return $this->hasOne(Room::class, 'challenge_id', 'id')
            ->whereNot('status', 'ended');
    }

    public function penddingRoom(): HasOne
    {
        return $this->hasOne(Room::class, 'challenge_id', 'id')
            ->where('status', 'pendding')
            ->latest('created_at');
    }
    
}
