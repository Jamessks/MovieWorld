<?php
$queryParams = $_GET;
unset($queryParams['page']);
$queryString = http_build_query($queryParams);
?>

<?php if ($total > $perPage): ?>
    <!-- Previous Page Link -->
    <?php if ($currentPage > 1): ?>
        <a href="<?= $path . '?' . $queryString . '&page=' . ($currentPage - 1) ?>" class="px-3 py-1 text-white hover:bg-gray-700 hover:text-white rounded">Previous</a>
    <?php endif; ?>

    <!-- Page Number Links -->
    <?php for ($i = $start; $i <= $end; $i++): ?>
        <?php if ($i == $currentPage): ?>
            <strong class="px-3 py-1 text-white bg-gray-800 rounded"><?= $i ?></strong>
        <?php else: ?>
            <a href="<?= $path . '?' . $queryString . '&page=' . $i ?>" class="px-3 py-1 text-white hover:bg-gray-700 hover:text-white rounded"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <!-- Ellipsis if needed -->
    <?php if ($end < $lastPage - 1): ?>
        <span class="px-3 py-1 text-gray-500">...</span>
    <?php endif; ?>

    <!-- Last Page Link -->
    <?php if ($end < $lastPage): ?>
        <a href="<?= $path . '?' . $queryString . '&page=' . $lastPage ?>" class="px-3 py-1 text-white hover:bg-gray-700 hover:text-white rounded"><?= $lastPage ?></a>
    <?php endif; ?>

    <!-- Next Page Link -->
    <?php if ($currentPage < $lastPage): ?>
        <a href="<?= $path . '?' . $queryString . '&page=' . ($currentPage + 1) ?>" class="px-3 py-1 text-white hover:bg-gray-700 hover:text-white rounded">Next</a>
    <?php endif; ?>
<?php endif; ?>