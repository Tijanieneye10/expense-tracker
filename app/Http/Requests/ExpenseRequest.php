<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'monthlyTarget' => 'required',
            'date' => 'required|date',
            'dailyExpenses' => 'required|array',
            'dailyExpenses.*.expenseTitle' => 'required',
            'daily_expenses.*.expenseAmount' => 'required|numeric',
        ];
    }
}
