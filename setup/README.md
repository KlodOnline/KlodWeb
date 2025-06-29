# Setup

This folder contains some of the scripts and tools needed to automate the installation of a _KlodWeb_ server.

## How-To (for Dev)
### The Docker Way
 - Navigate to your own projects folder
 - `git clone --branch develop https://github.com/KlodOnline/KlodWeb.git`
 - `cd KlodWeb`
 - `make build`
 - `make run`
Your server should now be accessible at: `https://127.0.01:443`.
To have an interactive session inside you container, do: `docker exec -it klodweb /bin/bash`.
Use `docker start|stop klodweb` to ... well I hope you understood.

## How-To (for Production)
### Requirements
- A working LAMP environment (Linux + Apache + MySQL + PHP)
- Root access to install and configure services
### How-to
 - navigate to `/var`
 - execute `git clone --branch main https://github.com/KlodOnline/KlodWeb.git`
 - execute `/var/klodweb/setup/install.sh` installation script
 - (todo) execute the `/var/klodweb/setup/prod_cleaner.sh` script
Your server should now be accessible at: `https://www.klodonline.com:443`.
