<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Models\MonthlyTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    public function create(ExpenseRequest $request): \Illuminate\Http\JsonResponse
    {

            DB::transaction(function() use($request) {

                $monthlyTarget = MonthlyTarget::create([
                    'monthly_target' => $request->monthlyTarget,
                    'date' => $request->date,
                    'user_id' => auth()->id()
                ]);

                $dailyExpenses = $request->dailyExpenses;

                foreach ($dailyExpenses as $dailyExpense) {

                    Expense::create([
                        'uniqueid' => str()->random(8),
                        'expenses_title' => $dailyExpense['expenseTitle'],
                        'expenses_amount' => $dailyExpense['expenseAmount'],
                        'monthly_target' => $monthlyTarget->id,
                        'user_id' => auth()->id(),
                    ]);
                }
            });

            return response()->json([
                'status' => true,
                'message' => "Expense created successfully",
            ], 201);

    }

    public function getDailyExpenses(): \Illuminate\Http\JsonResponse
    {
        try {
            $monthlyTarget = MonthlyTarget::query()
                ->where('user_id', auth()->id())
                ->whereMonth('date', now()->month)
                ->first();

            $dailyExpenses = Expense::query()
                ->whereDate('created_at', Carbon::today()->toDateString())
                ->get();

            $totalSumOfDailyExpenses = $dailyExpenses->sum('expenses_amount');

            return response()->json([
                'status' => true,
                'message' => 'Daily Expenses fetched successfully',
                'data' => [
                    'monthlyTarget' => $monthlyTarget,
                    'dailyExpenses' => $dailyExpenses,
                    'totalSumOfDailyExpenses' => $totalSumOfDailyExpenses
                ]
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
            ]);
        }

    }
}
