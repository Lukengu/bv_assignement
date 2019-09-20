# /src/views/machines

from flask import request, json, Response, Blueprint, g

from src.models.machine import MachineModel, MachineSchema
from ..models.users import UserModel
from ..shared.authentication import Auth

# Group All the machines related  endpoint to one group(Blueprint)
machine_api = Blueprint('machine_api', __name__)
machineSchema = MachineSchema()


@machine_api.route('/', methods=['GET'])
@Auth.auth_required
def user_machines():
    """
    Get User Machine
    """
    user = UserModel.get_one_user(g.user.get('id'));
    machines = machineSchema.dump(user.machines, many=True)
    return custom_response(machines, 200)


def custom_response(res, status_code):
    """
  Custom Response Function
  """
    return Response(
        mimetype="application/json",
        response=json.dumps(res),
        status=status_code
    )
