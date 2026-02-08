<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\User;
use App\Models\Earning;
use App\Models\Expense;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        // Get real revenue from your Jobs table price column
        $totalRevenue = (float) Job::sum('price');
        
        $laborCosts = (float) Earning::sum('amount');
        $materialCosts = (float) Expense::sum('amount');
        
        $totalExpenses = $laborCosts + $materialCosts;
        $netProfit = $totalRevenue - $totalExpenses;

        return response()->json([
            'total_revenue' => number_format($totalRevenue, 2),
            'labor_expenses' => number_format($laborCosts, 2),
            'material_expenses' => number_format($materialCosts, 2),
            'total_expenses' => number_format($totalExpenses, 2),
            'net_profit' => number_format($netProfit, 2),
            'profit_margin' => $totalRevenue > 0 ? round(($netProfit / $totalRevenue) * 100, 2) . '%' : '0%'
        ]);
    }
   public function employeePerformance($userId)
    {
        $user = User::findOrFail($userId);

        // Get all jobs this user worked on
        $recentJobs = $user->jobs()
            ->with(['client']) 
            ->latest()
            ->get();

        // FIX: Changed 'total_price' to 'price' to fix the SQL error
        $revenueSum = $user->jobs()->where('status', 'completed')->sum('price');

        return response()->json([
            'completed_jobs_count' => $user->jobs()->where('status', 'completed')->count(),
            'total_revenue' => number_format($revenueSum ?? 0, 2),
            'recent_activity' => $recentJobs,
            'joined_date' => $user->created_at->format('M d, Y'),
        ]);
    }
}