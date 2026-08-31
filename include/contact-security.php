<?php

function contact_env(string $name): string
{
    $value = getenv($name);
    return is_string($value) ? trim($value) : '';
}

function contact_mail_enabled(): bool
{
    return !in_array(strtolower(contact_env('RISEGATE_CONTACT_MAIL_ENABLED')), ['0', 'false', 'off', 'no'], true);
}

function contact_security_directory(): string
{
    $configured = contact_env('RISEGATE_CONTACT_SECURITY_DIR');
    return $configured !== '' ? rtrim($configured, '/\\') : dirname(__DIR__, 2) . '/tmp/contact-security';
}

function contact_client_ip(): string
{
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : 'unknown';
}

function contact_security_identifier(string $ip): string
{
    $salt = contact_env('RISEGATE_CONTACT_LOG_SALT');
    if ($salt === '') {
        $salt = __DIR__;
    }

    return substr(hash_hmac('sha256', $ip, $salt), 0, 20);
}

function contact_security_log(string $outcome, string $reason, string $ip): void
{
    $directory = contact_security_directory();
    if (!is_dir($directory) && !@mkdir($directory, 0700, true) && !is_dir($directory)) {
        return;
    }

    $record = json_encode([
        'time' => date(DATE_ATOM),
        'outcome' => $outcome,
        'reason' => $reason,
        'source' => contact_security_identifier($ip),
    ], JSON_UNESCAPED_SLASHES);

    if (is_string($record)) {
        @file_put_contents($directory . '/contact-security.log', $record . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

function contact_rate_limit(string $ip, ?int $now = null): array
{
    $now = $now ?? time();
    $directory = contact_security_directory();
    if (!is_dir($directory) && !@mkdir($directory, 0700, true) && !is_dir($directory)) {
        return ['allowed' => false, 'reason' => 'rate-store-unavailable'];
    }

    $path = $directory . '/rate-limit.json';
    $handle = @fopen($path, 'c+');
    if ($handle === false || !flock($handle, LOCK_EX)) {
        if (is_resource($handle)) {
            fclose($handle);
        }
        return ['allowed' => false, 'reason' => 'rate-store-unavailable'];
    }

    $raw = stream_get_contents($handle);
    $state = is_string($raw) && $raw !== '' ? json_decode($raw, true) : [];
    if (!is_array($state)) {
        $state = [];
    }

    $windowStart = $now - 3600;
    $global = array_values(array_filter((array) ($state['global'] ?? []), fn($value) => is_int($value) && $value > $windowStart));
    $sourceKey = contact_security_identifier($ip);
    $sources = is_array($state['sources'] ?? null) ? $state['sources'] : [];
    $source = array_values(array_filter((array) ($sources[$sourceKey] ?? []), fn($value) => is_int($value) && $value > $windowStart));

    $allowed = true;
    $reason = 'allowed';
    if (count($global) >= 20) {
        $allowed = false;
        $reason = 'global-hourly-limit';
    } elseif (count($source) >= 3) {
        $allowed = false;
        $reason = 'source-hourly-limit';
    } elseif ($source !== [] && max($source) > $now - 60) {
        $allowed = false;
        $reason = 'source-cooldown';
    }

    if ($allowed) {
        $global[] = $now;
        $source[] = $now;
    }

    $sources[$sourceKey] = $source;
    foreach ($sources as $key => $timestamps) {
        $recent = array_values(array_filter((array) $timestamps, fn($value) => is_int($value) && $value > $windowStart));
        if ($recent === []) {
            unset($sources[$key]);
        } else {
            $sources[$key] = $recent;
        }
    }

    $encoded = json_encode(['global' => $global, 'sources' => $sources], JSON_UNESCAPED_SLASHES);
    $writeSucceeded = is_string($encoded) && ftruncate($handle, 0) && rewind($handle) && fwrite($handle, $encoded) !== false && fflush($handle);
    flock($handle, LOCK_UN);
    fclose($handle);

    if (!$writeSucceeded) {
        return ['allowed' => false, 'reason' => 'rate-store-unavailable'];
    }

    return ['allowed' => $allowed, 'reason' => $reason];
}

function contact_verify_recaptcha(string $token, string $ip): array
{
    $secret = contact_env('RISEGATE_RECAPTCHA_SECRET_KEY');
    if ($secret === '' || $token === '') {
        return ['success' => false, 'reason' => $secret === '' ? 'recaptcha-not-configured' : 'recaptcha-missing'];
    }

    $payload = http_build_query(['secret' => $secret, 'response' => $token, 'remoteip' => $ip]);
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($payload) . "\r\n",
            'content' => $payload,
            'timeout' => 5,
            'ignore_errors' => true,
        ],
    ]);
    $response = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    $decoded = is_string($response) ? json_decode($response, true) : null;

    if (!is_array($decoded) || ($decoded['success'] ?? false) !== true) {
        return ['success' => false, 'reason' => is_array($decoded) ? 'recaptcha-rejected' : 'recaptcha-unavailable'];
    }

    $expectedHost = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
    $verifiedHost = strtolower((string) ($decoded['hostname'] ?? ''));
    if ($expectedHost !== '' && $verifiedHost !== '' && !hash_equals(preg_replace('/:\d+$/', '', $expectedHost), $verifiedHost)) {
        return ['success' => false, 'reason' => 'recaptcha-hostname-mismatch'];
    }

    return ['success' => true, 'reason' => 'verified'];
}

function contact_text_length(string $value): int
{
    return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
}
