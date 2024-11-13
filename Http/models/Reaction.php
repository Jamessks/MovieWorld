<?php

namespace Http\models;

use Core\App;
use Core\Database;
use Http\models\Movie;

class Reaction
{
    protected $db;
    public static $allowedReactives = ['movie' => Movie::class];
    protected $targetId;
    protected $targetType;
    protected $userId;

    public function __construct($targetType, $targetId, $userId)
    {
        $this->db = App::resolve(Database::class);
        $this->targetType = $targetType;
        $this->targetId = $targetId;
        $this->userId = $userId;
    }

    public function performLike()
    {
        return $this->toggleReaction(1);
    }

    public function performDislike()
    {
        return $this->toggleReaction(0);
    }

    public function toggleReaction($reactionType)
    {
        $message = '';
        try {
            // Check if the reaction already exists.
            $existingReaction = $this->db->query(
                "SELECT id, user_id, target_type, target_id, reaction 
             FROM like_dislike 
             WHERE user_id = :user_id AND target_type = :target_type AND target_id = :target_id",
                [
                    'user_id' => $this->userId,
                    'target_type' => $this->targetType,
                    'target_id' => $this->targetId
                ]
            )->find();

            if ($existingReaction) {
                if ($existingReaction['reaction'] == $reactionType || $reactionType === null) {
                    // User wants to undo their reaction.
                    $this->db->query(
                        "DELETE FROM like_dislike WHERE id = :id",
                        ['id' => $existingReaction['id']]
                    );
                    $message = 'Reaction removed';
                } else if ($existingReaction['reaction'] != $reactionType && $reactionType !== null) {
                    // Update the reaction if it is different.
                    $this->db->query(
                        "UPDATE like_dislike 
                     SET reaction = :reaction, created_at = NOW() 
                     WHERE id = :id",
                        [
                            'reaction' => $reactionType,
                            'id' => $existingReaction['id']
                        ]
                    );
                    $message = 'Reaction updated';
                }
            } else {
                // Insert a new reaction if none exists.
                $this->db->query(
                    "INSERT INTO like_dislike (user_id, target_type, target_id, reaction, created_at) 
                VALUES (:user_id, :target_type, :target_id, :reaction, NOW())",
                    [
                        'user_id' => $this->userId,
                        'target_type' => $this->targetType,
                        'target_id' => $this->targetId,
                        'reaction' => $reactionType
                    ]
                );
                $message = 'Reaction added';
            }

            if ($message !== '') {
                // Fetch updated like and dislike counts in a single query.
                $counts = $this->db->query(
                    "SELECT
                    SUM(CASE WHEN reaction = 1 THEN 1 ELSE 0 END) AS like_count,
                    SUM(CASE WHEN reaction = 0 THEN 1 ELSE 0 END) AS dislike_count
                 FROM like_dislike 
                 WHERE target_id = :target_id AND target_type = :target_type",
                    [
                        'target_id' => $this->targetId,
                        'target_type' => $this->targetType
                    ]
                )->find();

                return [
                    'likes' => $counts['like_count'] ?? "0",
                    'dislikes' => $counts['dislike_count'] ?? "0",
                    'message' => $message,
                    'error' => 0
                ];
            }
        } catch (\PDOException $e) {
            return ['error' => 1, 'message' => 'There was an error in updating your reaction.'];
        } catch (\Exception $e) {
            return ['error' => 1, 'message' => 'An unexpected error occurred. Please try again later.'];
        }
    }
}
