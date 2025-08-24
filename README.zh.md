# Redis CLI

> 輕量級 PHP Redis 客戶端，支援快取操作、自動連接管理和完整的 Redis 功能。<br>
> 基於原生 Redis 擴展建構，提供穩定可靠的快取操作體驗。

[![packagist](https://img.shields.io/packagist/v/pardnchiu/redis-cli)](https://packagist.org/packages/pardnchiu/redis-cli)
[![version](https://img.shields.io/github/v/tag/pardnchiu/php-redis-cli?label=release)](https://github.com/pardnchiu/php-redis-cli/releases)
[![license](https://img.shields.io/github/license/pardnchiu/php-redis-cli)](LICENSE)<br>
[![readme](https://img.shields.io/badge/readme-EN-white)](README.md)
[![readme](https://img.shields.io/badge/readme-ZH-white)](README.zh.md)

- [三大核心特色](#三大核心特色)
  - [自動連接管理](#自動連接管理)
  - [多資料庫支援](#多資料庫支援)
  - [穩定連接](#穩定連接)
- [功能特性](#功能特性)
- [使用方法](#使用方法)
  - [安裝](#安裝)
  - [環境變數設定](#環境變數設定)
  - [基本使用](#基本使用)
- [API 參考](#api-參考)
  - [基本操作](#基本操作)
  - [哈希操作](#哈希操作)
  - [列表操作](#列表操作)
  - [集合操作](#集合操作)
  - [管理操作](#管理操作)
- [錯誤處理](#錯誤處理)
- [授權協議](#授權協議)
- [作者](#作者)

## 三大核心特色

### 自動連接管理
智能連接池管理，自動建立和維護 Redis 連接，支援持久連接提升性能

### 多資料庫支援
靈活的資料庫選擇機制，支援 Redis 多資料庫操作，滿足不同業務場景需求

### 穩定連接
內建重試機制和錯誤處理，確保在網路不穩定環境下的可靠性

## 功能特性

- **環境變數配置**: 靈活的環境變數設定，支援多環境部署
- **持久連接**: 使用持久連接提升性能，減少連接開銷
- **自動重試**: 內建重試機制處理網路抖動和暫時性連接失敗
- **完整 Redis 操作**: 支援字符串、哈希、列表、集合等 Redis 資料類型
- **多資料庫支援**: 支援 Redis 多資料庫操作，靈活管理不同業務資料
- **安全認證**: 支援密碼認證，確保連接安全
- **無狀態設計**: 自動連接管理和清理

## 使用方法

### 安裝

```shell
composer require pardnchiu/redis-cli
```

### 環境變數設定

```env
REDIS_HOST=localhost      # Redis 主機地址
REDIS_PORT=6379           # Redis 連接埠
REDIS_PASSWORD=your_pass  # Redis 密碼（選填）
```

### 基本使用

```php
<?php

use pardnchiu\RDB;

// 初始化客戶端
$redis = new RDB();

// 基本字符串操作
$redis->set(0, "user:123", "張三", 3600);  // 設定值並過期時間
$user = $redis->get(0, "user:123");        // 獲取值

// 檢查連接狀態
if ($redis->isConnected()) {
    echo "Redis 連接正常";
}

// 計數操作
$redis->incr(0, "page_views");
$redis->decr(0, "stock_count");
```

## API 參考

### 基本操作

- `get($db, $key)` - 獲取字符串值
  ```php
  $value = $redis->get(0, "user:123");
  ```

- `set($db, $key, $content, $expire = null)` - 設定字符串值
  ```php
  $redis->set(0, "session:abc", $data, 1800);  // 30分鐘過期
  $redis->set(1, "config:app", $config);        // 永不過期
  ```

- `exists($db, $key)` - 檢查鍵是否存在
  ```php
  if ($redis->exists(0, "user:123")) {
      echo "用戶存在";
  }
  ```

- `delete($db, $key)` - 刪除鍵
  ```php
  $redis->delete(0, "temp:data");
  ```

- `ttl($db, $key)` - 獲取過期時間
  ```php
  $seconds = $redis->ttl(0, "session:abc");
  ```

- `keys($db, $pattern)` - 搜尋鍵名
  ```php
  $userKeys = $redis->keys(0, "user:*");
  ```

### 哈希操作

```php
// 設定哈希欄位
$redis->hset(0, "user:123", "name", "張三", 3600);
$redis->hset(0, "user:123", "email", "zhang@example.com");

// 獲取哈希欄位
$name = $redis->hget(0, "user:123", "name");

// 獲取所有哈希資料
$userData = $redis->hgetall(0, "user:123");
```

### 列表操作

```php
// 推入列表（左側/右側）
$redis->lpush(0, "tasks", "新任務", 3600);
$redis->rpush(0, "logs", "日誌訊息");

// 彈出列表元素
$task = $redis->lpop(0, "tasks");
$log = $redis->rpop(0, "logs");

// 獲取列表長度
$length = $redis->llen(0, "tasks");
```

### 集合操作

```php
// 添加集合成員
$redis->sadd(0, "tags", "php", 3600);
$redis->sadd(0, "tags", "redis");

// 移除集合成員
$redis->srem(0, "tags", "old_tag");

// 獲取所有成員
$tags = $redis->smembers(0, "tags");

// 檢查成員存在
if ($redis->sismember(0, "tags", "php")) {
    echo "包含 PHP 標籤";
}

// 集合運算
$common = $redis->sinter(0, ["tags:user1", "tags:user2"]);  // 交集
$all = $redis->sunion(0, ["tags:user1", "tags:user2"]);     // 聯集
$diff = $redis->sdiff(0, ["tags:user1", "tags:user2"]);     // 差集
```

### 管理操作

```php
// 批量操作
$values = $redis->mget(0, ["key1", "key2", "key3"]);
$redis->mset(0, ["key1" => "value1", "key2" => "value2"]);

// 數值操作
$redis->incr(0, "counter");         // 增加 1
$redis->decr(0, "stock");           // 減少 1
$redis->append(0, "log", "新內容"); // 附加字符串

// 清空資料庫
$redis->flushdb(0);

// 獲取伺服器資訊
$info = $redis->info();
```

## 錯誤處理

```php
try {
    $redis = new RDB();
    
    // Redis 操作
    $result = $redis->set(0, "user:123", $userData, 3600);
    
    if ($result) {
        echo "資料保存成功";
    } else {
        echo "資料保存失敗";
    }
    
} catch (\Exception $e) {
    // 連接錯誤處理
    error_log("Redis 錯誤: " . $e->getMessage());
    
    if (strpos($e->getMessage(), "Connection refused") !== false) {
        echo "Redis 伺服器未啟動";
    } elseif (strpos($e->getMessage(), "Authentication") !== false) {
        echo "Redis 認證失敗，請檢查密碼";
    } else {
        echo "Redis 操作異常，請稍後再試";
    }
}
```

### 連接狀態檢查

```php
$redis = new RDB();

// 檢查連接狀態
if (!$redis->isConnected()) {
    // 處理連接失敗情況
    error_log("Redis 連接失敗，使用備用方案");
    
    // 可以使用其他快取方案或直接查詢資料庫
    return $this->fallbackCache($key);
}

// 正常使用 Redis
$data = $redis->get(0, $key);
```

### 性能監控

```php
// 監控連接狀態
$info = $redis->info();
if ($info) {
    $connectedClients = $info['connected_clients'] ?? 0;
    $usedMemory = $info['used_memory_human'] ?? '0B';
    
    error_log("Redis 狀態 - 連接數: {$connectedClients}, 記憶體使用: {$usedMemory}");
}
```

## 授權協議

本原始碼專案採用 [MIT](LICENSE) 授權。

## 作者

<img src="https://avatars.githubusercontent.com/u/25631760" align="left" width="96" height="96" style="margin-right: 0.5rem;">

<h4 style="padding-top: 0">邱敬幃 Pardn Chiu</h4>

<a href="mailto:dev@pardn.io" target="_blank">
    <img src="https://pardn.io/image/email.svg" width="48" height="48">
</a> <a href="https://linkedin.com/in/pardnchiu" target="_blank">
    <img src="https://pardn.io/image/linkedin.svg" width="48" height="48">
</a>

***

©️ 2024 [邱敬幃 Pardn Chiu](https://pardn.io)