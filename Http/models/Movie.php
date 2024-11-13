<?php

namespace Http\models;

use Core\App;
use Core\Database;
use Core\RedisManagement\RedisManager;
use Http\models\interfaces\BasicQueryable;
use Http\models\interfaces\NonSelfReactible;
use Core\RedisManagement\jobs\CleanupReactionsJob;

class Movie implements BasicQueryable, NonSelfReactible
{
    protected $db;

    public function __construct()
    {
        $this->db = App::resolve(Database::class);
    }

    public function fetchMovieOrFail(int $id)
    {
        $results = $this->db->query('SELECT id FROM movies WHERE id = :id', [
            'id' => $id
        ])->findOrFail();

        return $results;
    }

    public function fetchMovie(int $id)
    {
        $results = $this->db->query('SELECT id, user_id, title FROM movies WHERE id = :id', [
            'id' => $id
        ])->find();

        return $results;
    }

    public function exists(int $id)
    {
        $results = $this->db->query("SELECT COUNT(id) as count FROM movies WHERE id =:id", [
            'id' => $id
        ])->find()['count'];

        return $results > 0;
    }

    public function isUserOwnerOfInstance(int $userId, int $movieId)
    {
        $results = $this->db->query("SELECT COUNT(id) AS count FROM movies WHERE user_id = :user_id AND id = :id", [
            'id' => $movieId,
            'user_id' => $userId
        ])->find()['count'];

        return $results > 0;
    }

    public function deleteMovie($id)
    {

        $redis = new RedisManager();
        $this->db->beginTransaction();

        try {
            $this->db->query('DELETE FROM movies WHERE id = :id', [
                'id' => $id
            ]);

            // We not only want to delete a movie, but to also delete the reactions of the movie review, so as to avoid having stale reactions.
            $redis->lpush('reaction_cleanup_queue', serialize(new CleanupReactionsJob($id, 'movie')));

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
