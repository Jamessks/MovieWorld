<?php

namespace Http\utils\renderers;

class PaginatorRenderer
{
    protected $paginator;

    public function __construct($paginator)
    {
        $this->paginator = $paginator;
    }

    public function render()
    {
        $currentPage = $this->paginator->currentPage();
        $lastPage = $this->paginator->lastPage();
        $path = $this->paginator->path();
        $perPage = $this->paginator->perPage();
        $start = max(1, $currentPage - 1);
        $end = min($lastPage, $currentPage + 1);

        if ($end + 1 < $lastPage) {
            $end = $currentPage + 1;
        }
        $total = $this->paginator->total();
        require '../views/partials/paginatorStandard.php';
    }
}
