# MovieWorld

A website in which users may post reviews about a movie they like.

## Basic Requirements

Composer (optional)

Docker

## Deployment Instructions

Follow these steps to set up the project locally:

1. **Clone the Repository**:
   Navigate to ```/var/www``` and clone the project there (no need to make changes to .env that way)
   ```bash
   git@github.com:Jamessks/MovieWorld.git
   ```

   or download it as a zip file and extract it in the directory of your choice.

2. **Project root**: CD to the root directory of the project with a terminal of your choice.

3. **Composer**: run ```composer install```. This will create the vendor folder in the project. (Read step 9 if you don't have composer outside docker)

4. **.env configuration**: Ensure .env is configured to your needs.

5. **Open docker**: Open your docker desktop application

6. Inside .env file make sure `APP_PATH` points to the actual directory that you have imported the project into eg. if you are at ```~/myprojects/``` and you git cloned the repo, change the value of the `APP_PATH` to `~/myprojects/${APP_NAME}`

7. In the case that you change the .env `APP_NAME` from MovieWorld to something else, make sure you change the supervisord/supervisord.conf `command` and `directory` project name to reflect that change eg. `command=php /var/www/DifferentProjectName/Core/RedisManagement/workers/worker_reaction.php` and `directory=/var/www/DifferentProjectName`

8. **Initialize containers**: `docker-compose up --build -d` from your terminal at project root
9. **Composer alternative**: If you don't have composer outside docker. **STEP 1**: From Docker desktop go to web-1 container -> EXEC tab -> ```composer install```. **STEP 2**: run ```docker-compose down``` outside docker. **STEP 3**: run ```docker-compose up --build -d``` outside docker.

10. **Access application**: Access the project at http://localhost:8081

From within your docker application -> `'tests-1' image -> EXEC tab`

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


Take a look at the rest of my pinned repositories https://github.com/Jamessks
