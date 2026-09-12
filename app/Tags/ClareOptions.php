<?php

namespace App\Tags;

use App\Support\ProductOptions;
use Statamic\Facades\Entry;
use Statamic\Tags\Tags;

class ClareOptions extends Tags
{
    public function index()
    {
        return ProductOptions::groups($this->entry());
    }

    private function entry()
    {
        $id = (string) $this->context->get('id', '');
        if ($id === '') {
            return null;
        }

        return Entry::find($id);
    }
}
