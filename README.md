# Complete integration sample for the Corbado web component
This is a sample implementation of frontend and backend where the Corbado web component is integrated.

## File structure
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

## How to use
>**Warning**
>This sample code corresponds to the integration tutorial in our docs, please read it first in order to understand the flows and business logic!

### Prerequisites
The only thing you need is a CNAME which points to `auth.corbado.com`. We will use `auth.your-company.com` in this tutorial. More info on what a CNAME is and why it is needed can be found in our [docs](https://docs.corbado.com/integrations/web-component#1.-define-cname).

### Corbado developer panel settings

### Setup
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

Entering the url which is inside the red rectangle with `/ping` as path should now display "pong" as well as this ngrok url just forwards requests to your local instance.

Congrats! You're set!


### Once it's running
