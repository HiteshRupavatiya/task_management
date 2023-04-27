<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ListingApiTrait;

    public function list(Request $request)
    {
        $this->ListingValidation();
        $query = Task::query()->with('user');

        if (auth()->user()->type == 'Employee') {
            $query->where('user_id', auth()->user()->id);
        }

        $searchableFields = ['name', 'description', 'status'];

        $data = $this->filterSearchPagination($query, $searchableFields);

        return ok('Tasks fetched successfully', [
            'tasks' => $data['query']->get(),
            'count' => $data['count']
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|min:10|max:100',
            'description' => 'required|string',
            'user_id'     => 'required|exists:users,id'
        ]);

        $task = Task::create($request->only(
            [
                'name',
                'description',
                'user_id'
            ]
        ));

        return ok('Task created successfully', $task);
    }

    public function get($id)
    {
        $task = Task::where('user_id', auth()->user()->id)->find($id);
        if ($task) {
            return ok('Task fetched successfully', $task);
        }
        return error('Task not found', type: 'notfound');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'string',
            'description' => 'string',
            'status'      => 'required|in:Pending,In-Progress,Completed'
        ]);

        if (auth()->user()->type == 'Team leader') {
            $task = Task::find($id);
            $name = $request->input('name') ?? $task->name;
            $description = $request->input('description') ?? $task->description;
            $status = $request->input('status') ?? $task->status;

            if ($task) {
                $task->update([
                    'name'        => $name,
                    'description' => $description,
                    'status'      => $status
                ]);

                return ok('Task updated successfully');
            }
            return error('Task not found', type: 'notfound');
        }

        if (auth()->user()->type == 'Employee') {
            $task = Task::where('user_id', auth()->user()->id)->find($id);
            $status = $request->input('status') ?? $task->status;

            if ($task) {
                $task->update([
                    'status' => $status
                ]);

                return ok('Task status updated successfully');
            }
            return error('Task not found', type: 'notfound');
        }
    }

    public function delete($id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->delete();
            return ok('Task deleted successfully');
        }
        return error('Task not found', type: 'notfound');
    }
}
