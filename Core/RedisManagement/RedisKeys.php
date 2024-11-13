<?php

namespace Core\RedisManagement;

class RedisKeys
{
    public static function movieThumbnailKey($id)
    {
        return "movie:{$id}:thumbnail";
    }

    public static function movieDataKey($id)
    {
        return "movie:{$id}:data";
    }

    // Generate a rate-limiting key based on user ID and action type
    public static function rateLimitKey($userId, $actionType)
    {
        return "rate_limit:{$userId}:{$actionType}";
    }

    public static function userSession($userId)
    {
        return "user_session:{$userId}";
    }
}
