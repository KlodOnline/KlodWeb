# Setup

This folder contains the scripts and tools needed to automate the installation of a _KlodWeb_ server.

## To-Do List

  - `setup.sh` actually add daemons the "old way" because of Docker images (files in `/etc/init.d` and usage with `service [name] start|stop` etc.) The script should detect if we have **systemctl** available or not and behave properly according to.

## Usage

### Requirements

- A working LAMP environment (Linux + Apache + MySQL + PHP)
  - Can be a virtual machine, container (CT), or bare-metal server
- Root access to install and configure services

### How-to

To deploy a **KlodWeb** server on your homelab:
1. Set up a LAMP environment (can be a VM, container, or bare-metal).
2. Copy the source files to `/var/klodweb/`.
3. Navigate to the `/var/klodweb/setup` directory.
4. If needed, edit the Manual variables section in `install.sh`.
5. Run `setup.sh` with root privileges.

Your server should now be accessible at: `https://<your-ip-or-domain>:443`
