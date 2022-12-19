# Complete integration sample for the Corbado web component
This is a sample implementation of frontend and backend where the Corbado web component is integrated. You can see the live demo [here](TODO: Link einfügen wenn online)

**Note:** In this tutorial a customer system was built with some preexisting password-based users. Have a look at our [docs](TODO:Link) to see the changes you have to make if you don't have any users yet. (Gets even easier then)

## 1. File structure
    .
    ├── ...
    ├── config                        
    │   └── routes.yaml                 # Assigns paths to controller methods    
    ├── docker                        
    │   └── .env                        # Contains all docker environment variables   
    ├── src                             
    │   ├── Controller                  
    │   │   ├── BackendController.php   # Manages endpoints for backend
    │   └── └── FrontendController.php  # Manages endpoints for frontend
    ├── templates                     
    │   ├── home.html.twig              # Home page which you only get to see if you are logged in
    │   └── login.html.twig             # Login page which contains the Corbado web component; Acts as landing page if you are not logged in
    └── ...

## 2. Setup
>**Warning**
>This sample code corresponds to our [web component integration tutorial](TODO: Link einfügen wenn online), please read it first in order to understand the flows and business logic!

### 2.1. Prerequisites

Please create a free account as well as a project inside our [developer panel](https//app.corbado.com) according to our [docs](TODO:Link). You will need the project's ID, API secret and individually generated CNAME in the next steps.

### 2.2. Setup ngrok

Please use our [guide](Link to docs) to configure the reverse proxy service ngrok.

### 2.3. Configure Corbado project
Please navigate to our configure your Corbado project as shown [here](https://app.corbado.com).

### 2.4. Start local server

#### 2.4.1. Configure environment variables

Inside /docker/.env you have to configure the following variables:
1. **CNAME**: The individually generated CNAME of step 2.1.
2. **PROJECT_ID**: The project ID of step 2.1.
3. **API_SECRET**: The API secret of step 2.1.
4. **NGROK_URL** Your ngrok URL which you received in step 2.2. (in our case `https://eb70-212-204-96-162.eu.ngrok.io`)
5. (Optional) **HTTP_BASIC_AUTH_USERNAME**: If you change the username here, you also have to enter the new value into the developer panel, as seen in 2.4.4.
5. (Optional) **HTTP_BASIC_AUTH_PASSWORD**: If you change the password here, you also have to enter the new value into the developer panel, as seen in 2.4.4.

#### 2.4.2. Start docker containers

**Note:** Before continuing, please ensure you have [Docker](https://www.docker.com/products/docker-desktop/) installed and accessible from your shell.

Use the following command to start the system:
```
docker compose up -d
```

To verify that your instance is running without errors enter `http://localhost:8000/ping` into your browser. If "pong" is displayed, everything worked. Entering your ngrok url with `/ping` as path (e.g. `https://eb70-212-204-96-162.eu.ngrok.io/ping`) should display "pong" as well.

### 3. Usage

After step 2.4.2. your local server should be fully funcional.

If you now go to your ngrok URL you should be forwarded to the `/login` page:

![image](https://user-images.githubusercontent.com/23581140/206202277-80ea9af6-c2de-456a-abed-febc622be291.png)

You can login with one of the existing accounts or sign-up yourself.

| Name | Email | Password |
| --- | --- | --- |
| demo_user | demo_user@company.com | demo12 |
| max | max@company.com | maxPW |
| john | john@company.com | 123456 |

When authenticated you will be forwarded to the homepage:

![image](https://user-images.githubusercontent.com/23581140/206202557-87be3808-9e76-444d-a9ff-229e19bdd61e.png)
