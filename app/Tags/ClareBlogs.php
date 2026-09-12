<?php

namespace App\Tags;

use Illuminate\Support\Collection;
use Statamic\Facades\Entry;
use Statamic\Tags\Tags;

class ClareBlogs extends Tags
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
            'prev_page' => $current > 1 ? $this->pageUrl($current - 1) : null,
            'next_page' => $current < $pages ? $this->pageUrl($current + 1) : null,
            'pages' => $links,
        ]];
    }

    private function pageItems(): Collection
    {
        $perPage = $this->perPage();
        $all = $this->filtered();
        $pages = max(1, (int) ceil(max(1, $all->count()) / $perPage));

        return $all->forPage($this->currentPage($pages), $perPage)->values();
    }

    private function filtered(): Collection
    {
        $q = strtolower(trim((string) request('q', '')));
        $entries = Entry::query()
            ->where('collection', 'blogs')
            ->orderBy('date', 'desc')
            ->get();

        if ($q === '') {
            return $entries->values();
        }

        return $entries->filter(function ($entry) use ($q) {
            $haystack = strtolower(implode(' ', [
                (string) $entry->get('title', ''),
                (string) $entry->get('excerpt', ''),
                (string) $entry->get('content', ''),
            ]));

            return str_contains($haystack, $q);
        })->values();
    }

    private function perPage(): int
    {
        $per = (int) $this->params->get('paginate', 6);

        return $per > 0 ? $per : 6;
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
}
