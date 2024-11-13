<?php

namespace Http\utils\paginators;

use Illuminate\Pagination\LengthAwarePaginator;

class StandardPaginator
{
    protected $db;
    protected $perPage;
    protected $path;

    public function __construct($path, $perPage = 10)
    {
        $this->perPage = $perPage;
        $this->path = $path;
    }

    public function getPaginatedResultsForCustomQuery($totalRecords, $results, $currentPage = 1)
    {
        $paginator = new LengthAwarePaginator(
            $results,
            $totalRecords,
            $this->perPage,
            $currentPage,
            ['path' => $this->path]
        );

        if (
            $currentPage > $paginator->lastPage() && $paginator->total() > 0
        ) {
            abort(404);
        }

        // Return both results and paginator
        return [$results, $paginator];
    }

    // public function getPaginatedResultsPerUserForCustomQuery($userId, $countQuery, $selectQuery, $currentPage = 1)
    // {
    //     // Get the total count of records from the custom count query
    //     $totalRecords = $this->db->query($countQuery, ['id' => $userId])->find()['count'];
    //     // Fetch the paginated results from the custom select query
    //     $results = $this->db->query($selectQuery, ['id' => $userId])->get();
    //     // Instantiate the paginator
    //     $paginator = new LengthAwarePaginator(
    //         $results,
    //         $totalRecords,
    //         $this->perPage,
    //         $currentPage,
    //         ['path' => $this->path]
    //     );

    //     if (
    //         $currentPage > $paginator->lastPage() && $paginator->total() > 0
    //     ) {
    //         abort(404);
    //     }

    //     // Return both results and paginator
    //     return [$results, $paginator];
    // }
}
