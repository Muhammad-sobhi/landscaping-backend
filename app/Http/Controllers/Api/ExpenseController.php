<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Job;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        // Return all expenses
        return response()->json(Expense::all());
    }

    /**
     * Get expenses specifically for a job
     */
    public function getByJob(Job $job)
    {
        return response()->json([
            'expenses' => $job->expenses
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_id'      => 'required|exists:jobs,id',
            'category'    => 'required|string', 
            'amount'      => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'spent_at'    => 'nullable|date', // Changed to nullable since we handle it in frontend or here
        ]);

        // If spent_at wasn't provided, use today
        if (empty($validated['spent_at'])) {
            $validated['spent_at'] = now()->format('Y-m-d');
        }

        $expense = Expense::create($validated);

        return response()->json([
            'message' => 'Expense recorded successfully',
            'expense' => $expense
        ], 201);
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return response()->json(['message' => 'Expense deleted']);
    }
}