<?php

namespace App\Tags;

use App\Support\ProductOptions;
use Illuminate\Support\Collection;
use Statamic\Facades\Entry;
use Statamic\Tags\Tags;

class ClareProducts extends Tags
{
    public function index()
    {
        return $this->pageItems()->all();
    }

    public function count()
    {
        return $this->filtered()->count();
    }

    public function paginate()
    {
        $perPage = $this->perPage();
        $all = $this->filtered();
        $total = $all->count();
        $pages = max(1, (int) ceil($total / $perPage));
        $current = $this->currentPage($pages);
        $from = $total === 0 ? 0 : (($current - 1) * $perPage) + 1;
        $to = min($total, $current * $perPage);

        $links = [];
        for ($i = 1; $i <= $pages; $i++) {
            $links[] = [
                'page' => $i,
                'url' => $this->pageUrl($i),
                'current' => $i === $current,
            ];
        }

        return [[
            'total_items' => $total,
            'total_pages' => $pages,
            'current_page' => $current,
            'per_page' => $perPage,
            'shown_from' => $from,
            'shown_to' => $to,
            'prev_page' => $current > 1 ? $this->pageUrl($current - 1) : null,
            'next_page' => $current < $pages ? $this->pageUrl($current + 1) : null,
            'pages' => $links,
        ]];
    }

    public function url()
    {
        $key = (string) $this->params->get('key');
        $value = (string) $this->params->get('value');
        $query = request()->query();

        if (in_array($key, ['color', 'size', 'price'], true)) {
            $currentValue = (string) ($query[$key] ?? '');
            if ($key === 'price') {
                $currentValue = $this->normalizePrice($currentValue);
                $value = $this->normalizePrice($value);
            }
            if ($currentValue === $value) {
                unset($query[$key]);
            } else {
                $query[$key] = $value;
            }
        } else {
            $current = $this->list($key);
            if (in_array($value, $current, true)) {
                $current = array_values(array_diff($current, [$value]));
            } else {
                $current[] = $value;
            }
            if ($current === []) {
                unset($query[$key]);
            } else {
                $query[$key] = count($current) === 1 ? $current[0] : $current;
            }
        }

        unset($query['page']);
        $path = '/'.ltrim(request()->path(), '/');
        $qs = http_build_query($query);

        return $qs === '' ? $path : $path.'?'.$qs;
    }

    private function filtered()
    {
        $q = strtolower(trim((string) request('q', '')));
        $categories = $this->list('category');
        $colors = $this->list('color');
        $sizes = $this->list('size');
        $price = $this->normalizePrice((string) request('price', ''));
        $orderby = (string) request('orderby', 'date');

        $entries = Entry::query()->where('collection', 'products')->get();

        $entries = $entries->filter(function ($entry) use ($q, $categories, $colors, $sizes, $price) {
            $title = strtolower((string) $entry->get('title', ''));
            $excerpt = strtolower((string) $entry->get('excerpt', ''));
            $content = strtolower((string) $entry->get('content', ''));

            if ($q !== '' && ! str_contains($title, $q) && ! str_contains($excerpt, $q) && ! str_contains($content, $q)) {
                return false;
            }

            $terms = array_map('strval', (array) $entry->get('product_category', []));
            if ($categories !== [] && count(array_intersect($terms, $categories)) === 0) {
                return false;
            }

            $entryColors = ProductOptions::values($entry, 'color') ?: array_map('strval', (array) $entry->get('colors', []));
            if ($colors !== [] && count(array_intersect($entryColors, $colors)) === 0) {
                return false;
            }

            $entrySizes = array_map('strtoupper', ProductOptions::values($entry, 'size') ?: array_map('strval', (array) $entry->get('sizes', [])));
            $wantSizes = array_map('strtoupper', $sizes);
            if ($wantSizes !== [] && count(array_intersect($entrySizes, $wantSizes)) === 0) {
                return false;
            }

            $amount = (float) $entry->get('price', 0);
            if ($price !== '' && $price !== 'all' && ! $this->inPriceRange($amount, $price)) {
                return false;
            }

            return true;
        });

        $entries = $entries->values();

        return match ($orderby) {
            'price' => $entries->sortBy(fn ($entry) => (float) $entry->get('price', 0))->values(),
            'price-desc' => $entries->sortByDesc(fn ($entry) => (float) $entry->get('price', 0))->values(),
            'title' => $entries->sortBy(fn ($entry) => (string) $entry->get('title'))->values(),
            default => $entries,
        };
    }

    private function pageItems(): Collection
    {
        $perPage = $this->perPage();
        $all = $this->filtered();
        $pages = max(1, (int) ceil(max(1, $all->count()) / $perPage));

        return $all->forPage($this->currentPage($pages), $perPage)->values();
    }

    private function perPage(): int
    {
        $per = (int) $this->params->get('paginate', 12);

        return $per > 0 ? $per : 12;
    }

    private function currentPage(int $max): int
    {
        $page = max(1, (int) request('page', 1));

        return min($page, $max);
    }

    private function pageUrl(int $page): string
    {
        $query = request()->query();
        if ($page <= 1) {
            unset($query['page']);
        } else {
            $query['page'] = $page;
        }

        $path = '/'.ltrim(request()->path(), '/');
        $qs = http_build_query($query);

        return $qs === '' ? $path : $path.'?'.$qs;
    }

    private function list(string $key): array
    {
        $value = request($key, []);
        if (is_string($value)) {
            $value = $value === '' ? [] : [$value];
        }

        return array_values(array_filter(array_map('strval', (array) $value)));
    }

    private function normalizePrice(string $price): string
    {
        return match ($price) {
            '0-680' => '0-40',
            '680-1360' => '40-80',
            '1360-2040' => '80-150',
            '2040-2720' => '150-500',
            '2720' => '500',
            default => $price,
        };
    }

    private function inPriceRange(float $amount, string $price): bool
    {
        return match ($price) {
            '0-40' => $amount <= 40,
            '40-80' => $amount > 40 && $amount <= 80,
            '80-150' => $amount > 80 && $amount <= 150,
            '150-500' => $amount > 150 && $amount <= 500,
            '500' => $amount > 500,
            default => true,
        };
    }
}
