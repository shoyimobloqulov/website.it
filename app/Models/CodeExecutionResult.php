<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CodeExecutionResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'language',
        'version',
        'code',
        'code_length',
        'output',
        'error',
        'execution_time',
        'memory_used',
        'task_id'
    ];
}
