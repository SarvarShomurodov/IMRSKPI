<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['taskName', 'description'];

    public function subTasks()
    {
        return $this->hasMany(SubTask::class, 'task_id');
    }

    // public function taskAssignments()
    // {
    //     return $this->hasMany(TaskAssignment::class, 'task_id');
    // }
}
