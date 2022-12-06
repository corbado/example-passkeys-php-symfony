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
