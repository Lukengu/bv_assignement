# Bidvest Assignement code sample
The code is comprise with a back end written in Python using Flask framework and postgresql  and a frontend written in php symphony framework
#Backend

 #Pre-requisites
  Make sure you have the following installed on your system

	- Python3.x  Rest Service Application Languafe
	- PostgreSQL - Data storage
	- Pipenv - we'll use pipenv to create and manage our project virtual environment and to also install and uninstall packages
	- Linux Os preferable(the code have been tested in linux) 

 #Installation
	- checkout the project in a directory of your choice
	- navigate to to the backend folder trough a terminal
	- type the following command "pipenv install" 
	- At this stage a assumption is made that your postgres server is already been set and you have the connection string
	- Setup your environment variable
		- On Linux Os terminal  type export DATABASE_URL=postrges://yourpostegresusername:password@localhost/yourdatabse
		- On Linux Os terminal  type export FLASK_ENV=development
		- On Linux Os terminal  type export JWT_SECRET_KEY=Random string for token generation
	- Create your database migration and populate dummy data
	   Still Inside the backend folder : run the manage script to populate the database:
		- python manage.py db init : to initiate migration
		- python manage.py db migrate:  to create migrations
		- python manage.py db upgrade : to create tables
		- python manage.py seeds : to generate and populate the database

	   this will create a users table  with  2 records   and a nachines table with 1550 records.
#Run
type python run.py : this will start the serve at port 7700 and you can access the following endpoint
  - GET http://localhost:7700 : Test endpoint to make sure that the system is working
  - GET http://localhost:7700/api/v1/machines   to get the user machines (required authentication) -H Authorization Bearer "access_token" 
  - POST  http://localhost:7700/api/v1/users/login -H Content-type : json  -d "{"username":"username1", "password":"password1"}"  or 
"{"username":"username2", "password":"password2"}" this will return an Access token to use then in the previous request
  - POST  http://localhost:7700/api/v1/users  -H Content-type : json  -d "{"username":"auser", "password":"apassw","name":"aname"}" to register a user


The live api is available at: http://197.189.224.34:8500
		