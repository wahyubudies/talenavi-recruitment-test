<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Services\TodoExportService;
use App\Services\TodoGetChart;
use App\Services\TodoService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TodoController extends Controller
{
    use ApiResponseTrait;

    public function store(Request $request, TodoService $todoService){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'assignee' => 'nullable|string',
            'due_date' => 'required|date|after_or_equal:today',
            'time_tracked' => 'numeric',
            'status' => 'in:pending,open,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Validation Error', 422);
        }

        $todo = $todoService->createItem($request->all());

        return $this->successResponse($todo, 'Todo created successfully');
    }

    public function exportExcel(Request $request, TodoExportService $todoExportService){
        $todos = Todo::query()
            ->when($request->title, fn($query, $title) => $query->where('title', 'like', "%$title%"))
            ->when($request->assignee, fn($query, $assignees) => $query->whereIn('assignee', explode(',', $assignees)))
            ->when($request->start && $request->end, fn($query) => $query->whereBetween('due_date', [$request->start, $request->end]))
            ->when($request->min && $request->max, fn($query) => $query->whereBetween('time_tracked', [$request->min, $request->max]))
            ->when($request->status, fn($query, $statuses) => $query->whereIn('status', explode(',', $statuses)))
            ->when($request->priority, fn($query, $priorities) => $query->whereIn('priority', explode(',', $priorities)))
            ->get();

        $fileName = 'todo_' . now()->format('Ymd') . '_' . strtolower(Str::random(4)) . '.xlsx';
        return $todoExportService->export($fileName);
    }

    public function getChart(Request $request, TodoGetChart $todoGetChart ){
        $type = $request->query('type');

        $data = $todoGetChart->getChart($type);

        return response()->json($data);
    }
}
