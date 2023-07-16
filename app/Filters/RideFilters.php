<?php

namespace App\Filters;
use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class RideFilters extends ApiFilter{

    protected $safeParams = [
        'id'=>['eq','lt','lte','gt','gte'],
        'start'=>['eq'],
        'end'=>['eq'],
        'price'=>['eq','lt','lte','gt','gte'],
        'startAt'=>['eq','lt','lte','gt','gte'],
        'status'=>['eq'],
        'type'=>['eq'],
        'placesNumber'=>['eq','lt','lte','gt','gte'],
        'twoPlaces'=>['eq'],
        'acceptAuctions'=>['eq'],
        'isDetourAllowed'=>['eq'],
        'canBook'=>['eq'],
        'views'=>['eq','lt','lte','gt','gte'],
        'userId'=>['eq'],
        'createdAt'=>['eq','lt','lte','gt','gte'],
        'updatedAt'=>['eq','lt','lte','gt','gte']
    ];

    protected $columnMap = [
        'createdAt'=>'created_at',
        'updatedAt'=>'updated_at',
        'userId'=>'user_id'
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