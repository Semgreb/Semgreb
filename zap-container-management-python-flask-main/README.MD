# Zap Container Management Script

This script is used to create and manage containers running OWASP ZAP (Zed Attack Proxy). OWASP ZAP is a popular open-source security tool for finding vulnerabilities in web applications. With this script, you can start and manage multiple ZAP containers

* Zed Attack Proxy: https://www.zaproxy.org/ 
* Zap API: https://www.zaproxy.org/docs/api/?python#general-steps
* Docker SDK for Python: https://docker-py.readthedocs.io/en/stable/index.html
* Gunicorn: https://gunicorn.org/

## Getting Started
1. Clone the repository or download the script.
2. Run `./install_services.sh`
3. Install the required dependencies. `pip install -r requirements.txt`
4. duplicate .env.template and rename it to .env
  - API_KEY used for authenticate requests.
  - ALIAS_NAME used to store the server ip.

## Run the application in Development
Run the app using `python3 app.py`

You can run the app using `flask run` and specify the host using -h or the port using -p
`flask run -h localhost -p 3001`

## Endpoints


### Authentication

For authentication, include the following header in your request:

- **Header:**
  - `X-API-KEY` (string): The API key for authentication.

### Start a Zap Container
- **Description:** Starts a new ZAP container.
- **Endpoint:** `/start` (HTTP POST)
- **Request Payload:**
  - `name` (string): A unique name for the container.
  - `api_key` (string): The API key for the ZAP container.
  - `host_port` (int): The host port to bind the ZAP container to.
- **Response:** JSON response indicating the status of the container.
- **Example Request:**
```json
POST /start
{
  "name": "my_zap_container",
  "api_key": "my_api_key",
  "host_port": 8080
}
```
- **Example Response:**
```json
{
  "message": "Zap container 'my_zap_container' started",
  "container": {
    "container_name": "my_zap_container",
    "api_key": "my_api_key",
    "host_port": 8080
  }
}

```
### Delete a Container by Name or ID
- **Description:** Deletes a ZAP container by its name.
- **Endpoint:** `/delete/<name>` (HTTP DELETE)
- **Path Parameter:** `name` (string): The name of the container to delete.
- **Response:** JSON response indicating the status of the deletion.
- **Example Request:**
```
DELETE /delete/my_zap_container
```
- **Example Response:**
```json
{
  "message": "Container 'my_zap_container' deleted"
}
``` 
### Delete a Container by Port
- **Description:** Deletes a ZAP container by its host port.
- **Endpoint:** `/delete_by_port/<int:port>` (HTTP DELETE)
- **Path Parameter:** `port` (int): The host port of the container to delete.
- **Response:** JSON response indicating the status of the deletion.
- **Example Request:**

```
DELETE /delete_by_port/8080
```

- **Example Response:**
```json
{
  "message": "Container with port '8080' deleted"
}
```
### Get Container Status
- **Description:** Retrieves the status of a ZAP container by its host port.
- **Endpoint:** `/status/<int:port>` (HTTP GET)
- **Path Parameter:** `port` (int): The host port of the container to check.
- **Response:** JSON response indicating the status of the container.
- **Example Request:**
```
GET /status/8080
```
- **Example Response:**
```json
{
  "status": "running"
}
```

### Available Status 

- created: The container has been created but is not yet running.
- restarting: The container is in the process of restarting.
- running: The container is currently running and active.
- removing: The container is in the process of being removed.
- paused: The container is paused.
- exited: The container has stopped and is no longer running.
- dead: The container has encountered an unrecoverable error and is not running.

## Usage
1. Start a ZAP container by sending a POST request to `/start`.
2. Delete a container by name or host port using the appropriate DELETE request.
3. Retrieve the status of a container using a GET request to `/status`.


# Production Deployment

## Guicorn

When running publicly rather than in development, you should not use the built-in development server (flask run or python3 app.py). Instead, use a production WSGI server. Here we are using [Gunicorn](https://gunicorn.org/).

``
gunicorn -w 4 app:app
``

## Configure systemd service to run App in backgroud

To deploy the application in a production server, we need to configure a daemon using systemd. Linux uses a program called systemd that supervises the application and makes sure the application runs uninterrupted. If the application crashes, you'd want it to automatically restart, and if the server experiences a power outage, you'd want the application to start immediately once power is restored. Basically what you need is something that keeps an eye on the application and restarts it if it ever finds that it isn't running anymore.

[Documenation to run configure service](/configure_server/configure_service.md)




# After configure Systemd Service

