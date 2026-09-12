<?php

namespace App\Modifiers;

use App\Services\ClareStore;
use Statamic\Modifiers\Modifier;

class ClareMoney extends Modifier
{
    public function index($value, $params, $context)
    {
        return app(ClareStore::class)->format(is_numeric($value) ? (float) $value : 0);
    }
}
