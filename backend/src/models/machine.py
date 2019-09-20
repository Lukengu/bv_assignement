# src/models/machine.py
from marshmallow import fields, Schema
from . import db
from sqlalchemy import Enum




class MachineModel(db.Model):
  """
  User Model
  """

  # table name
  __tablename__ = 'machines'

  id = db.Column(db.Integer, primary_key=True)
  computer_name = db.Column(db.String(128))
  processors = db.Column(db.Integer)
  memory = db.Column(db.Integer)
  last_login_date = db.Column(db.Date, nullable=False)
  user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=False)
  machine_type = db.Column("machine_type", Enum("Window", "Linux", "Other", name="m_type", create_type=False))


  # class constructor
  def __init__(self, data):
    """
    Class constructor
    """
    self.computer_name = data.get('computer_name')
    self.processors = data.get('processors')
    self.memory = data.get('memory')
    self.machine_type = data.get('machine_type')
    self.user_id = data.get('user_id')
    self.last_login_date = data.get('last_login_date')

  def save(self):
    db.session.add(self)
    db.session.commit()

  def update(self, data):
    for key, item in data.items():
      setattr(self, key, item)

    db.session.commit()

  def delete(self):
    db.session.delete(self)
    db.session.commit()

  @staticmethod
  def get_all_machines(user_id):
    return MachineModel.query.filter(MachineModel.user_id == user_id).all()

  def __repr__(self):
    return '<id {}>'.format(self.id)



class MachineSchema(Schema):
    """
    MachineSchema Schema
    """
    id = fields.Int(dump_only=True)
    computer_name = fields.Str(required=True)
    processor = fields.Int(required=True)
    user_id = fields.Int(required=True)
    memory = fields.Int(required=True)
    machine_type = fields.Str(required=True)
    last_login_date = fields.Date(required=True)


