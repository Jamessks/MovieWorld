# MovieWorld

A website in which users may post reviews about a movie they like.

## Basic Requirements

Composer (optional)

Docker

## Deployment Instructions

### Quick Start (Recommended)

1. **Clone the Repository**:

   ```bash
   git clone git@github.com:Jamessks/MovieWorld.git
   cd MovieWorld
   ```

2. **Start the Application**:

   ```bash
   ./start.sh
   ```

3. **Access the Application**:
   Open http://localhost:8081 in your browser

### Manual Setup

If you prefer manual setup:

1. **Prerequisites**: Ensure Docker is running
2. **Start containers**: `docker-compose up --build -d`
3. **Install dependencies** (if needed): `docker-compose exec web composer install`
4. **Access application**: http://localhost:8081

### Notes

From within your docker application -> `'tests-1' image -> EXEC tab`

and run

```
vendor/bin/pest
```

to run available tests with Pest

# **Available actions for the project**

**All users**

- visit the home page http://localhost:8081/

- view other users' movie review posts http://localhost:8081/

- visit non-important pages

- view movie review posts and sort them by various filters

**Non logged-in user**

- register a new account http://localhost:8081/register

- login to their account http://localhost:8081/login

- not view other users' profiles

**Logged-in user**

- view other users' and their own movie review posts http://localhost:8081/

- log out of their account (from navigation bar)

- create a new movie review post http://localhost:8081/movies/create

- react or undo their reaction (like/dislike) to other users' movie review posts but not their own

- view their own or other people's profiles http://localhost:8081/user or http://localhost:8081/user?user_id=5 (if a user with id of 5 exists)

- delete their own movie review posts from within their own profile page http://localhost:8081/user
