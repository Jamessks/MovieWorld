<?php

namespace Http\services\movies;

use Core\App;
use Core\Database;
use Http\instance\UserInstance;
use Http\Utils\Paginators\StandardPaginator;

class MoviePaginatorService
{
    protected $db;
    protected $paginator;

    public function __construct(StandardPaginator $paginator)
    {
        $this->paginator = $paginator;
        $this->db = App::resolve(Database::class);
    }

    public function getMoviesWithPagination($currentPage = 1, $perPage = 10, $extraParams = [])
    {
        // Default sort values.
        $sortBy = 'created_at';
        $order = 'DESC';

        // Get sort and order from $extraParams.
        if (isset($extraParams['sort_by']) && isset($extraParams['order'])) {
            $sortBy = $extraParams['sort_by'];
            $order = $extraParams['order'];
        }

        // Base queries
        $countQuery = "SELECT COUNT(*) as count FROM movies";
        $selectQuery = "
        SELECT movies.id, movies.title, movies.description, movies.created_at, movies.user_id, users.username,
            COUNT(CASE WHEN like_dislike.reaction = 1 THEN 1 END) AS like_count,
            COUNT(CASE WHEN like_dislike.reaction = 0 THEN 1 END) AS dislike_count
        FROM movies
        LEFT JOIN like_dislike ON like_dislike.target_id = movies.id AND like_dislike.target_type = 'movie'
        LEFT JOIN users ON users.id = movies.user_id
        GROUP BY movies.id, users.id
        ORDER BY $sortBy $order
        LIMIT {$perPage} OFFSET " . (($currentPage - 1) * $perPage);

        // Get the total count of records from the custom count query.
        $totalRecords = $this->db->query($countQuery)->find()['count'];

        // Fetch the paginated results from the custom select query.
        $results = $this->db->query($selectQuery)->get();

        // Load logged in user's reaction for each post.
        if (UserInstance::loggedIn() && $totalRecords > 0) {
            $movieIds = array_column($results, 'id');

            $reactionsQuery = "
                SELECT target_id, reaction
                FROM like_dislike
                WHERE target_id IN (" . implode(',', array_fill(0, count($movieIds), '?')) . ")
                AND target_type = 'movie'
                AND user_id = ?
            ";

            $params = array_merge($movieIds, [UserInstance::id()]);
            $reactions = $this->db->query($reactionsQuery, $params)->get();

            $userReactions = [];
            foreach ($reactions as $reaction) {
                $userReactions[$reaction['target_id']] = $reaction['reaction'];
            }

            foreach ($results as &$result) {
                $result['user_reaction'] = $userReactions[$result['id']] ?? null;
            }
            unset($result);
        }

        // Use paginator.
        return $this->paginator->getPaginatedResultsForCustomQuery($totalRecords, $results, $currentPage);
    }

    public function getMoviesPerUserWithPagination($userId, $currentPage = 1, $perPage = 10, $extraParams = [])
    {
        // Default sort values.
        $sortBy = 'created_at';
        $order = 'DESC';
        $userId = $userId;

        // Get sort and order from $extraParams.
        if (isset($extraParams['sort_by']) && isset($extraParams['order'])) {
            $sortBy = $extraParams['sort_by'];
            $order = $extraParams['order'];
        }

        // Base queries.
        $countQuery = "SELECT COUNT(*) as count FROM movies WHERE user_id = :id";
        $selectQuery = "
        SELECT movies.id, movies.title, movies.description, movies.created_at, movies.user_id, users.username,
            COUNT(CASE WHEN like_dislike.reaction = 1 THEN 1 END) AS like_count,
            COUNT(CASE WHEN like_dislike.reaction = 0 THEN 1 END) AS dislike_count
        FROM movies
        LEFT JOIN like_dislike ON like_dislike.target_id = movies.id AND like_dislike.target_type = 'movie'
        LEFT JOIN users ON users.id = movies.user_id
        WHERE movies.user_id = :id
        GROUP BY movies.id, users.id
        ORDER BY $sortBy $order
        LIMIT {$perPage} OFFSET " . (($currentPage - 1) * $perPage);

        // Get the total count of records from the custom count query.
        $totalRecords = $this->db->query($countQuery, ['id' => $userId])->find()['count'];
        // Fetch the paginated results from the custom select query.
        $results = $this->db->query($selectQuery, ['id' => $userId])->get();

        // Load logged in user's reaction for each post.
        if (UserInstance::loggedIn() && $totalRecords > 0) {
            $movieIds = array_column($results, 'id');

            $reactionsQuery = "
                SELECT target_id, reaction
                FROM like_dislike
                WHERE target_id IN (" . implode(',', array_fill(0, count($movieIds), '?')) . ")
                AND target_type = 'movie'
                AND user_id = ?
            ";

            $params = array_merge($movieIds, [UserInstance::id()]);
            $reactions = $this->db->query($reactionsQuery, $params)->get();

            $userReactions = [];
            foreach ($reactions as $reaction) {
                $userReactions[$reaction['target_id']] = $reaction['reaction'];
            }

            foreach ($results as &$result) {
                $result['user_reaction'] = $userReactions[$result['id']] ?? null;
            }
            unset($result);
        }
        // Use paginator
        return $this->paginator->getPaginatedResultsForCustomQuery($totalRecords, $results, $currentPage);
    }
}
