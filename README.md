# Complete integration sample for the Corbado web component
This is a sample implementation of frontend and backend where the Corbado web component is integrated.

**Note:** In this tutorial a customer system is created with some pre-existing password-based users. Have a look at our [docs](https://docs.corbado.com/integrations/web-component/no-existing-user-base) to see the integration if you don't have any users yet.

## 1. File structure
    ├── ...
    ├── config      
    |   └── routes.yaml                 # Assigns paths to controller methods    
    ├── src                             
    │   ├── Controller                  
    │   │   ├── WebhookController.php   # Manages endpoints for webhook
    │   └── └── AppController.php       # Manages endpoints for application
    ├── templates                     
    │   ├── home.html.twig              # Home page which you only get to see if you are logged in
    │   ├── login.html.twig             # Login page which contains the Corbado web component; Acts as landing page if you are not logged in
    ├── .env                            # Contains all Symfony environment variables
    └── ...
    
## 2. Setup

> :warning: **If you are using a Windows machine**: Make sure to execute `git config --global core.autocrlf false` before cloning this repository to prevent git from changing the line endings of the bash scripts. (Docker will not be able to find the scripts if git does this)

### 2.1. Prerequisites

Please follow steps 1-3 on our [Getting started](https://docs.corbado.com/overview/getting-started) page to create and configure a project in the [developer panel](https://app.corbado.com).

### 2.2. Configure environment variables

Use the values you obtained in step 2.1 from the [developer panel](https://app.corbado.com/app/settings/credentials). to configure the following variables inside `.env`:
1. **PROJECT_ID**: The project ID.
2. **API_SECRET**: The API secret.
3. **CLI_SECRET** The CLI secret.

### 2.3. Start Docker containers

**Note:** Before continuing, please ensure you have [Docker](https://www.docker.com/products/docker-desktop/) installed and accessible from your shell.

Use the following command to start the system:
```
docker compose up
```
**Note:** Please wait until all containers are ready. This can take some time. 

### 2.4. Error check (optional)

To verify that your instance is running without errors enter `http://localhost:8000/ping` in your browser. If "pong" is displayed, everything worked.

<img width="1115" alt="image" src="https://user-images.githubusercontent.com/18458907/219000434-058d2a75-9480-43e9-b6f9-a09be769cfb8.png">

## 3. Usage

After step 2.3. your local server should be fully working.

### 3.1. Test authentication

If you now visit `http://localhost:8000`, you should be forwarded to the `/login` page:

<img width="1111" alt="image" src="https://user-images.githubusercontent.com/18458907/219000019-dd6ab040-f61d-4bd4-8752-221c77ca3301.png">

You can login with one of the existing accounts or sign-up yourself.

| Name | Email | Password |
| --- | --- | --- |
| demo_user | demo_user@company.com | demo12 |
| max | max@company.com | maxPW |
| john | john@company.com | 123456 |

When authenticated you will be forwarded to the home page:

<img width="1114" alt="image" src="https://user-images.githubusercontent.com/18458907/219000168-d500bbfc-0fd9-41ea-a553-590c28105c57.png">

### 3.2. View all users

On [localhost:8081](http://localhost:8081) a PHPMyAdmin instance is running where you can view all registered users:

<img width="1114" alt="image" src="https://user-images.githubusercontent.com/18458907/219000289-24cb9225-f226-43ef-85d9-6356b1f419a2.png">
