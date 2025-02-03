# PD\Redis

> PD\Redis 是一個基於 Predis 建構的 PHP Redis 客戶端封裝，提供簡化的 Redis 操作和自動連線管理功能。

![tag](https://img.shields.io/badge/tag-PHP%20Library-bb4444)
![size](https://img.shields.io/github/size/pardnchiu/PHP-Redis/src/Redis.php)<br>
![version](https://img.shields.io/packagist/v/pardnchiu/redis)
![download](https://img.shields.io/packagist/dm/pardnchiu/redis)

## 特色功能

- 自動連線管理
- 基於環境的配置
- 持久連線支援
- 自動資料庫選擇
- 內建錯誤處理
- 連線狀態監控
- 自動清理銷毀

## 核心功能

- 簡單的資料庫選擇讀寫操作
- 自動重試連線機制
- 過期時間管理
- 連線狀態檢查
- 環境變數配置
- 持久連線支援

## 必要的環境變數

```SHELL
REDIS_HOST=localhost    # 必填：Redis 伺服器主機
REDIS_PORT=6379         # 必填：Redis 伺服器連接埠
REDIS_PASSWORD=secret   # 選填：Redis 伺服器密碼
```

## 使用方式

### 安裝

```shell
composer require pardnchiu/redis
```

```php
// 初始化 Redis 客戶端
$redis = new PD\Redis();

// 設定帶有過期時間的值
$redis->set(0, "user:123", "user_data", 3600); // 資料庫 0，1 小時後過期

// 取得值
$data = $redis->get(0, "user:123"); // 從資料庫 0 取得

// 檢查連線狀態
if ($redis->isConnected()) {
    // Redis 已連線且就緒
}
```

## 授權條款

此原始碼專案採用 [MIT](https://github.com/pardnchiu/PHP-Redis/blob/main/LICENSE) 授權。

## 作者

<img src="https://avatars.githubusercontent.com/u/25631760" align="left" width="96" height="96" style="margin-right: 0.5rem;">

#### 邱敬幃 Pardn Chiu

<a href="mailto:dev@pardn.io" target="_blank">
 <img src="https://pardn.io/image/email.svg" width="48" height="48">
</a>
<a href="https://linkedin.com/in/pardnchiu" target="_blank">
 <img src="https://pardn.io/image/linkedin.svg" width="48" height="48">
</a>

---

©️ 2024 [邱敬幃 Pardn Chiu](https://pardn.io)