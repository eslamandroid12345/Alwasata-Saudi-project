<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Traits\BelongsTo\BelongsToRequest;

class PrePayment extends BaseModel
{
    use BelongsToRequest;
    protected $table="prepayments";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pay_date',
        'payStatus',
        'realCost',
        'incValue',
        'prepaymentVal',
        'prepaymentPre',
        'prepaymentCos',
        'netCustomer',
        'net_to_customer',
        'deficitCustomer',
        'visa',
        'carLo',
        'personalLo',
        'realLo',
        'credit',
        'other',
        'debt',
        'mortPre',
        'mortCost',
        'proftPre',
        'profCost',
        'addedVal',
        'adminFee',
        'agreement_cost',
        'request_profit',
        'account_profit_presntage',
        'account_status',
        'provide_first_batch',
        'customer_discount',
        'req_id',
        'isSentSalesManager',
        'isSentSalesAgent',
        'isSentMortgageManager',
        'isSentGeneralManager',
        'mortgaged_value',
        'mortgaged_percentage',
        'Real_estate_disposition_value',
        'Real_estate_disposition_percentage',
        'purchase_tax_value',
        'purchase_tax_percentage',
        'beside_value',
        'beside_percentage',
        'other_fees',
        'mortgage_debt',
        'mortgage_debt_with_tax',
        'total_taxes_mortgage',
        'first_batch_value',
        'first_batch_percentage',
        'perlo_percentage',
        'car_percentage',
        'visa_percentage',
        'first_batch_from_realValue',
    ];

    /**
     * @return string
     */
    public function belongsToRequestForeignKey(): string
    {
        return 'req_id';
    }
}
