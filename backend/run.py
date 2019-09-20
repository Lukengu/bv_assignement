# /src/run.py

import os
from src.app import create_app
"""
Creating local server to run the App
"""

if __name__ == '__main__':
    env_name = os.getenv('FLASK_ENV')
    app = create_app(env_name)
    # run app
    app.run(port=7700)