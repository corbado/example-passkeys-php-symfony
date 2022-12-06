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

### Corbado developer panel settings

### Setup
To start the local development server your system requires [PHP](https://www.php.net/manual/en/install.php) and [Symfony](https://symfony.com/download) e.g. using brew:
```
brew install php
brew install symfony-cli/tap/symfony-cli
```

You then need to install composer:
```
curl -sS https://getcomposer.org/installer | php
```
to be able to install all required packages for this project:
```
sudo -E env "PATH=$PATH" php composer.phar install
```

You should now be able to run this demo by typing `symfony server:start` into your console while being located in the same folder that contains this README file. If the setup was done correctly the following messages should appear in your terminal:![image](https://user-images.githubusercontent.com/23581140/205909459-7ed3d679-b313-40d3-85be-1178b80a1594.png)


The endpoints of your local system must be entered into the developer panel and thus have to be public. To make your local instance publicly availbale we use [ngrok](https://ngrok.com/download). It can be installed using:
```
curl -s https://ngrok-agent.s3.amazonaws.com/ngrok.asc | sudo tee /etc/apt/trusted.gpg.d/ngrok.asc >/dev/null && echo "deb https://ngrok-agent.s3.amazonaws.com buster main" | sudo tee /etc/apt/sources.list.d/ngrok.list && sudo apt update && sudo apt install ngrok
```

### Once it's running
