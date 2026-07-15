<?php
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function responsive_lines(array $line_sets): string
{
    $html = '';
    foreach (['desktop', 'tablet', 'mobile'] as $device) {
        if (empty($line_sets[$device]) || !is_array($line_sets[$device])) {
            continue;
        }

        $lines = array_map(
            static fn ($line) => e((string) $line),
            $line_sets[$device]
        );

        $html .= '<span class="responsive-lines responsive-lines--' . $device . '">'
            . implode('<br>', $lines)
            . '</span>';
    }

    return $html;
}

function responsive_text(array $source, string $key): string
{
    $line_key = $key . '_lines';

    if (isset($source[$line_key]) && is_array($source[$line_key])) {
        return responsive_lines($source[$line_key]);
    }

    return e((string) ($source[$key] ?? ''));
}
