<?php
require 'vendor/autoload.php';
use Monolog\Logger;
if (class_exists(Logger::class)) {
    echo "Class Monolog\Logger exists\n";
    try {
        $logger = new Logger('test');
        echo "Monolog\Logger instantiated successfully\n";
    } catch (\Throwable $e) {
        echo "Error instantiating Monolog\Logger: " . $e->getMessage() . "\n";
    }
} else {
    echo "Class Monolog\Logger NOT found\n";
}
