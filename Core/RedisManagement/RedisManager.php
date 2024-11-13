<?php

namespace Core\RedisManagement;

use Redis;

class RedisManager
{
    private Redis $redis;

    public function __construct()
    {
        $this->redis = new Redis();
        $this->connect();
    }

    private function connect(): void
    {
        $this->redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
    }

    public function get(string $key)
    {
        return $this->redis->get($key);
    }

    public function set(string $key, $value, int $expiration = 0): bool
    {
        if ($expiration > 0) {
            return $this->redis->setex($key, $expiration, $value);
        }
        return $this->redis->set($key, $value);
    }

    public function delete(string $key): bool
    {
        return $this->redis->del($key) > 0;
    }

    public function exists(string $key): bool
    {
        return $this->redis->exists($key) > 0;
    }

    public function setUserSession($userId, $sessionId)
    {
        if ($this->redis->exists(RedisKeys::userSession($userId))) {
            return false;
        }

        return $this->redis->setex(RedisKeys::userSession($userId), 3600, $sessionId);
    }

    public function getUserSession($userId)
    {
        return $this->redis->get(RedisKeys::userSession($userId));
    }

    public function removeUserSession($userId): bool
    {
        $sessionKey = RedisKeys::userSession($userId);
        return $this->delete($sessionKey);
    }

    public function rateLimit($userId, $actionType, $limit = 100, $window = 60): bool
    {
        $key = RedisKeys::rateLimitKey($userId, $actionType);

        $currentCount = $this->redis->incr($key);

        if ($currentCount === 1) {
            $this->redis->expire($key, $window);
        }

        if ($currentCount > $limit) {
            return false;
        }

        return true;
    }

    public function refreshSessionTTL($userId, $ttl = 3600)
    {
        $key = RedisKeys::userSession($userId);

        if ($this->exists($key)) {
            // Reset the TTL for the session key
            $this->redis->expire($key, $ttl);
            return true;
        }

        return false;
    }

    public function flushAll(): bool
    {
        return $this->redis->flushAll();
    }

    public function close(): void
    {
        $this->redis->close();
    }

    public function keys(string $pattern): array
    {
        return $this->redis->keys($pattern);
    }

    public function deleteAllByPattern(string $pattern): int
    {
        $keys = $this->keys($pattern);
        $deletedCount = 0;

        foreach ($keys as $key) {
            $deletedCount += $this->delete($key);
        }

        return $deletedCount;
    }

    public function lpush(string $key, string $value): bool
    {
        return $this->redis->lPush($key, $value);
    }

    public function rpop(string $key)
    {
        return $this->redis->rpop($key);
    }
}
