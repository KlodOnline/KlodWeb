# Setup

This folder contains some of the scripts and tools needed to automate the installation of a _KlodWeb_ server.

## How-To (for Dev)
### The Composer Way
 - Navigate to your own projects folder
 - `git clone --branch develop https://github.com/KlodOnline/KlodWeb.git`
 - If you use Docker Desktop, allow the KlodWeb folder to be shared with the container :
    `Settings > Resources > File sharing > [Add you KlodWeb path]`
 - `cd KlodWeb`
 - `docker compose up -d` (will create the whole stack)
Your server should now be accessible at: `https://127.0.01:443`.
For some reasons, you may have a "Fatal error: Uncaught mysqli_sql_exception: Connection refused" (or equivalent).
Just wait a bit and try again, your containers are not up at the same time.
To have an interactive session inside you web container, do: `docker exec -it klodweb-www bash`.
To have an interactive session inside you db container, do: `docker exec -it klodweb-db bash`.
If you need to rebuild the whole stack, do :
- `docker compose down`
- `docker volume rm klodweb_db_data`
- `docker compose build --no-cache`
- `docker compose up -d`

## How-To (for Production)
Work in progress.
### Requirements
Work in progress.
### How-to
Work in progress.
