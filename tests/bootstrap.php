<?php
/**
 * PHPUnit bootstrap for hypeGallery plugin tests.
 * Plugin must be installed at {elgg_root}/mod/hypeGallery/
 *
 * tests/ -> mod/hypeGallery/ -> mod/ -> elgg_root/
 */

$elggRoot = dirname(dirname(dirname(__DIR__)));

require_once $elggRoot . '/vendor/autoload.php';

// Load Elgg test classes (UnitTestCase, IntegrationTestCase, etc.)
$testClassesDir = $elggRoot . '/vendor/elgg/elgg/engine/tests/classes';
spl_autoload_register(function ($class) use ($testClassesDir) {
    $file = $testClassesDir . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Load plugin autoloader if present
$pluginRoot = dirname(__DIR__);
if (file_exists($pluginRoot . '/vendor/autoload.php')) {
    require_once $pluginRoot . '/vendor/autoload.php';
} elseif (file_exists($pluginRoot . '/autoloader.php')) {
    require_once $pluginRoot . '/autoloader.php';
}

// Manually register plugin PSR-0/PSR-4 classes in case plugin isn't active in test DB
spl_autoload_register(function ($class) use ($pluginRoot) {
    $prefix = 'hypeJunction\\Gallery\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = $pluginRoot . '/classes/hypeJunction/Gallery/' . str_replace('\\', '/', $relative) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

\Elgg\Application::loadCore();
