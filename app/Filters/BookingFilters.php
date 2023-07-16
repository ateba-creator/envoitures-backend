<?php

namespace App\Filters;
use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class BookingFilters extends ApiFilter{

    protected $safeParams = [
        'id'=>['eq','lt','lte','gt','gte'],
        'suggestedPrice'=>['eq','lt','lte','gt','gte'],
        'validatedAt'=>['eq','lt','lte','gt','gte'],
        'payment'=>['eq'],
        'paidAt'=>['eq','lt','lte','gt','gte'],
        'fee'=>['eq','lt','lte','gt','gte'],
        'isValidated'=>['eq'],
        'status'=>['eq'],
        'userId'=>['eq'],
        'rideId'=>['eq'],
        'createdAt'=>['eq','lt','lte','gt','gte'],
        'updatedAt'=>['eq','lt','lte','gt','gte']
    ];

    protected $columnMap = [
        'createdAt'=>'created_at',
        'updatedAt'=>'updated_at',
        'userId'=>'user_id',
        'rideId'=>'ride_id'
    ];

    protected $operatorMap = [
        'eq'=>'=',
        'ne'=>'!=',
        'lt'=>'<',
        'lte'=>'<=',
        'gt'=>'>',
        'gte'=>'>=',
    ];
}