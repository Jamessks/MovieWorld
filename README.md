# MovieWorld

A website in which users may post reviews about a movie they like.

## Basic Requirements

Composer (installed and able to run)

Docker

## Deployment Instructions

Follow these steps to set up the project locally:

1. **Clone the Repository**:
   Preferably, on a linux system, navigate to ```/var/www``` and clone the project there (no need to make changes to .env that way)
   ```bash
   git@github.com:Jamessks/MovieWorld.git
   ```

   or just download it as a zip file and place it in the directory of your choice.

2. **Project root**: CD to the root directory of the project with a terminal of your choice (I used WSL2 terminal).

3. **Composer**: run ```composer install```. This will create the vendor folder in the project.

4. **.env configuration**: At project root check that everything is OK in .env file.

5. **Open docker**: Open your docker desktop application

6. **IMPORTANT:** make sure `APP_PATH` points to the actual directory that you have imported the project into eg. if you are at ```/var/www``` and you git cloned the repo, change the value of the `APP_PATH` to `/var/www/${APP_NAME}`

7. **ALSO IMPORTANT:** In the case that you change the .env `APP_NAME` from MovieWorld to something else, make sure you change the supervisord/supervisord.conf `command` and `directory` project name to reflect that change eg. `command=php /var/www/DifferentProjectName/Core/RedisManagement/workers/worker_reaction.php` and `directory=/var/www/DifferentProjectName` (no need to change anything before or after the project name)

8. **Initialize containers**: `docker-compose up --build -d` from your terminal at project root

9. **Access application**: Access the project at http://localhost:8081

PS: From within your docker application -> `'tests-1' image -> EXEC tab`

and run

```
vendor/bin/pest
```

to run available tests with Pest

# **Available actions for the project**

**All users**

may visit the home page http://localhost:8081/

may view other users' movie review posts http://localhost:8081/

may visit non-important pages

may view movie review posts and sort them by various filters

**Non logged-in user**

may register a new account http://localhost:8081/register

may login to their account http://localhost:8081/login

may not view other users' profiles

**Logged-in user**

may view other users' and their own movie review posts http://localhost:8081/

may log out of their account (from navigation bar)

create a new movie review post http://localhost:8081/movies/create

may react or undo their reaction (like/dislike) to other users' movie review posts but not their own

may view their own or other people's profiles http://localhost:8081/user or http://localhost:8081/user?user_id=5 (if a user with id of 5 exists)

delete their own movie review posts from within their own profile page http://localhost:8081/user

# **What the project includes**

**_MVC custom framework_** that I work and practice on and add functionalities on the go for small scale projects such as this.

**_Security_**: CSRF token for form validation.

**_MySql_**: Users table to hold user data. Movies table to hold movie data and like_dislike table which uses a polymorphic many-to-many relationship between multiple types of content (imagine in the future if we wanted to add comments that users could like or dislike), we can use the same table to store that information. Appropriate indexes have been added to certain fields such as:

`` KEY `idx_user_id_on_like_dislike` (`user_id`) ``

`` KEY `idx_target_id_on_like_dislike` (`target_id`) ``

`` KEY `idx_user_target` (`user_id`,`target_type`,`target_id`) ``

`` KEY `idx_user_reaction` (`user_id`,`reaction`) ``

Which are mostly found in select queries and are very frequently requested.

**_Redis_**: To track user login sessions and rate limiting (block users that make too many requests in a small amount of time)

**_Redis Queue_**: When users delete their own movie review posts, reactions are also removed but in a queue from a worker that is set to run by supervisord (http://supervisord.org/). if a movie had 1000 reactions and the movie was deleted, we would have to delete the reactions as well. Ideally we dont want the user to wait for 1000 reactions to be removed through cascading so we delegate that heavy action to a Redis Queue that an automated worker is watching in the background, outside the main server.

**_Vue.js_**: It handles reactive data such as movie Like count and Dislike count as well as reacting to movie review posts. When a user reacts to a movie, a PATCH request through AJAX is sent to `/api/reaction.php`. The api validates data (user is logged in, movie exists, movie does not belong to user who initiated the reaction etc.) rejects requests that are considered bad and if everything is OK the reaction is logged and passed to the database in the like_dislike table.
