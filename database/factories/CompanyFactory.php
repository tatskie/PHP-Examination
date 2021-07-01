<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name' => 'Company Name',
        'email' => 'company@gmail.com',
        'logo' => null,
        'website' => 'www.website.com'
    ];
});
