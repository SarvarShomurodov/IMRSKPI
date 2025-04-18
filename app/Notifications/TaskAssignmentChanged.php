<?php

// app/Notifications/TaskAssignmentChanged.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskAssignmentChanged extends Notification
{
    use Queueable;

    public $action;
    public $assignment;

    public function __construct($action, $assignment)
    {
        $this->action = $action;
        $this->assignment = $assignment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Sizga bir tayinlov {$this->action}",
            'assignment_id' => $this->assignment->id,
            'subtask' => $this->assignment->subtask->name ?? '—',
            'time' => now(),
        ];
    }
}
