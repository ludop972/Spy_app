<?php

namespace App\Classe;

use App\Entity\Specialities;
use App\Entity\Country;

class Search
{
    public $string = '';

    public $nationalities = [];

    public $specialities = [];

    public $page = 1;
}