<?php

namespace App\Modifiers;

use Statamic\Modifiers\Modifier;

class ClareColor extends Modifier
{
    public function index($value, $params, $context)
    {
        return match (strtolower(trim((string) $value))) {
            'black' => '#000000',
            'brown' => '#591E1E',
            'cream' => '#F5E5C5',
            'red' => '#AC1D1D',
            'navy' => '#4376A5',
            'green' => '#30C651',
            'pink' => '#FEA0A5',
            default => '#CCCCCC',
        };
    }
}
