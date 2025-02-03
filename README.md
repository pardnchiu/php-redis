# PD\Redis

> PD\Redis is a PHP Redis client wrapper built on Predis, offering simplified Redis operations with automatic connection management.

![tag](https://img.shields.io/badge/tag-PHP%20Library-bb4444) 
![size](https://img.shields.io/github/size/pardnchiu/PHP-Redis/src/Redis.php)<br>
![version](https://img.shields.io/packagist/v/pardnchiu/redis)
![download](https://img.shields.io/packagist/dm/pardnchiu/redis)

## Features

- Automatic connection management
- Environment-based configuration
- Persistent connection support
- Automatic database selection
- Built-in error handling
- Connection state monitoring
- Automatic cleanup on destruction

## Key Capabilities

- Simple get/set operations with database selection
- Automatic connection retry mechanism
- Expiration time management
- Connection status checking
- Environment variables configuration
- Persistent connection support

## Required Environment Variables

```SHELL
REDIS_HOST=localhost    # Required: Redis server host
REDIS_PORT=6379         # Required: Redis server port
REDIS_PASSWORD=secret   # Optional: Redis server password
```

## How to Use

### Install

```SHELL
composer require pardnchiu/redis
```

```PHP
// Initialize Redis client
$redis = new PD\Redis();

// Set value with expiration
$redis->set(0, "user:123", "user_data", 3600); // db 0, expires in 1 hour

// Get value
$data = $redis->get(0, "user:123"); // from db 0

// Check connection status
if ($redis->isConnected()) {
    // Redis is connected and ready
}
```

## License

This source code project is licensed under the [MIT](https://github.com/pardnchiu/PHP-Redis/blob/main/LICENSE) license.

## Creator

<img src="https://avatars.githubusercontent.com/u/25631760" align="left" width="96" height="96" style="margin-right: 0.5rem;">

<h4 style="padding-top: 0">邱敬幃 Pardn Chiu</h4>

<a href="mailto:dev@pardn.io" target="_blank">
    <img src="https://pardn.io/image/email.svg" width="48" height="48">
</a> <a href="https://linkedin.com/in/pardnchiu" target="_blank">
    <img src="https://pardn.io/image/linkedin.svg" width="48" height="48">
</a>

***

©️ 2024 [邱敬幃 Pardn Chiu](https://pardn.io)