# Complete integration sample for the Corbado web component
This is a sample implementation of frontend and backend where the Corbado web component is integrated.

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
    │   └── login.html.twig             # Login page which contains the Corbado web component
    └── ...

## 2. How to use
>**Warning**
>This sample code corresponds to the integration tutorial in our docs, please read it first in order to understand the flows and business logic!

### 2.1. Prerequisites
The only thing you need is a CNAME which points to `auth.corbado.com`. We will use `auth.your-company.com` in this tutorial. More info on what a CNAME is and why it is needed can be found in our [docs](https://docs.corbado.com/integrations/web-component#1.-define-cname).

### 2.2. Corbado developer panel settings

>**Warning**
>Remember to always press "Save changes" after entering details in the Corbado developer panel!
>
#### 2.2.1. Configure CNAME

In the developer panel under `Project settings -> Web component` enter the CNAME you previously created. 
![image](https://user-images.githubusercontent.com/23581140/205950309-f6f622e5-94ca-4413-9384-d7a2605da75d.png)

#### 2.2.2. Authorize origins
Inside the developer panel under `Project settings -> REST API` you need to enter the following origins in order to allow them to share resources.
1. `https://auth.your-company.com`, the CNAME which represents the Corbado web component
2. `http://localhost:8000`, which is where your backend instance is running
3. Your ngrok URL which connects your local backend to the internet

![image](https://user-images.githubusercontent.com/23581140/205950485-6285d536-d676-4382-a23c-c3c0bbfe3de4.png)

### 2.3. Setup
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

You should now be able to run this demo by typing `symfony server:start` into your console while being located in the same folder that contains this README file. If the setup was done correctly the following messages should appear in your terminal:
![image](https://user-images.githubusercontent.com/23581140/205909459-7ed3d679-b313-40d3-85be-1178b80a1594.png)
To verify that your instance is running without errors enter `http://localhost:8000/ping` into your browser. If "pong" is displayed you can continue with the next step.


The endpoints of your local system have to be public so Corbado can send requests there. To make your local instance publicly availbale we use [ngrok](https://ngrok.com/download). It can be installed using:
```
curl -s https://ngrok-agent.s3.amazonaws.com/ngrok.asc | sudo tee /etc/apt/trusted.gpg.d/ngrok.asc >/dev/null && echo "deb https://ngrok-agent.s3.amazonaws.com buster main" | sudo tee /etc/apt/sources.list.d/ngrok.list && sudo apt update && sudo apt install ngrok
```

You can start your ngrok instance by typing `ngrok http 8000`. In your terminal you should see the following:
![image](https://user-images.githubusercontent.com/23581140/205919914-986f95ea-7c32-4501-a651-f47b16e3b2e2.png)

Entering the url which is inside the red rectangle with `/ping` as path should now display "pong" as well since this ngrok url just forwards requests to your local instance.

### 2.4. Fill in your backend endpoints

In the developer panel under `Project settings -> Web component` configure the endpoints as seen in the following image, but use your own ngrok URL (the paths stay the same).
```
<your-ngrok-url>/api/sessionToken
<your-ngrok-url>/api/loginInfo
<your-ngrok-url>/api/passwordVerify
```
In our case the ngrok url was `https://d15e-212-204-96-162.eu.ngrok.io`
![image](https://user-images.githubusercontent.com/23581140/205945743-207cd062-bb41-4b3c-af0c-cb13bf279f9c.png)

### 2.5. Configure .env file

At the top level of this repository you will find the [.env file](https://github.com/corbado/widget-complete-tutorial/blob/master/.env). In there you need to set the following variables:
1. **CNAME**: Your cname which is "auth.your.company.com" in this tutorial
2. **PROJECT_ID**: your projectId which can be found on the top right of the dev panel (pro-xxxxxxxxxx)
3. **API_SECRET**: your apiSecret which can be created on the `API credentials` page
4. **NGROK_URL** Your ngrok URL which you received a few steps before, which in our case was `https://d15e-212-204-96-162.eu.ngrok.io`

Congrats, you're set! Once you restart your symfony server the authentication process should be fully operational.

### 2.6. Once it's running

If you now go to your ngrok url you should be forwarded to the `/login` page. There you can sign up / sign in and if authenticated you will be forwarded to the homepage. Have fun!
