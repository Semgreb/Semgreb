
from flask import Flask, request, jsonify, make_response
from zap_api_client.ZapContainerManager import ZapContainerManager
import os
from dotenv import load_dotenv
from decorators.authenticate_api_key import authenticate_api_key

load_dotenv()
app = Flask(__name__)
zap_manager = ZapContainerManager()
API_KEY = os.getenv("API_KEY")

# Start a Zap container

@app.route('/start', methods=['POST'])
@authenticate_api_key(API_KEY)
def start():
    data = request.get_json()
    container_name = data.get('name')
    api_key = data.get('api_key')
    host_port = data.get('host_port')
    manager = ZapContainerManager()

    if (manager.is_container_running_on_port(host_port)):
        return make_response({
            "port": host_port,
            "running": True
        })

    try:
        manager.run_zap(container_name, api_key, host_port)
    except Exception as e:
        return jsonify({"message": e.__dict__["explanation"]})
    return jsonify({"message": f"Zap container '{container_name}' started", "container": {
        "container_name": container_name,
        "api_key": api_key,
        "host_port": host_port
    }})

# Delete a container by name or ID


@app.route('/delete/<name>', methods=['DELETE'])
@authenticate_api_key(API_KEY)
def delete_container_by_name(name):
    try:
        zap_manager.delete_container_by_name(name)
    except Exception as e:
        return jsonify({"message": e.__dict__["explanation"]})
    return jsonify({"message": f"Container '{name}' deleted"})

# Delete a container by port


@app.route('/delete_by_port/<int:port>', methods=['DELETE'])
@authenticate_api_key(API_KEY)
def delete_container_by_port(port):
    try:
        zap_manager.delete_container_by_port(port)
    except Exception as e:
        return make_response({"message": e.__dict__["explanation"]}, 400)
    return jsonify({"message": f"Container with port '{port}' deleted"})


# Get container status


@app.route('/status/<int:port>', methods=["GET"])
@authenticate_api_key(API_KEY)
def status(port):
    try:
        status = zap_manager.container_status_by_port(port)
    except Exception as e:
        return jsonify({"message": e.__dict__["explanation"]})
    return jsonify({
        "status": status,
    })


@app.route('/status-containers-count', methods=["GET"])
@authenticate_api_key(API_KEY)
def status_containers_count():
    args = request.args
    status = args.get("status", default="running")
    try:
        count = zap_manager.count_containers_with_status(status)
    except Exception as e:
        return jsonify({"message": e.__dict__["explanation"]})

    return jsonify({
        "count": count,
    })
    


if __name__ == "__main__":
    app.run(debug=True)
