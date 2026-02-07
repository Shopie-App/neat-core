# 🚀 Neat-Core

**Neat-Core** is a high-performance **PHP 8.4 Micro-kernel**. It provides the essential engine—DI Container, Middleware Pipeline, and Worker-ready Request handling—needed to build ultra-fast, stateless **microservices** and web applications.

Built for the 2026 era of distributed systems, Neat-Core is a f\*\*\*ing brilliant foundation for developers who need maximum throughput with a **zero-leak** footprint.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
![PHP Version](https://img.shields.io/badge/php-%3E%3D8.4-8892bf.svg)

## ✨ The Philosophy

Modern PHP isn't just for FPM anymore. Neat-Core was built from the ground up to thrive as a **Microservice backbone** in **Worker-mode** (FrankenPHP, RoadRunner). 

While other frameworks struggle with memory bloat and state pollution in long-running processes, Neat-Core uses a **"Build-not-Reset"** architecture. We don't try to scrub dirty objects; we spawn fresh ones and recycle the references. It’s cleaner, safer, and optimized for high-concurrency environments where every byte counts and every millisecond matters.

## 📊 Performance
* **200K+ requests/sec** (Baseline with full middleware pipeline in worker mode).
* **Zero Memory Creep:** Flat memory delta over long-running worker loops—perfect for low-resource VPS environments.
* **Lean Kernel:** Zero third-party dependencies. Pure PHP 8.4 code.

## 🏗 Key Features

* **Reference Recycling:** Automatic isolation between requests. No manual `reset()` methods, no data leakage.
* **Microservice Ready:** Lightweight footprint designed for distributed architectures and containerized deployments (Docker/Kubernetes).
* **Dual-Mode Engine:** Seamlessly switches between FrankenPHP workers and traditional PHP-FPM for ultimate flexibility.
* **Fluent AppBuilder:** Simple, expressive setup for HTTP, Middleware, and Routing components.

## 🚀 Quick Start (FrankenPHP / Microservice)

```php
use Neat\App\AppBuilder;
use Neat\Contexts\HttpContext;
use Neat\Http\Request;
use Neat\Http\Response;

// Build the kernel once
$app = (new AppBuilder())
    ->useHttp()
    ->useMiddleware()
    ->build();

// The Worker Handler
$handler = static function () use ($app) {
    // 1. Spawn fresh Request/Response instances for THIS request
    $context = new HttpContext(new Request(), new Response());
    
    // 2. Run the kernel. Reference Recycling handles the cleanup via 'finally'.
    $app->run($context);
};

// Start the high-performance loop
while (frankenphp_handle_request($handler));
```

## 🏗 Architecture

Neat-Core follows a **Ref-Recycle** pattern:
1. **Spawn:** Fresh HTTP objects are created per request (physically new memory).
2. **Sync:** The DI Container "hot-swaps" its internal pointers to these new objects.
3. **Run:** Middleware and logic execute in a 100% clean environment.
4. **Wipe:** The `finally` block severs references, allowing PHP to reclaim memory instantly.

## 📄 Documentation

Full documentation, architecture deep-dives, and Greek-server optimization tips are available at:
https://github.com/your-username/neat-core/wiki

## ⚖️ License

MIT License. Built for speed.