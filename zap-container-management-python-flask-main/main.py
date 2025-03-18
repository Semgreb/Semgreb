import docker
import re

def is_zap_container(container, tag):
    print(container.name)
    if tag in container.name:
        return True
    return False

client = docker.from_env()

constainers = client.containers.list()
containers_with_tags = []
for container in constainers:
    print(is_zap_container(container, "Testing"))
    if(is_zap_container(container, "Testing")):
        containers_with_tags.append(container)

print(containers_with_tags)

