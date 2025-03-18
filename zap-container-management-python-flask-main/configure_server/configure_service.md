# Running Application as a Service with Systemd

This are the steps to configure the server in order to make sure the application runs uninterrupted. This will run the app in production with Gunicorn


## Configuring a Service with Systemd



Make a copy
Edit document in the variable ExecStart and User
Move the file to the directory





cp container_manager.service.template container_manager.service



sudo mv container_manager.service /etc/systemd/system



Make a copy of  `container_manager.service.template` 

Move it to  `/etc/systemd/system`

Make sure to change `ExecStart` line for your needs

Reload systmd
```
sudo systemctl daemon-reload
```

Start the service
```
sudo systemctl start container_manager
```

Check the status
```
sudo systemctl status container_manager
```

## References

[A good article for guidence](https://blog.miguelgrinberg.com/post/running-a-flask-application-as-a-service-with-systemd)

[An article about systemd](https://www.digitalocean.com/community/tutorials/how-to-use-systemctl-to-manage-systemd-services-and-units)