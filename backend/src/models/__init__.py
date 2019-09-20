#src/models/__init__.py
from flask_sqlalchemy import SQLAlchemy
from flask_bcrypt import Bcrypt
# initialize the db
db = SQLAlchemy()
# initialize password encryption
bcrypt = Bcrypt()