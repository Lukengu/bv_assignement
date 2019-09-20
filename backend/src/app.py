# /src/app.py
""" Bootstrap file"""

from flask import Flask
from .config import app_config
from .models import db, bcrypt
# import user_api blueprint
from .views.users import user_api as  user_blueprint
from .views.machines import machine_api as machine_blueprint


def create_app(env_name):
    """
    Create app
    """
    # app initiliazation
    app = Flask(__name__)
    app.config.from_object(app_config[env_name])

    # initializing bcrypt
    bcrypt.init_app(app)
    # initialize database
    db.init_app(app)

    app.register_blueprint(user_blueprint, url_prefix='/api/v1/users')
    app.register_blueprint(machine_blueprint, url_prefix='/api/v1/machines')

    @app.route('/', methods=['GET'])
    def index():
        """
        Test endpoint
        """
        return 'Test endpoint'

    return app
