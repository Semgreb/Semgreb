#!/bin/bash

# Update package manager and install required dependencies
sudo apt-get update
sudo apt-get install -y ca-certificates curl gnupg software-properties-common

# Remove old Docker-related packages (if any)
for pkg in docker.io docker-doc docker-compose docker-compose-v2 podman-docker containerd runc; do
  sudo apt-get remove -y $pkg
done

# Add Docker's official GPG key
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

# Add the Docker repository to Apt sources
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(. /etc/os-release && echo $VERSION_CODENAME) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Update package manager again to refresh package lists
sudo apt-get update

# Install Docker and its components
sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# Manage Docker as a non-root user
sudo groupadd docker
sudo usermod -aG docker $USER
newgrp docker

# Install Python 3.11 and Pip
sudo add-apt-repository ppa:deadsnakes/ppa -y
sudo apt-get update
sudo apt-get install -y python3.11 python3.11-dev python3.11-venv python3-pip
sudo apt install python3-pip

# Install gunicorn
sudo apt-get install gunicorn

# Install project dependencies (replace with your actual dependency installation commands)
# For example, you can use pip to install Python packages:
# sudo pip3.11 install package1 package2 ...

# Install Nginx
sudo apt install nginx


# Print installation completion message
echo "Installation completed: Docker, Python 3.11, Pip, and project dependencies have been installed."
