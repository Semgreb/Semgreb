from functools import wraps
from flask import request, jsonify

def authenticate_api_key(api_key):
    def decorator(func):
        @wraps(func)
        def wrapper(*args, **kwargs):
            # Check if the 'X-API-Key' header is present in the request
            if 'X-API-Key' not in request.headers:
                return jsonify({'error': 'API key is missing'}), 401
            
            # Compare the provided API key with the expected API key
            provided_api_key = request.headers['X-API-Key']
            if provided_api_key != api_key:
                return jsonify({'error': 'Invalid API key'}), 403

            # If the API key is valid, proceed with the original function
            return func(*args, **kwargs)

        return wrapper
    return decorator
