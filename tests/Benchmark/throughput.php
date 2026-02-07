<?php

/**
 * Benchmark Script for Neat API Core
 * Simulates a persistent worker loop (FrankenPHP/RoadRunner)
 * 
 * Usage: php tests/benchmark/throughput.php
 */

require __DIR__ . '/../../vendor/autoload.php';

use Neat\App\AppBuilder;
use Neat\Contexts\HttpContext;
use Neat\Http\Request;
use Neat\Contracts\Http\ActionResult\ActionResultInterface;
use Neat\Contracts\Http\ResponseInterface;

// 1. Setup the Application (Bootstrap once)
echo "Bootstrapping App...\n";
$builder = new AppBuilder();
$builder->useHttp();
$builder->useMiddleware(); // Adds SecurityHeaders, PoweredBy, etc.
$app = $builder->build();

// 2. Configuration
$iterations = 10000;

// 3. Create a "Quiet" Response class to avoid printing to CLI and crashing on missing ActionResult
//    This ensures we benchmark the Framework overhead, not I/O.
$quietResponse = new class implements ResponseInterface {
    public function setActionResult(ActionResultInterface $actionResult): void {}
    public function withHeader(string $key, string $value, bool $replace = true): void {}
    public function output(): void
    {
        // No-op: Don't echo, don't check for ActionResult
    }
};

// 4. Run Benchmark
echo "Starting benchmark for $iterations iterations...\n";
$startTime = microtime(true);
$startMemory = memory_get_usage();

for ($i = 0; $i < $iterations; $i++) {
    // A. Simulate fresh request state (reading globals)
    // In a real worker, these would be new instances reading $_SERVER, etc.
    $request = new Request();
    
    // B. Create Context
    // We use the quiet response to suppress output
    // Assuming HttpContext constructor is (RequestInterface, ResponseInterface)
    // If HttpContext is not directly instantiable in your setup, you might need a helper here.
    // Based on standard patterns:
    $context = new HttpContext($request, $quietResponse);

    // C. Run the App (Hot Swap & Execute)
    $app->run($context);
}

$endTime = microtime(true);
$endMemory = memory_get_usage();
$peakMemory = memory_get_peak_usage();

// 5. Results
$duration = $endTime - $startTime;
$rps = $iterations / $duration;
$memoryConsumed = ($endMemory - $startMemory) / 1024 / 1024;
$memoryPeak = $peakMemory / 1024 / 1024;

echo str_repeat('-', 30) . "\n";
echo "Results:\n";
echo str_repeat('-', 30) . "\n";
echo sprintf("Total Time:    %.4f seconds\n", $duration);
echo sprintf("Throughput:    %.2f req/sec\n", $rps);
echo sprintf("Memory Change: %.2f MB\n", $memoryConsumed);
echo sprintf("Memory Peak:   %.2f MB\n", $memoryPeak);
echo str_repeat('-', 30) . "\n";

// 6. Sanity Check
echo "Benchmark complete.\n";