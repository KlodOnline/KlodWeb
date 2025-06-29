# Setup

This folder contains some of the scripts and tools needed to automate the installation of a _KlodWeb_ server.

## How-To (for Dev)
### The Docker Way
 - `git clone --branch develop https://github.com/KlodOnline/KlodWeb.git`
 - `cd KlodWeb`
 - `make build`
 - `make run`
Your server should now be accessible at: `https://127.0.01:443`
To have an interactive session inside you container, do: `docker exec -it klodweb /bin/bash`

### The Other way
#### Requirements
- A working LAMP environment (Linux + Apache + MySQL + PHP)
- Root access to install and configure services

#### How-to
To deploy a **KlodWeb** server on your homelab:
1. Set up a LAMP environment (can be a VM, container, or bare-metal).
2. Copy the source files to `/var/klodweb/`.
3. Navigate to the `/var/klodweb/setup` directory.
4. If needed, edit the Manual variables section in `install.sh`.
5. Run `setup.sh` with root privileges.

Your server should now be accessible at: `https://<your-ip-or-domain>:443`
