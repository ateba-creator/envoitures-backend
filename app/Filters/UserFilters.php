<?php

namespace App\Filters;
use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class UserFilters extends ApiFilter{

    protected $safeParams = [
        'fname'=>['eq'],
        'lname'=>['eq'],
        'username'=>['eq'],
        'birthDate'=>['eq'],
        'phoneNumber'=>['eq'],
        'sex'=>['eq'],
        'receivingNewsPapers'=>['eq','ne'],
        'role'=>['eq','ne'],
        'createdAt'=>['eq','lt','lte','gt','gte'],
        'updatedAt'=>['eq','lt','lte','gt','gte'],

        'rides'=>['eq','lt','lte','gt','gte'],
        'bookings'=>['eq','lt','lte','gt','gte']

    ];

    protected $columnMap = [
        'createdAt'=>'created_at',
        'updatedAt'=>'updated_at'
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