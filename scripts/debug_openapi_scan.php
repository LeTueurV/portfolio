<?php
require __DIR__ . '/../vendor/autoload.php';

// Suppress swagger-php warnings so we can inspect the produced OpenAPI object
error_reporting(E_ALL & ~E_USER_WARNING & ~E_USER_NOTICE & ~E_WARNING & ~E_NOTICE);

use OpenApi\Generator as OpenApiGenerator;
use OpenApi\SourceFinder;

try {
    $scanPaths = [__DIR__ . '/../app'];
    echo "Scanning: ".implode(',', $scanPaths)."\n";

    $finder = new SourceFinder($scanPaths, [], '*.php');
    $generator = new OpenApiGenerator();
    $openapi = $generator->generate($finder);

    echo "Found paths count: ";
    if (isset($openapi->paths) && is_array($openapi->paths)) {
        echo count($openapi->paths)."\n";
    } else {
        echo "0\n";
    }

    echo "Paths keys:\n";
    if (isset($openapi->paths) && is_array($openapi->paths)) {
        foreach ($openapi->paths as $p) {
            echo (string)$p->path . "\n";
        }
    }

    echo "Full OpenAPI object:\n";
    var_export($openapi);

} catch (Throwable $e) {
    echo "Exception: ".get_class($e)." - ". $e->getMessage() ."\n";
    echo $e->getTraceAsString();
}

