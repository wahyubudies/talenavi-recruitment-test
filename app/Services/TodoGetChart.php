<?php

namespace App\Services;

use App\Models\Todo;
use App\Traits\ApiResponseTrait;

class TodoGetChart
{
    use ApiResponseTrait;
    public function getChart($type)
    {
        switch ($type) {
            case 'status':
                return $this->getStatusSummary();
            case 'priority':
                return $this->getPrioritySummary();
            case 'assignee':
                return $this->getAssigneeSummary();
            default:
                return $this->errorResponse('Error!', 'Invalid type', 400);
        }
    }

    protected function getStatusSummary()
    {
        return [
            'status_summary' => Todo::query()
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray()
        ];
    }

    protected function getPrioritySummary()
    {
        return [
            'priority_summary' => Todo::query()
                ->selectRaw('priority, COUNT(*) as count')
                ->groupBy('priority')
                ->pluck('count', 'priority')
                ->toArray()
        ];
    }

    protected function getAssigneeSummary()
    {
        return [
            'assignee_summary' => Todo::query()
                ->selectRaw('assignee, COUNT(*) as total_todos, SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as total_pending_todos, SUM(time_tracked) as total_timetracked_completed_todos')
                ->groupBy('assignee')
                ->get()
                ->keyBy('assignee')
                ->mapWithKeys(function ($item) {
                    return [$item->assignee => collect($item)->except('assignee')];
                })
                ->toArray()
        ];
    }
}
