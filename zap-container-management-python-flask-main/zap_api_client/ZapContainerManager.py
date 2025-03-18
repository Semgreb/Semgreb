import docker
from .zap_commands import get_zap_command
import os
from dotenv import load_dotenv
load_dotenv()

class ZapContainerManager:
    def __init__(self):
        self.client = docker.from_env()

    def run_zap(self, name, api_key, host_port):
        alias_name = os.getenv("ALIAS_NAME")
        command = get_zap_command(host_port, api_key,alias_name=alias_name ,env="PROD")
        # command = [
        #     'zap.sh', '-daemon', '-port', host_port, '-host', '0.0.0.0',
        #     '-config', f'api.key={api_key}',
        #     '-config', 'api.addrs.addr.name=.*',
        #     '-config', 'api.addrs.addr.regex=true',
        #     '-config', 'network.connection.timeoutInSecs=6'
        # ]

        container = self.client.containers.run(
            'ghcr.io/zaproxy/zaproxy:stable',
            name=name,
            user='zap',
            ports={f'{host_port}/tcp': host_port},
            remove=True,  # Automatically remove the container when it stops
            detach=True,  # Run the container in the background
            command=command,
            # tty=True,  # Allocate a pseudo-TTY
        )


    def stop_all(self):
        containers = self.client.containers.list()
        for container in containers:
            if container.status == "running":
                container_name = container.name
                container.remove(force=True)
                print(f"Container {container_name} deleted successfully.")

    def delete_container_by_name(self, name):
        try:
            container = self.client.containers.get(name)
            container.remove(force=True)
            print(f"Container {name} deleted successfully.")
        except docker.errors.NotFound:
            print(f"Container {name} not found.")
        except docker.errors.APIError as e:
            print(f"Error deleting container: {e}")

    def delete_container_by_port(self, port):
        containers = self.client.containers.list()
        for container in containers:
            ports = container.attrs.get("NetworkSettings", {}).get("Ports", [])
            for port_mapping in ports.keys():
                host_port, _ = port_mapping.split("/")
                if host_port == str(port):
                    container_name = container.name
                    container.remove(force=True)
                    print(f"Container {container_name} deleted successfully.")
                    return

    def container_status_by_port(self, target_port):
        for container in self.client.containers.list():
            container_status = container.status
            ports = container.attrs['NetworkSettings']['Ports']

        # Check if the target port is in the published ports
            if f"{target_port}/tcp" in ports:
                return container_status

    def is_container_running_on_port(self, port_to_check):
        try:
            # Initialize the Docker client
            client = docker.from_env()

            # Get a list of all containers, including the one you're interested in
            all_containers = client.containers.list()

            # Check if the container with the specified name or ID is running and bound to the port
            for container in all_containers:
                container_info = container.attrs
                network_settings = container_info['NetworkSettings']
                ports = network_settings['Ports']
                if f"{port_to_check}/tcp" in ports:
                    return True
                else:
                    return False
            return False
        except docker.errors.APIError:
            print("Error: Docker API request failed.")
            return False

    def count_containers_with_status(self, status="running"):
        try:
            containers_with_status = self.client.containers.list(filters={"status": status})
            num_containers = len(containers_with_status)
            return num_containers
        except Exception as e:
            print(f"Error: {e}")
            return -1

if __name__ == "__main__":
    manager = ZapContainerManager()

    # Start Zap containers
    manager.run_zap("zap_api_1", "123456789", 3001)
    manager.run_zap("zap_api_2", "987654321", 3002)

    # Do some work with the containers...

    # Stop and remove all containers
    manager.stop_all()
