<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Tasks extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'condition',
        'input',
        'output',
        'note',
        'time',
        'memory',
        'difficulty',
        'user_id',
        'key'
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->key)) {
                $model->key = Str::uuid()->toString();
            }
        });
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sample(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TaskInputOutput::class,'task_id');
    }
}
