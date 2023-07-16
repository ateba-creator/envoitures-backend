<?php

namespace App\Filters;
use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class VehicleFilters extends ApiFilter{

    protected $safeParams = [
        'id'=>['eq'],
        'userId'=>['eq'],
        'designation'=>['eq'],
        'description'=>['eq'],
        'imageName'=>['eq'],
        'isMusicAllowed'=>['eq'],
        'isAnimalAllowed'=>['eq'],
        'isBagAllowed'=>['eq'],
        'isFoodAllowed'=>['eq'],
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