<?php

namespace App\Support;

class Code39Barcode
{
    /**
     * Basic Code39 charset patterns.
     * n = narrow, w = wide
     */
    private const PATTERNS = [
        '0' => 'nnnwwnwnn',
        '1' => 'wnnwnnnnw',
        '2' => 'nnwwnnnnw',
        '3' => 'wnwwnnnnn',
        '4' => 'nnnwwnnnw',
        '5' => 'wnnwwnnnn',
        '6' => 'nnwwwnnnn',
        '7' => 'nnnwnnwnw',
        '8' => 'wnnwnnwnn',
        '9' => 'nnwwnnwnn',
        'A' => 'wnnnnwnnw',
        'B' => 'nnwnnwnnw',
        'C' => 'wnwnnwnnn',
        'D' => 'nnnnwwnnw',
        'E' => 'wnnnwwnnn',
        'F' => 'nnwnwwnnn',
        'G' => 'nnnnnwwnw',
        'H' => 'wnnnnwwnn',
        'I' => 'nnwnnwwnn',
        'J' => 'nnnnwwwnn',
        'K' => 'wnnnnnnww',
        'L' => 'nnwnnnnww',
        'M' => 'wnwnnnnwn',
        'N' => 'nnnnwnnww',
        'O' => 'wnnnwnnwn',
        'P' => 'nnwnwnnwn',
        'Q' => 'nnnnnnwww',
        'R' => 'wnnnnnwwn',
        'S' => 'nnwnnnwwn',
        'T' => 'nnnnwnwwn',
        'U' => 'wwnnnnnnw',
        'V' => 'nwwnnnnnw',
        'W' => 'wwwnnnnnn',
        'X' => 'nwnnwnnnw',
        'Y' => 'wwnnwnnnn',
        'Z' => 'nwwnwnnnn',
        '-' => 'nwnnnnwnw',
        '.' => 'wwnnnnwnn',
        ' ' => 'nwwnnnwnn',
        '$' => 'nwnwnwnnn',
        '/' => 'nwnwnnnwn',
        '+' => 'nwnnnwnwn',
        '%' => 'nnnwnwnwn',
        '*' => 'nwnnwnwnn',
    ];

    public static function clean(string $value): string
    {
        $upper = strtoupper(trim($value));
        $upper = preg_replace('/[^0-9A-Z\\-\\. \\$\\/\\+%]/', ' ', $upper) ?? '';
        $upper = preg_replace('/\\s+/', ' ', $upper) ?? '';

        return trim($upper);
    }

    public static function renderSvg(string $rawValue, int $height = 82): string
    {
        $value = self::clean($rawValue);
        if ($value === '') {
            $value = 'EMPTY';
        }

        $encoded = '*' . $value . '*';

        $narrow = 2;
        $wide = 5;
        $interGap = 2;
        $paddingX = 12;
        $paddingY = 10;
        $textHeight = 18;

        $x = $paddingX;
        $barHeight = $height;
        $bars = [];

        foreach (str_split($encoded) as $char) {
            $pattern = self::PATTERNS[$char] ?? self::PATTERNS['-'];

            foreach (str_split($pattern) as $index => $size) {
                $width = $size === 'w' ? $wide : $narrow;
                $isBar = $index % 2 === 0;

                if ($isBar) {
                    $bars[] = sprintf('<rect x="%d" y="%d" width="%d" height="%d" fill="#111"/>', $x, $paddingY, $width, $barHeight);
                }

                $x += $width;
            }

            $x += $interGap;
        }

        $totalWidth = $x + $paddingX;
        $totalHeight = $paddingY + $barHeight + $textHeight + 8;
        $safeText = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        return sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 %d %d"><rect width="100%%" height="100%%" fill="#fff"/>%s<text x="%d" y="%d" font-family="Arial, sans-serif" font-size="12" fill="#111" text-anchor="middle">%s</text></svg>',
            $totalWidth,
            $totalHeight,
            $totalWidth,
            $totalHeight,
            implode('', $bars),
            (int) floor($totalWidth / 2),
            $paddingY + $barHeight + 15,
            $safeText
        );
    }
}

