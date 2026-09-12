<?php

namespace App\Support;

use Illuminate\Support\Str;
use Statamic\Contracts\Entries\Entry;

class ProductOptions
{
    public const LABELS = [
        'color' => 'Color',
        'size' => 'Size',
        'material' => 'Material',
        'length' => 'Length',
        'metal' => 'Metal',
        'engraving' => 'Engraving',
    ];

    public static function groups(?Entry $entry): array
    {
        if (! $entry) {
            return [];
        }

        $groups = self::fromReplicator($entry);
        if ($groups === []) {
            $groups = self::fromLegacy($entry);
        }

        return array_values(array_filter($groups, fn ($group) => $group['values'] !== []));
    }

    public static function values(?Entry $entry, string $handle): array
    {
        foreach (self::groups($entry) as $group) {
            if ($group['handle'] === $handle) {
                return array_column($group['values'], 'value');
            }
        }

        return [];
    }

    public static function pick(array $groups, array $want): array
    {
        $chosen = [];

        foreach ($groups as $group) {
            $handle = $group['handle'];
            $allowed = array_column($group['values'], 'value');
            if ($allowed === []) {
                continue;
            }

            $raw = $want[$handle] ?? '';
            $match = self::matchValue($allowed, (string) $raw, $handle === 'size');

            $chosen[] = [
                'handle' => $handle,
                'label' => $group['label'],
                'style' => $group['style'],
                'value' => $match,
            ];
        }

        return $chosen;
    }

    public static function lineId(string $id, array $chosen): string
    {
        $parts = [$id];
        foreach ($chosen as $row) {
            $value = strtolower(trim((string) ($row['value'] ?? '')));
            $parts[] = $row['handle'].':'.($value !== '' ? $value : 'na');
        }

        return implode('__', $parts);
    }

    private static function fromReplicator(Entry $entry): array
    {
        $rows = (array) $entry->get('product_options', []);
        $groups = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $kind = (string) ($row['handle'] ?? 'custom');
            $label = trim((string) ($row['label'] ?? ''));
            $handle = $kind === 'custom' || $kind === ''
                ? Str::slug($label !== '' ? $label : 'option')
                : $kind;
            if ($handle === '') {
                continue;
            }

            if ($label === '') {
                $label = self::LABELS[$handle] ?? Str::headline($handle);
            }

            $style = ($row['style'] ?? '') === 'swatch' || $handle === 'color' ? 'swatch' : 'box';
            $values = self::normalizeValues($row['values'] ?? []);
            if ($values === []) {
                continue;
            }

            $groups[] = self::formatGroup($handle, $label, $style, $values);
        }

        return $groups;
    }

    private static function fromLegacy(Entry $entry): array
    {
        $groups = [];
        $colors = self::normalizeValues($entry->get('colors', []));
        $sizes = self::normalizeValues($entry->get('sizes', []));

        if ($colors !== []) {
            $groups[] = self::formatGroup('color', 'Color', 'swatch', $colors);
        }
        if ($sizes !== []) {
            $groups[] = self::formatGroup('size', 'Size', 'box', $sizes);
        }

        return $groups;
    }

    private static function formatGroup(string $handle, string $label, string $style, array $values): array
    {
        $items = [];
        $last = count($values) - 1;
        foreach ($values as $i => $value) {
            $items[] = [
                'handle' => $handle,
                'label' => $label,
                'style' => $style,
                'value' => $value,
                'first' => $i === 0,
                'last' => $i === $last,
            ];
        }

        return [
            'handle' => $handle,
            'label' => $label,
            'style' => $style,
            'is_swatch' => $style === 'swatch',
            'values' => $items,
        ];
    }

    private static function normalizeValues(mixed $values): array
    {
        $out = [];
        foreach ((array) $values as $value) {
            $value = trim((string) $value);
            if ($value !== '') {
                $out[] = $value;
            }
        }

        return array_values(array_unique($out));
    }

    private static function matchValue(array $allowed, string $want, bool $upper): string
    {
        if ($allowed === []) {
            return '';
        }

        $compare = $upper
            ? array_map('strtoupper', $allowed)
            : array_map('strtolower', $allowed);
        $choice = $want === '' ? '' : ($upper ? strtoupper($want) : strtolower($want));

        if ($choice !== '' && in_array($choice, $compare, true)) {
            return $allowed[(int) array_search($choice, $compare, true)];
        }

        return $allowed[0];
    }
}
