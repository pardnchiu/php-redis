# Redis CLI

> Lightweight PHP Redis client supporting cache operations, automatic connection management, and complete Redis functionality.<br>
> Built on native Redis extension, providing stable and reliable cache operation experience.

[![packagist](https://img.shields.io/packagist/v/pardnchiu/redis-cli)](https://packagist.org/packages/pardnchiu/redis-cli)
[![version](https://img.shields.io/github/v/tag/pardnchiu/php-redis-cli?label=release)](https://github.com/pardnchiu/php-redis-cli/releases)
[![license](https://img.shields.io/github/license/pardnchiu/php-redis-cli)](LICENSE)<br>
[![readme](https://img.shields.io/badge/readme-EN-white)](README.md)
[![readme](https://img.shields.io/badge/readme-ZH-white)](README.zh.md)

- [Three Core Features](#three-core-features)
  - [Automatic Connection Management](#automatic-connection-management)
  - [Multi-Database Support](#multi-database-support)
  - [Stable Connection](#stable-connection)
- [Features](#features)
- [Usage](#usage)
  - [Installation](#installation)
  - [Environment Configuration](#environment-configuration)
  - [Basic Usage](#basic-usage)
- [API Reference](#api-reference)
  - [Basic Operations](#basic-operations)
  - [Hash Operations](#hash-operations)
  - [List Operations](#list-operations)
  - [Set Operations](#set-operations)
  - [Management Operations](#management-operations)
- [Error Handling](#error-handling)
- [License](#license)
- [Author](#author)

## Three Core Features

### Automatic Connection Management
Intelligent connection pool management, automatically establishes and maintains Redis connections, supports persistent connections for improved performance

### Multi-Database Support
Flexible database selection mechanism, supports Redis multi-database operations to meet different business scenario requirements

### Stable Connection
Built-in retry mechanism and error handling, ensuring reliability in unstable network environments

## Features

- **Environment Variable Configuration**: Flexible environment variable settings, supports multi-environment deployment
- **Persistent Connections**: Uses persistent connections to improve performance and reduce connection overhead
- **Automatic Retry**: Built-in retry mechanism handles network fluctuations and temporary connection failures
- **Complete Redis Operations**: Supports Redis data types including strings, hashes, lists, sets, etc.
- **Multi-Database Support**: Supports Redis multi-database operations, flexible management of different business data
- **Security Authentication**: Supports password authentication to ensure connection security
- **Stateless Design**: Automatic connection management and cleanup

## Usage

### Installation

```shell
composer require pardnchiu/redis-cli
```

### Environment Configuration

```env
REDIS_HOST=localhost      # Redis host address
REDIS_PORT=6379           # Redis port
REDIS_PASSWORD=your_pass  # Redis password (optional)
```

### Basic Usage

```php
<?php

use pardnchiu\RDB;

// Initialize client
$redis = new RDB();

// Basic string operations
$redis->set(0, "user:123", "John Doe", 3600);  // Set value with expiration
$user = $redis->get(0, "user:123");            // Get value

// Check connection status
if ($redis->isConnected()) {
    echo "Redis connection is normal";
}

// Counter operations
$redis->incr(0, "page_views");
$redis->decr(0, "stock_count");
```

## API Reference

### Basic Operations

- `get($db, $key)` - Get string value
  ```php
  $value = $redis->get(0, "user:123");
  ```

- `set($db, $key, $content, $expire = null)` - Set string value
  ```php
  $redis->set(0, "session:abc", $data, 1800);  // 30 minutes expiration
  $redis->set(1, "config:app", $config);        // Never expires
  ```

- `exists($db, $key)` - Check if key exists
  ```php
  if ($redis->exists(0, "user:123")) {
      echo "User exists";
  }
  ```

- `delete($db, $key)` - Delete key
  ```php
  $redis->delete(0, "temp:data");
  ```

- `ttl($db, $key)` - Get expiration time
  ```php
  $seconds = $redis->ttl(0, "session:abc");
  ```

- `keys($db, $pattern)` - Search key names
  ```php
  $userKeys = $redis->keys(0, "user:*");
  ```

### Hash Operations

```php
// Set hash field
$redis->hset(0, "user:123", "name", "John Doe", 3600);
$redis->hset(0, "user:123", "email", "john@example.com");

// Get hash field
$name = $redis->hget(0, "user:123", "name");

// Get all hash data
$userData = $redis->hgetall(0, "user:123");
```

### List Operations

```php
// Push to list (left/right side)
$redis->lpush(0, "tasks", "New Task", 3600);
$redis->rpush(0, "logs", "Log Message");

// Pop list elements
$task = $redis->lpop(0, "tasks");
$log = $redis->rpop(0, "logs");

// Get list length
$length = $redis->llen(0, "tasks");
```

### Set Operations

```php
// Add set members
$redis->sadd(0, "tags", "php", 3600);
$redis->sadd(0, "tags", "redis");

// Remove set member
$redis->srem(0, "tags", "old_tag");

// Get all members
$tags = $redis->smembers(0, "tags");

// Check member existence
if ($redis->sismember(0, "tags", "php")) {
    echo "Contains PHP tag";
}

// Set operations
$common = $redis->sinter(0, ["tags:user1", "tags:user2"]);  // Intersection
$all = $redis->sunion(0, ["tags:user1", "tags:user2"]);     // Union
$diff = $redis->sdiff(0, ["tags:user1", "tags:user2"]);     // Difference
```

### Management Operations

```php
// Batch operations
$values = $redis->mget(0, ["key1", "key2", "key3"]);
$redis->mset(0, ["key1" => "value1", "key2" => "value2"]);

// Numeric operations
$redis->incr(0, "counter");         // Increment by 1
$redis->decr(0, "stock");           // Decrement by 1
$redis->append(0, "log", "New content"); // Append string

// Clear database
$redis->flushdb(0);

// Get server info
$info = $redis->info();
```

## Error Handling

```php
try {
    $redis = new RDB();
    
    // Redis operations
    $result = $redis->set(0, "user:123", $userData, 3600);
    
    if ($result) {
        echo "Data saved successfully";
    } else {
        echo "Data save failed";
    }
    
} catch (\Exception $e) {
    // Connection error handling
    error_log("Redis error: " . $e->getMessage());
    
    if (strpos($e->getMessage(), "Connection refused") !== false) {
        echo "Redis server is not running";
    } elseif (strpos($e->getMessage(), "Authentication") !== false) {
        echo "Redis authentication failed, please check password";
    } else {
        echo "Redis operation exception, please try again later";
    }
}
```

### Connection Status Check

```php
$redis = new RDB();

// Check connection status
if (!$redis->isConnected()) {
    // Handle connection failure
    error_log("Redis connection failed, using fallback solution");
    
    // Can use other cache solutions or directly query database
    return $this->fallbackCache($key);
}

// Normal Redis usage
$data = $redis->get(0, $key);
```

### Performance Monitoring

```php
// Monitor connection status
$info = $redis->info();
if ($info) {
    $connectedClients = $info['connected_clients'] ?? 0;
    $usedMemory = $info['used_memory_human'] ?? '0B';
    
    error_log("Redis status - Connections: {$connectedClients}, Memory usage: {$usedMemory}");
}
```

## License

This project is licensed under the [MIT](LICENSE) License.

## Author

<img src="https://avatars.githubusercontent.com/u/25631760" align="left" width="96" height="96" style="margin-right: 0.5rem;">

<h4 style="padding-top: 0">邱敬幃 Pardn Chiu</h4>

<a href="mailto:dev@pardn.io" target="_blank">
    <img src="https://pardn.io/image/email.svg" width="48" height="48">
</a> <a href="https://linkedin.com/in/pardnchiu" target="_blank">
    <img src="https://pardn.io/image/linkedin.svg" width="48" height="48">
</a>

***

©️ 2024 [邱敬幃 Pardn Chiu](https://pardn.io)