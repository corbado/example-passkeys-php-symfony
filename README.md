# Complete integration sample for the Corbado web component
This is a sample implementation of frontend and backend where the Corbado web component is integrated. You can see a live demo: (soon)

**Note:** In this tutorial a customer system was built with some pre-existing password-based users. Have a look at our [docs](https://docs.corbado.com/integrations/web-component/no-existing-user-base) to see the changes you have to make if you don't have any users yet.

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

> :warning: **If you are using a windows machine**: Make sure to execute `git config --global core.autocrlf false` before cloning this repository to prevent git from changing the line endings of the bash scripts. (Docker will not be able to find the scripts if git does this)

### 2.1. Prerequisites

Please follow steps 1-3 on our [Getting started](https://docs.corbado.com/overview/getting-started) page to create and configure a project in the [developer panel](https://app.corbado.com).

### 2.2. Configure environment variables

Use the values you obtained in step 2.1. to configure the following variables inside `.env`:
1. **PROJECT_ID**: The project ID.
2. **API_SECRET**: The API secret.
3. **CLI_SECRET** Your individual ngrok token. [What is an ngrok token and how can I get one?](https://docs.corbado.com)

### 2.3. Start Docker containers

**Note:** Before continuing, please ensure you have [Docker](https://www.docker.com/products/docker-desktop/) installed and accessible from your shell.

Use the following command to start the system:
```
docker compose up
```
**Note:** Please wait until all containers are ready. This can take some time. Once the system is ready, a custom ngrok url will be opened in your default browser. (This url is only valid until the containers are stopped).

If your browser does not open by itself, you can find the ngrok url inside the 'projectconfigurator' container's logs:

![image](https://user-images.githubusercontent.com/23581140/210551918-d6f537ea-0271-4036-b6e8-f521994ff2fa.png)

### 2.4. Error check (optional)

To verify that your instance is running without errors enter `http://localhost:8000/ping` in your browser. If "pong" is displayed, everything worked. Entering your ngrok URL with `/ping` as path (e.g. `https://a9f7-212-204-96-162.eu.ngrok.io/ping`) should display "pong" as well:

![image](https://user-images.githubusercontent.com/23581140/208480558-c1bcde88-164e-4a22-97de-240fd93af4c1.png)

## 3. Usage

After step 2.3. your local server should be fully working.

### 3.1. Test authentication

If you now visit your ngrok URL you should be forwarded to the `/login` page:

![image](https://user-images.githubusercontent.com/23581140/208479745-4dc9acaa-cc43-4324-bfd4-ad2ecf0f7901.png)

You can login with one of the existing accounts or sign-up yourself.

| Name | Email | Password |
| --- | --- | --- |
| demo_user | demo_user@company.com | demo12 |
| max | max@company.com | maxPW |
| john | john@company.com | 123456 |

When authenticated you will be forwarded to the home page:

![image](https://user-images.githubusercontent.com/23581140/208479917-e82f06a9-98d1-406d-89d5-aaceb6bdbb2b.png)

### 3.2. View all users

On [localhost:8081](http://localhost:8081) a PHPMyAdmin instance is running where you can view all registered users:

![image](https://user-images.githubusercontent.com/23581140/208480126-65f84460-8914-40e8-a964-ac48bfdeec2f.png)

