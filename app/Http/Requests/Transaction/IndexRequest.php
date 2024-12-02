<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'fromDate' => $this->input('fromDate', now()->subYears(30)->format('Y-m-d')),
            'toDate' => $this->input('toDate', now()->format('Y-m-d')),
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'fromDate' => 'required|date|date_format:Y-m-d',
            'toDate' => 'required|date|date_format:Y-m-d|after_or_equal:fromDate',
            'status' => 'nullable|string|in:APPROVED,WAITING,DECLINED,ERROR',
            'operation' => 'nullable|string|in:DIRECT,REFUND,3D,3DAUTH,STORED',
            'merchantId' => 'nullable|integer',
            'acquirerId' => 'nullable|integer',
            'paymentMethod' => 'nullable|string|in:CREDITCARD,CUP,IDEAL,GIROPAY,MISTERCASH,STORED,PAYTOCARD,CEPBANK,CITADEL',
            'errorCode' => 'nullable|string|in:"Do not honor","Invalid Transaction","Invalid Card","Not sufficient funds","Incorrect PIN","Invalid country association","Currency not allowed","3-D Secure Transport Error","Transaction not permitted to cardholder"',
            'filterField' => 'nullable|string|in:"Transaction UUID","Customer Email","Reference No","Custom Data","Card PAN"',
            'filterValue' => 'nullable|string',
            'page' => 'nullable|integer|min:1',
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
            'status.in' => 'The selected status is invalid.',
            'operation.in' => 'The selected operation is invalid.',
            'merchantId.integer' => 'Merchant ID must be a valid number.',
            'acquirerId.integer' => 'Acquirer ID must be a valid number.',
            'paymentMethod.in' => 'The selected payment method is invalid.',
            'errorCode.in' => 'The selected error code is invalid.',
            'filterField.in' => 'The selected filter field is invalid.',
            'page.integer' => 'Page must be a valid number.',
            'page.min' => 'Page must be at least 1.',
        ];
    }
}
