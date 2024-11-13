<?php

use Core\Session;
use Http\instance\UserInstance;

?>

<nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
                <div class="block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                        <a href="/"
                            class="<?= urlIs('/') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                        <a href="/about"
                            class="<?= urlIs('/about') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">About</a>
                        <a href="/contact"
                            class="<?= urlIs('/contact') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Contact</a>
                        <?php if (UserInstance::loggedIn()) : ?>
                            <a href="/user"
                                class="<?= urlIs('/user') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Profile</a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="block">
                <div class="ml-4 flex items-center md:ml-6">
                    <!-- Profile dropdown -->
                    <?php if (UserInstance::loggedIn()) : ?>
                        <div class="ml-3">
                            <form method="POST" action="/session">
                                <input type="hidden" name="_method" value="DELETE" />

                                <button class="text-white">Log Out</button>
                            </form>
                        </div>
                    <?php else : ?>
                        <div class="ml-3">
                            <a href="/register"
                                class="<?= urlIs('/register') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Register</a>
                            <a href="/login"
                                class="<?= urlIs('/login') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Log
                                In</a>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</nav>
<?php

if (Session::has('success')) {
    $message = Session::get('success');
    echo "<div class='cursor-pointer flash_notification text-black bg-green-600 p-6'>{$message}</div>";
}

if (Session::has('notification')) {
    $message = Session::get('notification');
    echo "<div class='cursor-pointer flash_notification text-black bg-yellow-600 p-6'>{$message}</div>";
}
?>