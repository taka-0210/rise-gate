<?php

$testDirectory = sys_get_temp_dir() . '/rise-gate-contact-security-test-' . bin2hex(random_bytes(4));
putenv('RISEGATE_CONTACT_SECURITY_DIR=' . $testDirectory);
putenv('RISEGATE_CONTACT_LOG_SALT=test-only-salt');

require dirname(__DIR__) . '/include/contact-security.php';

function assert_contact_test(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

assert_contact_test(contact_verify_recaptcha('', '127.0.0.1')['success'] === false, 'CAPTCHA must fail closed without configuration.');

putenv('RISEGATE_CONTACT_MAIL_ENABLED=0');
assert_contact_test(contact_mail_enabled() === false, 'Mail emergency switch must disable delivery.');
putenv('RISEGATE_CONTACT_MAIL_ENABLED=1');
assert_contact_test(contact_mail_enabled() === true, 'Mail emergency switch must allow delivery when enabled.');

$first = contact_rate_limit('192.0.2.10', 1000);
$cooldown = contact_rate_limit('192.0.2.10', 1030);
$second = contact_rate_limit('192.0.2.10', 1061);
$third = contact_rate_limit('192.0.2.10', 1122);
$hourly = contact_rate_limit('192.0.2.10', 1183);

assert_contact_test($first['allowed'] === true, 'First submission should pass.');
assert_contact_test($cooldown['allowed'] === false && $cooldown['reason'] === 'source-cooldown', 'One-minute cooldown should block.');
assert_contact_test($second['allowed'] === true, 'Second submission after cooldown should pass.');
assert_contact_test($third['allowed'] === true, 'Third submission after cooldown should pass.');
assert_contact_test($hourly['allowed'] === false && $hourly['reason'] === 'source-hourly-limit', 'Fourth hourly submission should be blocked.');

for ($index = 0; $index < 17; $index++) {
    $globalEntry = contact_rate_limit('198.51.100.' . ($index + 1), 2000);
    assert_contact_test($globalEntry['allowed'] === true, 'Submissions below the global ceiling should pass.');
}
$globalLimit = contact_rate_limit('203.0.113.1', 2000);
assert_contact_test($globalLimit['allowed'] === false && $globalLimit['reason'] === 'global-hourly-limit', 'Global hourly ceiling should block additional mail.');

$files = is_dir($testDirectory) ? scandir($testDirectory) : [];
foreach (is_array($files) ? $files : [] as $file) {
    if ($file !== '.' && $file !== '..') {
        unlink($testDirectory . '/' . $file);
    }
}
if (is_dir($testDirectory)) {
    rmdir($testDirectory);
}

echo "contact security tests passed\n";
