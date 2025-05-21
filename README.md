# KlodWeb
## General Policies
  - This is not a democracy. This project is my vision of what an MMO-Strategy game should be, any people who share the vision is welcome, any suggestion is welcome, but the final word is mine.
  - Please READ & USE https://www.conventionalcommits.org/en/v1.0.0/
  - English is the main language of the project, to allow peoples from all over the world to participate
## To Do List
  - Add a README in _setup_ folder to explain how to setup a web portal 
  - Make code easier to read & work as a team
  - Check security of the whole authentication process
## Purpose
"KlodWeb" is the main portal to access the game. It manages player account, subscriptions, fees, and connections to world servers. It suppose to have a minimalistic interface, in English & French (and later, Spanish & Chinese) to register and discover some basics about the game. It should have a video to present the project, and a link to a Wiki to understand the game.
## Setup
This how you can setup a "KlodWeb" Server to test it on you own homelab :
  - Have a LAMP (either a VM, a CT, or baremetal)
  - Upload sources in /var/klodweb/
  - Go to /var/klodweb/setup
  - Edit "Manual variables" section of install.sh if usefull
  - run setup.sh with root privileges
You serveur should be up and running on http://xxxx:443
