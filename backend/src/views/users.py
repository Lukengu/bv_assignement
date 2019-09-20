# /src/views/users
import sqlalchemy
from flask import request, json, Response, Blueprint
from ..models.users import UserModel, UserSchema
from ..shared.authentication import Auth

# Group All the user related endpoint  to one group(Blueprint)
user_api = Blueprint('user_api', __name__)
user_schema = UserSchema()


@user_api.route('/', methods=['POST'])
def create():
    """
    Create User Function
    """
    data = request.get_json()
    try:
        # check if user already exist in the db
        user_in_db = UserModel.get_user_by_username(data.get('username'))
        if user_in_db:
            message = {'error': 'User already exist, please supply username'}
            return custom_response(message, 400)
    except sqlalchemy.orm.exc.NoResultFound:
        user = UserModel(data)
        user.save()

        token = Auth.generate_token(user.id)
        return custom_response({'jwt_token': token}, 201)


@user_api.route('/login', methods=['POST'])
def login():
    """
    Login function
    """
    data = request.get_json()
    if not data.get('username') or not data.get('password'):
        return custom_response({'error': 'you need username and password to sign in'}, 400)

    user = UserModel.get_user_by_username(data.get('username'))
    if not user:
        return custom_response({'error': 'invalid credentials'}, 400)

    if not user.check_hash(data.get('password')):
        return custom_response({'error': 'invalid credentials'}, 400)

    # ser_data = user_schema.dump(user).data
    token = Auth.generate_token(user.id)
    return custom_response({'jwt_token': token}, 200)


def custom_response(res, status_code):
    """
  Custom Response Function
  """
    return Response(
        mimetype="application/json",
        response=json.dumps(res),
        status=status_code
    )
