# src/models/users.py
from marshmallow import fields, Schema

from . import db
from . import bcrypt
from .machine import MachineSchema


class UserModel(db.Model):
    """
    User Model
    """
    # table name
    __tablename__ = 'users'


    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(128), nullable=False)
    username = db.Column(db.String(128), unique=True, nullable=False)
    password = db.Column(db.String(128), nullable=False)
    machines = db.relationship('MachineModel', backref='users', lazy=True)


    # class constructor
    def __init__(self, data):
        """
        Class constructor
        """
        self.name = data.get('name')
        self.username = data.get('username')
        self.password = self.__generate_hash(data.get('password'))


    def save(self):
        db.session.add(self)
        db.session.commit()

    def update(self, data):
        for key, item in data.items():
            if key == 'password':
                self.password = self.__generate_hash(item)
            setattr(self, key, item)

        db.session.commit()

    def delete(self):
        db.session.delete(self)
        db.session.commit()

    @staticmethod
    def get_all_users():
        return UserModel.query.all()

    @staticmethod
    def get_one_user(id):
        return UserModel.query.get(id)

    @staticmethod
    def get_user_by_username(username):
        return UserModel.query.filter_by(username=username).one()

    # Password encryption
    def __generate_hash(self, password):
        return bcrypt.generate_password_hash(password, rounds=10).decode("utf-8")

    # Password check
    def check_hash(self, password):
        return bcrypt.check_password_hash(self.password, password)

    def __repr(self):
        return '<id {}>'.format(self.id)

class UserSchema(Schema):
    """
    User Schema
    """
    id = fields.Int(required=True)
    name = fields.Str(required=False)
    username = fields.Str(required=True)
    password = fields.Str(required=True)
    created_at = fields.DateTime(required=True)
    machines = fields.Nested(MachineSchema, required=True)
