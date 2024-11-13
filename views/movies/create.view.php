<?php require base_path('views/partials/head.php') ?>
<?php require base_path('views/partials/nav.php') ?>
<?php require base_path('views/partials/banner.php') ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="mt-5 md:col-span-2 md:mt-0">
                <form class="bg-gray-700" method="POST" action="/movies">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">
                    <div class="shadow sm:overflow-hidden sm:rounded-md">
                        <div class="space-y-6 px-4 py-5 sm:p-6">
                            <div>
                                <label class="text-white" for="title">Title</label>
                                <input id="title" name="title" type="text" autocomplete="title" required
                                    class="relative block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Movie Title eg. The Avengers">
                            </div>
                            <div>
                                <label
                                    for="description"
                                    class="text-white">Description</label>
                                <div class="mt-1">
                                    <textarea
                                        id="description"
                                        name="description"
                                        rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Here's what I think about the movie..."><?= $_POST['description'] ?? '' ?></textarea>
                                </div>
                            </div>
                            <button type="button" onclick="generateLoremIpsum()" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600 mb-4">
                                Generate Lorem Ipsum
                            </button>
                        </div>
                        <div class="flex px-4 py-3 text-right sm:px-6 gap-4 justify-end">
                            <a href="/" class="bg-red-500 text-white py-2 px-4 rounded  hover:bg-red-700">Back</a>
                            <button
                                type="submit"
                                class="cursor-pointer text-white bg-blue-500 hover:bg-blue-700 font-medium py-2 px-4 rounded">
                                Save
                            </button>
                        </div>
                    </div>

                </form>
                <?php if (isset($_SESSION['_flash']['errors'])): ?>
                    <ul class="mt-4">
                        <?php foreach ($_SESSION['_flash']['errors'] as $field => $error): ?>
                            <li class="text-red-500 text-lg mt-2"><?= $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
    function generateLoremIpsum() {
        const loremIpsum = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        document.getElementById('description').value += loremIpsum;
    }
</script>

<?php require base_path('views/partials/footer.php') ?>