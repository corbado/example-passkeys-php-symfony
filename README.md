# Complete integration sample for the Corbado web component
This is a sample implementation of frontend and backend where the Corbado web component is integrated. You can see the live demo [here](TODO: Link einfügen wenn online)

>**Warning**
>In this tutorial a customer system is built with no preexisting user base, which is effecively realized by having the loginInfo endpoint always return a 404 code when given a username. In case you have an existing user base, you just need to modify this endpoint to return other status codes as well, as described in our [docs](TODO: Link einfügen wenn online)

## 1. File structure
    .
    ├── ...
    ├── config                        
    │   └── routes.yaml                 # Assigns paths to controller methods    
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

### 2.1. CNAME
The only thing you need to create is a CNAME which points to `auth.corbado.com`. We will use `auth.your-company.com` in this tutorial. More info on what a CNAME is and why it is needed can be found in our [docs](https://docs.corbado.com/integrations/web-component#1.-define-cname).

### 2.2. Setup

>**Warning**
>All commands listed in this tutorial can be executed in linux/mac. If you use windows or have trouble installing, just klick on the name of whatever you wanted to install. This should lead you to the download-page/installation guide of that program/software.

#### 2.2.1 Clone repository
Download the repository code by executing `git clone https://github.com/corbado/widget-complete-tutorial.git`.

#### 2.2.2. PHP Symfony
To start the local development server your system requires [PHP](https://www.php.net/manual/en/install.php) and [Symfony](https://symfony.com/download):
```
brew install php
brew install symfony-cli/tap/symfony-cli
```

You then need to install [Composer](https://getcomposer.org/download/):
```
curl -sS https://getcomposer.org/installer | php
```
With Composer you should be able to install all required packages for this project:
```
sudo -E env "PATH=$PATH" php composer.phar install
```

You should now be able to run this demo by typing `symfony server:start` into your console while being located in the root directory of this repository. If the setup was done correctly, the following messages should appear in your terminal:
![image](https://user-images.githubusercontent.com/23581140/205909459-7ed3d679-b313-40d3-85be-1178b80a1594.png)
To verify that your instance is running without errors enter `http://localhost:8000/ping` into your browser. If "pong" is displayed, you can continue with the next step.

#### 2.2.3. Ngrok

The endpoints of your local system have to be public so Corbado can send requests there. To make your local instance publicly availbale we use [ngrok](https://ngrok.com/download) which is a reverse proxy service. It assigns you a globally available URL and forwards all incoming requests to your local instance. It can be installed using:
```
curl -s https://ngrok-agent.s3.amazonaws.com/ngrok.asc | sudo tee /etc/apt/trusted.gpg.d/ngrok.asc >/dev/null && echo "deb https://ngrok-agent.s3.amazonaws.com buster main" | sudo tee /etc/apt/sources.list.d/ngrok.list && sudo apt update && sudo apt install ngrok
```

Since symfony in default launches its server on port 8000, we will use the ngrok service on that port. You can start your ngrok instance on port 8000 by typing `ngrok http 8000`. In your terminal you should see the following:
![image](https://user-images.githubusercontent.com/23581140/205919914-986f95ea-7c32-4501-a651-f47b16e3b2e2.png)

Entering the URL which is inside the red rectangle with `/ping` as path (In our case `https://d15e-212-204-96-162.eu.ngrok.io/ping`) should now display "pong" as well since this ngrok URL just forwards requests to your local instance.


### 2.3. Corbado developer panel settings

>**Warning**
>Remember to always press "Save changes" after entering details in the Corbado developer panel!
>
#### 2.3.1. Configure CNAME

In the developer panel under `Project settings -> Web component` enter the CNAME you previously created. 
>**Warning**
>It can take up to 5 minutes until our system has registered your CNAME

![image](https://user-images.githubusercontent.com/23581140/205950309-f6f622e5-94ca-4413-9384-d7a2605da75d.png)

#### 2.3.2. Authorize origins
Inside the developer panel under `Project settings -> REST API` you need to enter the following origins in order to have them whitelisted for your Corbado project.
1. `https://auth.your-company.com`, the CNAME which points to auth.corbado.com
2. `http://localhost:8000`, which is where your local symfony server hosting this sample application is running
3. Your ngrok URL (in our case `https://d15e-212-204-96-162.eu.ngrok.io`) which connects your local application to the internet

![image](https://user-images.githubusercontent.com/23581140/205950485-6285d536-d676-4382-a23c-c3c0bbfe3de4.png)

#### 2.3.3. Fill in your backend endpoints

In the developer panel under `Project settings -> Web component` configure the endpoints as seen in the following image, but use your own ngrok URL (the paths stay the same).
```
<your-ngrok-url>/api/sessionToken
<your-ngrok-url>/api/loginInfo
<your-ngrok-url>/api/passwordVerify
```
In our case the ngrok URL was `https://d15e-212-204-96-162.eu.ngrok.io`
![image](https://user-images.githubusercontent.com/23581140/205945743-207cd062-bb41-4b3c-af0c-cb13bf279f9c.png)

#### 2.3.4. Configure basic auth

The loginInfo and sessionToken endpoints should only be accessible via HTTP basic auth. In your own projects you can come up with your own username and password which have to be entered into the developer panel under `Project settings -> Web component`. In this sample implementation we predefined `basicusername` and `basicpassword` as credentials, so these are the values you have to enter:
![image](https://user-images.githubusercontent.com/23581140/205995437-34a838e9-10e5-446d-817b-8d9005a3d764.png)

### 2.4. Configure .env file

At the top level of this repository you will find the [.env file](https://github.com/corbado/widget-complete-tutorial/blob/master/.env). In there you need to set the following variables:
1. **CNAME**: Your cname which is "auth.your-company.com" in this tutorial
2. **PROJECT_ID**: your project ID which can be found on the top right of the developer panel (pro-xxxxxxxxxx)
3. **API_SECRET**: your API secret which can be created on the `API credentials` page
4. **NGROK_URL** Your ngrok URL which you received in step 2.2.2. (in our case `https://d15e-212-204-96-162.eu.ngrok.io`)
5. (Optional) **HTTP_BASIC_AUTH_USERNAME**: If you change the username here, you also have to enter the new value into the developer panel, as seen in 2.3.4..
5. (Optional) **HTTP_BASIC_AUTH_PASSWORD**: If you change the password here, you also have to enter the new value into the developer panel, as seen in 2.3.4..

### 2.5. Re-Run the application

Since everything is configured now, you only need to restart your local symfony server (Press `Ctrl+C` to stop and then enter `symfony server:start` to start the server again) before the authentication process should be fully operational.

If you now go to your ngrok URL you should be forwarded to the `/login` page:
![image](https://user-images.githubusercontent.com/23581140/206160273-5e0b5936-84e1-42cf-9830-4cfc3c650334.png)

Now you can sign-up / login and if authenticated you will be forwarded to the homepage:
![image](https://user-images.githubusercontent.com/23581140/206160108-590c1934-5e67-464a-a73e-77300733c205.png)


