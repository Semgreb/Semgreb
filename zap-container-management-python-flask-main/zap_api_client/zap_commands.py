def get_zap_command(host_port, api_key, alias_name, env="DEV"):
    command = [
        'zap.sh', '-daemon', '-port', host_port, '-host', '0.0.0.0',
        '-config', f'api.key={api_key}',
        '-config', 'api.addrs.addr.name=.*',
        '-config', 'api.addrs.addr.regex=true',
        '-config', 'network.connection.timeoutInSecs=6',
    ]

    if env == "PROD":
        alias_config = f"network.localServers.aliases.alias.name={alias_name}"
        command.append('-config')
        command.append(alias_config)

    return command
