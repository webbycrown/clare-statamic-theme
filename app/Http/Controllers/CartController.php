<?php

namespace App\Http\Controllers;

use App\Services\ClareStore;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, ClareStore $store)
    {
        $request->validate([
            'id' => 'required|string',
            'qty' => 'nullable|integer|min:1|max:20',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:40',
            'color' => 'nullable|string|max:40',
            'size' => 'nullable|string|max:10',
        ]);

        $options = (array) $request->input('options', []);
        if ($request->filled('color')) {
            $options['color'] = $request->input('color');
        }
        if ($request->filled('size')) {
            $options['size'] = $request->input('size');
        }

        if (! $store->add(
            $request->string('id')->toString(),
            (int) $request->input('qty', 1),
            $options
        )) {
            return back()->with('cart_error', 'That product could not be added.');
        }

        return redirect('/cart')->with('cart_ok', 'Added to your cart.');
    }

    public function update(Request $request, ClareStore $store)
    {
        $store->update((array) $request->input('qty', []));

        return redirect('/cart')->with('cart_ok', 'Cart updated.');
    }

    public function remove(Request $request, ClareStore $store)
    {
        $store->remove($request->string('id')->toString());

        return redirect('/cart')->with('cart_ok', 'Removed from your cart.');
    }
}
