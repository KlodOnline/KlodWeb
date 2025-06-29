# Setup

This folder contains some of the scripts and tools needed to automate the installation of a _KlodWeb_ server.

## How-To (for Dev)
### The Composer Way
 - Navigate to your own projects folder
 - `git clone --branch develop https://github.com/KlodOnline/KlodWeb.git`
 - If you use Docker Desktop, allow the KlodWeb folder to be shared with the container :
    `Settings > Resources > File sharing > [Add you KlodWeb path]`
 - `cd KlodWeb`
 - `make up` (will create the whole stack)
Your server should now be accessible at: `https://127.0.0.1:1443`.
For some reasons, you may have a "Fatal error: Uncaught mysqli_sql_exception: Connection refused" (or equivalent).
Just wait a bit and try again, your containers are not up at the same time.
To have an interactive session inside the following containers :
   - db -> `make sh-db`
   - php -> `make sh-php`
   - apache -> `make sh-apache`

If you need to connect to the database from inside the container, run:
- `make sh-db`
- Then, inside the container: `mariadb -p`
- Enter the root password defined in your `docker-compose-dev.yml` (`rootpass` by default).

If you need to clean the database, do :
- `make clean-db`

Other useful commands:
- `make ps`    # Show the status of all containers
- `make logs`   # Follow the logs of all containers
- `make down`   # Stop and remove all containers

## How-To (for Production)
Work in progress.
### Requirements
Work in progress.
### How-to
Work in progress.
