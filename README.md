# Complete integration sample for the Corbado web component
This is a sample implementation of frontend and backend where the Corbado web component is integrated. You can see the live demo [here](TODO: Link einfügen wenn online)

**Note:** In this tutorial a customer system was built with no preexisting user base, which is effecively realized by having the loginInfo endpoint always return a 404 code when given a username. In case you have an existing user base, you just need to modify this endpoint to return other status codes as well, as described in our [docs](https://docs.corbado.com/integrations/web-component/existing-user-base#2.-login-information)

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

## 2. How to use
>**Warning**
>This sample code corresponds to our [web component integration tutorial](TODO: Link einfügen wenn online), please read it first in order to understand the flows and business logic!

### 2.1. Prerequisites

#### 2.1.1. Create CNAME
Create a CNAME which points to `auth.corbado.com` as described [here](TODO: Link).

#### 2.1.2. Create a Corbado project.

Please create a project as well as an API secret for that project inside our [developer panel](https://app.corbado.com) as shown in our [docs](TODO: Link).
You will need the project ID (pro-xxxxxxxx) and your API secret in the next steps.

### 2.2. Setup ngrok

Please use our [guide](Link to docs) to configure the reverse proxy service ngrok which enables your local server to receive requests from the internet.
The local server (see next step) will be available at http://localhost:8000. Therefore you have to launch the ngrok service on port 8000 by entering `ngrok http 8000`.

### 2.3. Start local server

#### 2.3.1. Configure environment variables

Inside /docker/.env you have to configure the following variables:
1. **CNAME**: The CNAME you created in step 2.1.1.
2. **PROJECT_ID**: your project ID from step 2.1.2.
3. **API_SECRET**: your API secret from step 2.1.2.
4. **NGROK_URL** Your ngrok URL which you received in step 2.2. (in our case `https://eb70-212-204-96-162.eu.ngrok.io`)
5. (Optional) **HTTP_BASIC_AUTH_USERNAME**: If you change the username here, you also have to enter the new value into the developer panel, as seen in 2.3.4.
5. (Optional) **HTTP_BASIC_AUTH_PASSWORD**: If you change the password here, you also have to enter the new value into the developer panel, as seen in 2.3.4.

#### 2.3.2. Start docker container

**Note:** Before continuing, please ensure you have [Docker](https://www.docker.com/products/docker-desktop/) installed and accessible from your shell.

First you need to build the container:
```
docker build . -t corbado-webcomponent-example
```
After building you can execute the container:
```
docker run -p 8000:80 --env-file=docker/.env -it --rm corbado-webcomponent-example
```

To verify that your instance is running without errors enter `http://localhost:8000/ping` into your browser. If "pong" is displayed, everything worked. Entering your ngrok url with `/ping` as path (e.g. `https://eb70-212-204-96-162.eu.ngrok.io/ping`) should display "pong" as well.

### 2.4. Configure Corbado project
Please create a project in our [developer panel](https://app.corbado.com) first.

#### 2.4.1. Add CNAME to your project
Add the CNAME you created in step 2.1. to your Corbado project as described [here](TODO: Link).

#### 2.4.2. Set URLs of your endpoints
Info on how to configure the endpoints can be found [here](TODO: Link). The endpoints of this server are predefined and can be reached via your ngrok url. As a result the URLs you have to enter into the developer panel are:
- `<ngrok-url>/api/loginInfo` (e.g.: `https://eb70-212-204-96-162.eu.ngrok.io/api/loginInfo`)
- `<ngrok-url>/api/passwordVerify`
- `<ngrok-url>/api/sessionToken`

#### 2.4.3. Authorize origins
In the developer panel under `Project settings -> Web component` enter the CNAME you previously created. [Docs](TODO: Link). 

![image](https://user-images.githubusercontent.com/23581140/205950309-f6f622e5-94ca-4413-9384-d7a2605da75d.png)

#### 2.3.2. Authorize origins
Inside the developer panel under `Project settings -> REST API` you need to enter the following origins in order to have them whitelisted for your Corbado project.
1. `https://auth.your-company.com`, the CNAME which points to auth.corbado.com
2. `http://localhost:8000`, which is where your local symfony server hosting this sample application is running
3. Your ngrok URL (in our case `https://eb70-212-204-96-162.eu.ngrok.io`) which connects your local application to the internet

![image](https://user-images.githubusercontent.com/23581140/205950485-6285d536-d676-4382-a23c-c3c0bbfe3de4.png)

#### 2.3.3. Fill in your backend endpoints

In the developer panel under `Project settings -> Web component` configure the endpoints as seen in the following image, but use your own ngrok URL (the paths stay the same).
```
<your-ngrok-url>/api/sessionToken
<your-ngrok-url>/api/loginInfo
<your-ngrok-url>/api/passwordVerify
```
In our case the ngrok URL was `https://eb70-212-204-96-162.eu.ngrok.io`
![image](https://user-images.githubusercontent.com/23581140/205945743-207cd062-bb41-4b3c-af0c-cb13bf279f9c.png)

#### 2.3.4. Configure basic auth

The loginInfo and sessionToken endpoints should only be accessible via HTTP basic auth. In your own projects you can come up with your own username and password which have to be entered into the developer panel under `Project settings -> Web component`. In this sample implementation we predefined `basicusername` and `basicpassword` as credentials, so these are the values you have to enter:
![image](https://user-images.githubusercontent.com/23581140/205995437-34a838e9-10e5-446d-817b-8d9005a3d764.png)

### 2.4. Configure .env file

At the top level of this repository you will find the [.env file](https://github.com/corbado/widget-complete-tutorial/blob/master/.env). In there you need to set the following variables:

### 2.5. Re-Run the application

Since everything is configured now, you only need to restart your local symfony server (Press `Ctrl+C` to stop and then enter `symfony server:start` to start the server again) before the authentication process should be fully operational.

If you now go to your ngrok URL you should be forwarded to the `/login` page:
![image](https://user-images.githubusercontent.com/23581140/206202277-80ea9af6-c2de-456a-abed-febc622be291.png)

Now you can sign-up / login and if authenticated you will be forwarded to the homepage:
![image](https://user-images.githubusercontent.com/23581140/206202557-87be3808-9e76-444d-a9ff-229e19bdd61e.png)



