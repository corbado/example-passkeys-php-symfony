# Complete integration sample for the Corbado web component in PHP with Symfony

This is a sample implementation of frontend and backend where the Corbado web component is integrated.

**Note:** In this tutorial an existing application is created with some pre-existing password-based users. Have a look
at our [docs](https://docs.corbado.com/integrations/web-component/no-existing-user-base) to see the integration if you
don't have any users yet. See also the [full blog post](https://www.corbado.com/blog/passkeys-php-symfony) to understand all the details.

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

> :warning: **If you are using a Windows machine**: Make sure to execute `git config --global core.autocrlf false`
> before cloning this repository to prevent git from changing the line endings of the bash scripts. (Docker will not be
> able to find the scripts if git does this)

### 2.1. Configure environment variables

#### Automatic Setup

You can download this repository directly from our [examples](https://app.corbado.com/app/getting-started/examples)
page, where all environment variables and other necessary parameters will be configured automatically. In that case, you
can skip the following manual setup step, and proceed to step 2.2.

#### Manual Setup

Please follow steps 1-3 on our [Getting started](https://docs.corbado.com/overview/getting-started) page to create and
configure a project in the [developer panel](https://app.corbado.com).

Copy `.env.example` to `.env` and use the values you obtained above to configure the following variables inside `.env`:

1. **PROJECT_ID**: The project ID.
2. **API_SECRET**: The API secret.
3. **CLI_SECRET**: The CLI secret.

### 2.2. Start Docker containers

**Note:** Before continuing, please ensure you have [Docker](https://www.docker.com/products/docker-desktop/) installed
and accessible from your shell.

Use the following command to start the system:

```
docker compose up
```

**Note:** Please wait until all containers are ready. This can take some time.

### 2.3. Error check (optional)

To verify that your instance is running without errors enter `http://localhost:19915/ping` in your browser. If "pong" is
displayed, everything worked.

<img width="1013" alt="Screenshot 2023-04-28 at 10 47 44 AM" src="https://user-images.githubusercontent.com/50023117/235101153-c99dd1ed-cefc-474f-8997-1fb4774a182e.png">

## 3. Usage

After step 2.3. your local server should be fully working.

### 3.1. Test authentication

If you now visit `http://localhost:19915`, you should be forwarded to the `/login` page:

<img width="834" alt="Screenshot 2023-04-28 at 10 42 10 AM" src="https://user-images.githubusercontent.com/50023117/235100435-9e727b16-8c65-4400-a987-a5a360a8c07c.png">

You can login with one of the existing accounts or sign-up yourself.

| Name      | Email                 | Password |
|-----------|-----------------------|----------|
| demo_user | demo_user@company.com | demo12   |
| max       | max@company.com       | maxPW    |
| john      | john@company.com      | 123456   |

When authenticated you will be forwarded to the home page:

<img width="833" alt="Screenshot 2023-04-28 at 10 41 40 AM" src="https://user-images.githubusercontent.com/50023117/235100667-dc11d62d-17f8-41e0-be0b-5942ebaa84d0.png">

### 3.2. View all users

On [localhost:8081](http://localhost:8081) a PHPMyAdmin instance is running where you can view all registered users:

<img width="1114" alt="image" src="https://user-images.githubusercontent.com/18458907/219000289-24cb9225-f226-43ef-85d9-6356b1f419a2.png">
