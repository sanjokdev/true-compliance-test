Laravel 12 True Compliance technical task. 

### If using docker

I have provided the .env file for this test. feel free to use your own environment variables.

## Run the Container
docker compose up --build -d


### Thing to note
## 1: Prefix routes with "api" since it is api endpoints.

## 2: Creating certificates
On the README.txt instructions you sent for the task, I got slightly confused when instructed to create a certificate like this 
POST /certificate     - Creates a certificate D

But looking at the table and sample data , looks like certicates can't be created on their own and needs to belong to a property, hence I changed the route so that the certicate can only be created when related to a property like this 
POST /property/{id}/certificate     - Creates a certificate D

Thank you for providing task and hopefully will be able to hear from you soon.


