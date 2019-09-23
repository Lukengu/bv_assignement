# /src/seeds/users.py
import datetime
from datetime import date

from faker import Faker
from faker.providers import internet, date_time

from src.models.machine import MachineModel
from src.models.users import UserModel
from . import db

fake = Faker()
fake.add_provider(internet)
fake.add_provider(date_time)


class UserSeeder():
    # create two default user
    def populate(self):
        user1 = UserModel({'name': fake.name(), 'username': 'username1', 'password': 'password1'})
        user1.save()
        names = ["Window", "Linux", "Other"]

        for _ in range(50):
            name = names[fake.random.randint(0, 2)]
            machines = MachineModel(
                {
                    'computer_name': fake.user_name(),
                    'processors': fake.random.randint(2048, 4096),
                    'memory': fake.random.randint(2048, 4096),
                    'last_login_date': fake.date_between_dates(datetime.date(2019, 1, 1), date.today()),
                    'user_id': user1.id,
                    'machine_type': name
                })
            machines.save()

        user2 = UserModel({'name': fake.name(), 'username': 'username2', 'password': 'password2'})
        user2.save()

        for _ in range(15000):
            name = names[fake.random.randint(0, 2)]
            machines = MachineModel(
                {
                 'computer_name': fake.user_name(),
                 'processors': fake.random.randint(2048, 4096),
                 'memory': fake.random.randint(2048, 4096),
                 'last_login_date': fake.date_between_dates(datetime.date(2019, 1, 1), date.today()),
                 'user_id': user2.id,
                 'machine_type': name
                 })
            machines.save()
