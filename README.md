# Cycling Race
This sample exercise is used top demonstrate a full stack technology using:
**VueJS (Quasar Framework)** for the front-end, **Laravel / Lumen** for the back-end, **MySQL** for the database, and **Docker** containers that serve as the infrastructure

# Requirements
You need to have **Docker** installed on your local machine. This code base was tested using Docker v18.06 under Windows OS. Although it is not tested under Linux OS, usually it would still work.

# Instruction
To run the code:
1. Clone the code base locally by running the following git command
`git clone https://github.com/luckmansugiarto/cycling-race.git <project name>`

2. Open your terminal / command prompt (in windows), navigate to the project's root directory, and run the following docker command
`docker-compose up -d`. This docker command will instantiate 3 separate containers: backend (api), frontend (webapp), and database. The initial bootup process will take quite a fair bit of time as docker will do all of the jobs of initializing these items: **database tables + sample data**, **backend dependencies through composer**, and **front-end dependencies through yarn**.

Back-end application can be accessed through this URL: **http://localhost:8080** whilst the front-end one can be accessed through **http://localhost:3000** on your browser. For this code base, you do not need to access the back-end part manually as it will be called by the front-end application.
