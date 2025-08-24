<?php

namespace pardnchiu;

class RDB
{
  private $client;

  public function __construct()
  {
    $this->getConnection();
  }

  public function isConnected()
  {
    return $this->client !== null;
  }

  private function getConnection()
  {
    if ($this->client !== null) {
      return;
    }

    try {
      $host     = $_ENV["REDIS_HOST"]     ?? "localhost";
      $port     = (int) ($_ENV["REDIS_PORT"]     ?? 6379);
      $password = $_ENV["REDIS_PASSWORD"] ?? "";

      $this->client = new \Redis();
      
      // Use persistent connection for better performance
      if (!$this->client->pconnect($host, $port, 2.5)) {
        throw new \Exception("Failed to connect to Redis server");
      }

      if (!empty($password)) {
        if (!$this->client->auth($password)) {
          throw new \Exception("Redis authentication failed");
        }
      }

      $this->client->select(0);
    } catch (\Exception $err) {
      error_log($err->getMessage());
      $this->client = null;
    }
  }

  private function exec($db, $callback, $defaultReturn = null)
  {
    $this->getConnection();

    if ($this->client !== null) {
      $this->client->select($db);
      return $callback($this->client);
    }

    return $defaultReturn;
  }

  public function get($db, $key)
  {
    return $this->exec($db, function ($client) use ($key) {
      return $client->get($key);
    });
  }

  public function set($db, $key, $content, $expire = null)
  {
    return $this->exec($db, function ($client) use ($key, $content, $expire) {
      $result = $client->set($key, $content);
      if ($expire !== null) {
        $client->expire($key, $expire);
      }
      return $result;
    }, false);
  }

  public function delete($db, $key)
  {
    return $this->exec($db, function ($client) use ($key) {
      return $client->del($key);
    }, false);
  }

  public function exists($db, $key)
  {
    return $this->exec($db, function ($client) use ($key) {
      return $client->exists($key);
    }, false);
  }

  public function ttl($db, $key)
  {
    return $this->exec($db, function ($client) use ($key) {
      return $client->ttl($key);
    }, -1);
  }

  public function keys($db, $pattern = "*")
  {
    return $this->exec($db, function ($client) use ($pattern) {
      return $client->keys($pattern);
    }, []);
  }

  public function incr($db, $key)
  {
    return $this->exec($db, function ($client) use ($key) {
      return $client->incr($key);
    });
  }

  public function decr($db, $key)
  {
    return $this->exec($db, function ($client) use ($key) {
      return $client->decr($key);
    });
  }

  public function append($db, $key, $value)
  {
    return $this->exec($db, function ($client) use ($key, $value) {
      return $client->append($key, $value);
    });
  }

  public function mget($db, $keys)
  {
    return $this->exec($db, function ($client) use ($keys) {
      return $client->mget($keys);
    }, []);
  }

  public function mset($db, $keyValues)
  {
    return $this->exec($db, function ($client) use ($keyValues) {
      return $client->mset($keyValues);
    }, false);
  }

  // Hash Operations
  public function hget($db, $key, $field)
  {
    return $this->exec($db, function ($client) use ($key, $field) {
      return $client->hget($key, $field);
    });
  }

  public function hset($db, $key, $field, $value, $expire = null)
  {
    return $this->exec($db, function ($client) use ($key, $field, $value, $expire) {
      $result = $client->hset($key, $field, $value);
      if ($expire !== null) {
        $client->expire($key, $expire);
      }
      return $result;
    }, false);
  }

  public function hgetall($db, $key)
  {
    return $this->exec($db, function ($client) use ($key) {
      return $client->hgetall($key);
    }, []);
  }

  public function lpush($db, $key, $value, $expire = null)
  {
    return $this->exec($db, function ($client) use ($key, $value, $expire) {
      $result = $client->lpush($key, $value);
      if ($expire !== null) {
        $client->expire($key, $expire);
      }
      return $result;
    });
  }

  public function rpush($db, $key, $value, $expire = null)
  {
    return $this->exec($db, function ($client) use ($key, $value, $expire) {
      $result = $client->rpush($key, $value);
      if ($expire !== null) {
        $client->expire($key, $expire);
      }
      return $result;
    });
  }

  public function lpop($db, $key)
  {
    return $this->exec($db, function ($client) use ($key) {
      return $client->lpop($key);
    });
  }

  public function rpop($db, $key)
  {
    return $this->exec($db, function ($client) use ($key) {
      return $client->rpop($key);
    });
  }

  public function llen($db, $key)
  {
    return $this->exec($db, function ($client) use ($key) {
      return $client->llen($key);
    }, 0);
  }

  public function sadd($db, $key, $member, $expire = null)
  {
    return $this->exec($db, function ($client) use ($key, $member, $expire) {
      $result = $client->sadd($key, $member);
      if ($expire !== null) {
        $client->expire($key, $expire);
      }
      return $result;
    });
  }

  public function srem($db, $key, $member)
  {
    return $this->exec($db, function ($client) use ($key, $member) {
      return $client->srem($key, $member);
    });
  }

  public function smembers($db, $key)
  {
    return $this->exec($db, function ($client) use ($key) {
      return $client->smembers($key);
    }, []);
  }

  public function scard($db, $key)
  {
    return $this->exec($db, function ($client) use ($key) {
      return $client->scard($key);
    }, 0);
  }

  public function sinter($db, $keys)
  {
    return $this->exec($db, function ($client) use ($keys) {
      return $client->sinter($keys);
    }, []);
  }

  public function sunion($db, $keys)
  {
    return $this->exec($db, function ($client) use ($keys) {
      return $client->sunion($keys);
    }, []);
  }

  public function sdiff($db, $keys)
  {
    return $this->exec($db, function ($client) use ($keys) {
      return $client->sdiff($keys);
    }, []);
  }

  public function sismember($db, $key, $member)
  {
    return $this->exec($db, function ($client) use ($key, $member) {
      return $client->sismember($key, $member);
    }, false);
  }

  public function flushdb($db)
  {
    return $this->exec($db, function ($client) {
      return $client->flushdb();
    }, false);
  }

  public function info()
  {
    $this->getConnection();

    if ($this->client !== null) {
      return $this->client->info();
    }

    return null;
  }

  public function __destruct()
  {
    if ($this->client !== null) {
      try {
        // For native Redis extension, close() is used instead of disconnect()
        $this->client->close();
      } catch (\Exception) {
        // Connection already lost, no need to close
      }
      $this->client = null;
    }
  }
}
