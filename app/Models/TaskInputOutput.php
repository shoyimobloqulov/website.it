<?php

namespace App\Models;

use Illuminate\Console\View\Components\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskInputOutput extends Model
{
    use HasFactory;
    protected $table = "task_inputs_outputs";

    protected $fillable = [
        'task_id',
        'input',
        'output'
    ];

    public function task(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
