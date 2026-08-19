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

function risegate_admin_password(): string
{
    $environment_password = (string) (getenv('RISEGATE_ADMIN_PASSWORD') ?: '');
    if ($environment_password !== '') {
        return $environment_password;
    }

    $local_config_file = __DIR__ . '/../config/local.php';
    if (!is_file($local_config_file)) {
        return '';
    }

    $local_config = require $local_config_file;
    return is_array($local_config) ? (string) ($local_config['admin_password'] ?? '') : '';
}

function hero_cms_definitions(): array
{
    return [
        'home' => ['label' => 'ホーム', 'selector' => '.hero-scene--home', 'image' => 'image/scene/home-fv-mountains.png'],
        'system' => ['label' => 'システム開発', 'selector' => '.hero-scene--system', 'image' => 'image/scene/future.png'],
        'website' => ['label' => 'ホームページ制作', 'selector' => '.hero-scene--website', 'image' => 'image/scene/service-river-v6.png'],
        'ai' => ['label' => '生成AI活用支援', 'selector' => '.hero-scene--ai', 'image' => 'image/scene/ai-ripple.jpg'],
        'works' => ['label' => '実績', 'selector' => '.hero-scene--works', 'image' => 'image/scene/method-road-high-v3.png'],
        'company' => ['label' => '会社案内', 'selector' => '.hero-scene--company', 'image' => 'image/scene/company-development.jpg'],
        'contact' => ['label' => '問い合わせ', 'selector' => '.hero-scene--contact', 'image' => 'image/scene/contact-forest.jpg'],
    ];
}

function hero_cms_settings(): array
{
    $settings_file = __DIR__ . '/../data/hero-settings.php';
    if (!is_file($settings_file)) {
        return [];
    }

    $settings = require $settings_file;
    return is_array($settings) ? $settings : [];
}

function hero_cms_number(mixed $value, float $minimum = 0, float $maximum = 100): float
{
    $number = is_numeric($value) ? (float) $value : 0;
    return max($minimum, min($maximum, $number));
}

function hero_cms_css(): string
{
    $definitions = hero_cms_definitions();
    $settings = hero_cms_settings();
    $device_rules = [
        'desktop' => '',
        'tablet' => '@media (max-width: 1080px)',
        'mobile' => '@media (max-width: 820px)',
    ];
    $css = [];

    foreach ($device_rules as $device => $media) {
        $rules = [];
        foreach ($definitions as $key => $definition) {
            $device_setting = $settings[$key][$device] ?? null;
            if (!is_array($device_setting)) {
                continue;
            }

            $x = hero_cms_number($device_setting['x'] ?? 50);
            $y = hero_cms_number($device_setting['y'] ?? 50);
            $overlay = hero_cms_number($device_setting['overlay'] ?? 0) / 100;
            $rules[] = sprintf(
                '%s{--scene-photo-position:%s%% %s%%;--hero-cms-overlay:%s;}',
                $definition['selector'],
                rtrim(rtrim(number_format($x, 2, '.', ''), '0'), '.'),
                rtrim(rtrim(number_format($y, 2, '.', ''), '0'), '.'),
                rtrim(rtrim(number_format($overlay, 3, '.', ''), '0'), '.') ?: '0'
            );
        }

        if (!$rules) {
            continue;
        }

        $rule_text = implode('', $rules);
        $css[] = $media === '' ? $rule_text : $media . '{' . $rule_text . '}';
    }

    return implode('', $css);
}
