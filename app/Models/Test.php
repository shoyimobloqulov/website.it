<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $fillable = ['task_id', 'file_path'];

    public function task(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tasks::class);
    }

    public function inputsOutputs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TestInputOutput::class);
    }
}
