<?php

use Core\Csrf\CsrfToken;
use Http\Forms\ReactionForm;
use Http\instance\UserInstance;

?>
<?php require('partials/head.php') ?>
<?php require('partials/nav.php') ?>
<?php require('partials/banner.php') ?>

<main id="like-dislike-app">
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <p class="mb-6 font-bold">Hello, <?= $_SESSION['user']['username'] ?? 'Guest' ?>. Welcome to the home page.</p>
        <?php if (UserInstance::loggedIn()): ?>
            <div class="mx-auto max-w-7xl py-6">
                <a href="movies/create" class="cursor-pointer text-white bg-blue-500 hover:bg-blue-700 font-medium py-2 px-4 rounded">
                    New Movie +
                </a>
            </div>
        <?php endif; ?>
        <?php if (UserInstance::loggedIn()): ?>
            <meta name="csrf-token" content="<?= CsrfToken::generateToken(ReactionForm::$formId); ?>">
        <?php endif; ?>
        <?php if (count($movies) > 0): ?>
            <section>
                <div class="flex flex-col md:flex-row justify-center items-center space-x-2 p-4 bg-gray-900 text-white">
                    <?php $pagination->render(); ?>
                </div>
                <div class="flex bg-gray-800">
                    <!-- Main Content -->
                    <div class="flex-1 p-6">
                        <div class="container mx-auto">
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Movie Post Card -->
                                <?php foreach ($movies as $movie): ?>
                                    <article class="bg-gray-900 p-4 rounded-lg shadow-md">
                                        <h3 class="text-xl font-semibold text-white mb-2"><?= htmlspecialchars($movie['title']) ?></h3>
                                        <p class="text-gray-300 mb-4"><?= htmlspecialchars($movie['description']) ?></p>
                                        <div class="flex items-center justify-between text-sm text-gray-400 mb-4">
                                            <span>Posted by: <strong><?php echo $movie['user_id'] == UserInstance::id() ? '<a href="/user">You</a>' :
                                                                            '<a href="/user?user_id=' . $movie['user_id'] . '">' . htmlspecialchars($movie['username']) . '</a>' ?></strong></span>
                                            <span>Posted at: <strong><?= date('d-m-Y', strtotime($movie['created_at'])); ?></strong></span>
                                        </div>
                                        <?php if (!UserInstance::loggedIn() || (UserInstance::loggedIn() && $movie['user_id'] == UserInstance::id())): ?>
                                            <div class="flex items-center space-x-4 mb-4">
                                                <span class="text-green-600 font-semibold">Likes: <?= $movie['like_count'] ?></span>
                                                <span class="text-red-600 font-semibold">Dislikes: <?= $movie['dislike_count'] ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (UserInstance::loggedIn()): ?>
                                            <?php if (UserInstance::id() != $movie['user_id']): ?>
                                                <like-dislike
                                                    :movie="<?= $movie['id'] ?>"
                                                    :reaction="<?= $movie['user_reaction'] ?>"
                                                    reference="movie"
                                                    :likes="<?= $movie['like_count'] ?>"
                                                    :dislikes=<?= $movie['dislike_count'] ?>>
                                                </like-dislike>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </article>
                                <?php endforeach; ?>
                                <!-- Repeat the above block for each movie post -->
                            </div>
                        </div>
                    </div>
                    <!-- Sidebar (Sticky) -->
                    <div class="w-1/4 p-4 bg-gray-700 rounded-lg">
                        <div class="sticky top-6">
                            <h2 class="text-xl font-semibold text-white mb-6">Filters</h2>
                            <div class="space-y-4 flex flex-col text-center">
                                <a href="?sort_by=like_count&order=ASC" class="w-full p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Sort by Likes (ASC)</a>
                                <a href="?sort_by=like_count&order=DESC" class="w-full p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Sort by Likes (DESC)</a>
                                <a href="?sort_by=dislike_count&order=ASC" class="w-full p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Sort by Dislikes (ASC)</a>
                                <a href="?sort_by=dislike_count&order=DESC" class="w-full p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Sort by Dislikes (DESC)</a>
                                <a href="?sort_by=created_at&order=ASC" class="w-full p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Sort by Date (ASC)</a>
                                <a href="?sort_by=created_at&order=DESC" class="w-full p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Sort by Date (DESC)</a>
                                <a href="/" class="w-full p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Reset</a>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row justify-center items-center space-x-2 p-4 bg-gray-900 text-white">
                    <?php $pagination->render(); ?>
                </div>
            </section>
        <?php else: ?>
            <p class="mt-10">No movie reviews have been posted yet.</p>
        <?php endif ?>
    </div>
</main>

<?php require('partials/footer.php') ?>