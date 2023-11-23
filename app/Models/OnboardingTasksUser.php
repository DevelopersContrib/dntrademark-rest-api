<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingTasksUser extends Model
{
    use HasFactory;
    
    // protected $table = 'onboarding_tasks_users';

    public function task () {
        return $this->belongsTo(OnboardingTask::class);
    }
}
