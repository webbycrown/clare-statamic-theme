<?php

namespace App\Services;

use App\Support\ProductOptions;
use Illuminate\Support\Collection;
use Statamic\Facades\Entry;

class ClareStore
{
    public const CURRENCIES = [
        'USD' => ['label' => 'USD', 'symbol' => '$', 'rate' => 1],
        'INR' => ['label' => 'INR', 'symbol' => '₹', 'rate' => 83],
        'GBP' => ['label' => 'GBP', 'symbol' => '£', 'rate' => 0.79],
    ];

    public const LOCALES = [
        'en' => 'English',
        'es' => 'Spanish',
        'de' => 'German',
    ];

    public function currency(): string
    {
        $code = strtoupper((string) session('clare_currency', 'USD'));

        return isset(self::CURRENCIES[$code]) ? $code : 'USD';
    }

    public function locale(): string
    {
        $code = strtolower((string) session('clare_locale', 'en'));

        return isset(self::LOCALES[$code]) ? $code : 'en';
    }

    public function format(?float $usd): string
    {
        $usd = (float) $usd;
        $currency = self::CURRENCIES[$this->currency()];
        $amount = round($usd * $currency['rate'], 2);

        return $currency['symbol'].number_format($amount, 2);
    }

    public function items(): array
    {
        return array_values((array) session('clare_cart', []));
    }

    public function count(): int
    {
        return collect($this->items())->sum(fn ($item) => (int) ($item['qty'] ?? 0));
    }

    public function subtotal(): float
    {
        return round(collect($this->items())->sum(fn ($item) => (float) ($item['total'] ?? 0)), 2);
    }

    public function add(string $id, int $qty = 1, array $options = []): bool
    {
        $entry = Entry::find($id);
        if (! $entry || $entry->collectionHandle() !== 'products') {
            return false;
        }

        $qty = max(1, min(20, $qty));
        $price = (float) $entry->get('price', 0);
        $imageUrl = $this->imageUrl($entry);
        $chosen = ProductOptions::pick(ProductOptions::groups($entry), $options);
        $lineId = ProductOptions::lineId($entry->id(), $chosen);
        $byHandle = collect($chosen)->keyBy('handle');

        $items = collect(session('clare_cart', []))->keyBy(fn ($item) => $item['line_id'] ?? $item['id']);
        $existing = $items->get($lineId);
        $nextQty = $existing ? min(20, (int) $existing['qty'] + $qty) : $qty;

        $items[$lineId] = [
            'id' => $entry->id(),
            'line_id' => $lineId,
            'title' => $entry->get('title'),
            'url' => $entry->url(),
            'image' => $imageUrl,
            'color' => (string) ($byHandle->get('color')['value'] ?? ''),
            'size' => (string) ($byHandle->get('size')['value'] ?? ''),
            'options' => $chosen,
            'options_label' => collect($chosen)
                ->filter(fn ($row) => ($row['value'] ?? '') !== '')
                ->map(fn ($row) => $row['label'].': '.$row['value'])
                ->implode(' · '),
            'price' => $price,
            'qty' => $nextQty,
            'total' => round($price * $nextQty, 2),
        ];

        session(['clare_cart' => $items->values()->all()]);

        return true;
    }

    public function update(array $qtyById): void
    {
        $items = collect(session('clare_cart', []))->keyBy(fn ($item) => $item['line_id'] ?? $item['id']);
        foreach ($qtyById as $id => $qty) {
            if (! $items->has($id)) {
                continue;
            }
            $qty = max(1, min(20, (int) $qty));
            $row = $items->get($id);
            $row['qty'] = $qty;
            $row['total'] = round(((float) $row['price']) * $qty, 2);
            $items[$id] = $row;
        }
        session(['clare_cart' => $items->values()->all()]);
    }

    public function remove(string $id): void
    {
        $items = collect(session('clare_cart', []))->reject(function ($item) use ($id) {
            return ($item['line_id'] ?? '') === $id || ($item['id'] ?? '') === $id;
        });
        session(['clare_cart' => $items->values()->all()]);
    }

    private function imageUrl($entry): string
    {
        $path = (string) $entry->get('image', '');
        if ($path !== '') {
            return '/assets/'.ltrim($path, '/');
        }

        $image = $entry->augmentedValue('image');
        $resolved = is_object($image) && method_exists($image, 'value') ? $image->value() : $image;
        if (is_object($resolved) && method_exists($resolved, 'url')) {
            return $resolved->url();
        }

        return '/assets/images/products-img1.jpg';
    }

    public function share(): void
    {
        $items = Collection::make($this->items())->map(function ($item) {
            $item['price_label'] = $this->format((float) $item['price']);
            $item['total_label'] = $this->format((float) $item['total']);

            return $item;
        });

        view()->share([
            'clare_cart' => $items,
            'clare_cart_count' => $this->count(),
            'clare_cart_subtotal' => $this->format($this->subtotal()),
            'clare_currency' => $this->currency(),
            'clare_locale' => $this->locale(),
            'clare_locale_label' => self::LOCALES[$this->locale()],
        ]);
    }
}
