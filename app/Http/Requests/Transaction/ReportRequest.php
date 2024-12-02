<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'fromDate' => 'nullable|date|date_format:Y-m-d',
            'toDate' => 'nullable|date|date_format:Y-m-d|after_or_equal:fromDate',
            'merchant' => 'nullable|integer',
            'acquirer' => 'nullable|integer',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'fromDate.required' => 'The start date is required.',
            'fromDate.date' => 'The start date must be a valid date.',
            'fromDate.date_format' => 'The start date format must be YYYY-MM-DD.',
            'toDate.required' => 'The end date is required.',
            'toDate.date' => 'The end date must be a valid date.',
            'toDate.date_format' => 'The end date format must be YYYY-MM-DD.',
            'toDate.after_or_equal' => 'The end date must be after or equal to the start date.',
            'merchant.integer' => 'Merchant must be a valid number.',
            'acquirer.integer' => 'Acquirer must be a valid number.',
        ];
    }
}
