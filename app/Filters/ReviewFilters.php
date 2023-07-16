<?php

namespace App\Filters;
use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class ReviewFilters extends ApiFilter{

    protected $safeParams = [
        'id'=>['eq'],
        'userId'=>['eq'],
        'rideId'=>['eq'],
        'isPrivate'=>['eq'],
        'note'=>['eq'],
        'content'=>['eq'],
    ];

    protected $columnMap = [
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