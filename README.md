# Complete integration sample for the Corbado web component
This is a sample implementation of frontend and backend where the Corbado web component is integrated. You can see the live demo [here](TODO: Link einfügen wenn online)

**Note:** In this tutorial a customer system was built with some preexisting password-based users. Have a look at our [docs](TODO: Link einfügen) to see the changes you have to make if you don't have any users yet.

## 1. File structure
    .
    ├── ...
    ├── config                        
    │   └── routes.yaml                 # Assigns paths to controller methods    
    ├── docker                        
    │   └── .env                        # Contains all Docker environment variables   
    ├── src                             
    │   ├── Controller                  
    │   │   ├── BackendController.php   # Manages endpoints for backend
    │   └── └── FrontendController.php  # Manages endpoints for frontend
    ├── templates                     
    │   ├── home.html.twig              # Home page which you only get to see if you are logged in
    │   └── login.html.twig             # Login page which contains the Corbado web component; Acts as landing page if you are not logged in
    └── ...

## 2. Setup

### 2.1. Prerequisites

Please follow the steps in our [setup guide](TODO: Link) to create and configure a project in our [developer panel](https://app.corabdo.com).

### 2.2. Configure environment variables

Use the values you obtained in step 2.1. to configure the following variables inside `/docker/.env`:
1. **CNAME**: The individually generated CNAME.
2. **PROJECT_ID**: The project ID.
3. **API_SECRET**: The API secret.
4. **NGROK_URL** Your individual ngrok URL (e.g. `https://eb70-212-204-96-162.eu.ngrok.io`)

### 2.3. Start Docker containers

**Note:** Before continuing, please ensure you have [Docker](https://www.docker.com/products/docker-desktop/) installed and accessible from your shell.

Use the following command to start the system:
```
docker compose up
```
**Note:** Please wait until all containers are ready. This can take some time.

To verify that your instance is running without errors enter `http://localhost:8000/ping` into your browser. If "pong" is displayed, everything worked. Entering your ngrok URL with `/ping` as path (e.g. `https://eb70-212-204-96-162.eu.ngrok.io/ping`) should display "pong" as well:

![image](https://user-images.githubusercontent.com/23581140/208480558-c1bcde88-164e-4a22-97de-240fd93af4c1.png)

### 3. Usage

After step 2.3. your local server should be fully functional.

If you now visit your ngrok URL you should be forwarded to the `/login` page:

![image](https://user-images.githubusercontent.com/23581140/208479745-4dc9acaa-cc43-4324-bfd4-ad2ecf0f7901.png)

You can login with one of the existing accounts or sign-up yourself.

| Name | Email | Password |
| --- | --- | --- |
| demo_user | demo_user@company.com | demo12 |
| max | max@company.com | maxPW |
| john | john@company.com | 123456 |

When authenticated you will be forwarded to the homepage:

![image](https://user-images.githubusercontent.com/23581140/208479917-e82f06a9-98d1-406d-89d5-aaceb6bdbb2b.png)

On [localhost:8081](http://localhost:8081) a PHPMyAdmin instance is running where you can view all registered users:

![image](https://user-images.githubusercontent.com/23581140/208480126-65f84460-8914-40e8-a964-ac48bfdeec2f.png)

