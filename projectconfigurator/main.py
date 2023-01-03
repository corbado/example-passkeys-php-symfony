import requests
import os


if __name__ == "__main__":

    # Get ngrok url
    response = requests.get("http://ngrok:4551/api/tunnels")
    tunnels = response.json()['tunnels']
    ngrokUrl:str = tunnels[0]['public_url']

    if ngrokUrl.startswith("http:"):
        ngrokUrl = ngrokUrl.replace("http:", "https:", 1)

    print("Detected ngrokUrl: ", ngrokUrl)

    session = requests.Session()
    session.auth = (os.environ['PROJECT_ID'], os.environ['API_SECRET'])

    os.environ['NGROK_URL']=ngrokUrl

    # Notify Symfony
    print("Notifying symfony...")
    resp = session.get("http://symfony:80/api/ngrokUrl?url=" + ngrokUrl)

    # Configure project
    print("Configuring project...")
    projectConfigBody = {
    "externalName": "Local Test",
    "emailFrom": "localtest@corbado.com",
    "smsFrom": "Corbado Localtest",
    "externalApplicationProtocolVersion": "v2",
    "webhookUsername": os.environ['WEBHOOK_USERNAME'],
    "webhookPassword": os.environ['WEBHOOK_PASSWORD'],
    "webhookURL": ngrokUrl + "/api/corbado",
    "authSuccessRedirectUrl": ngrokUrl + "/api/sessionToken",
    "allowUserRegistration": True
    }
    resp = session.post("https://api.corbado.com/v1/projectConfig", json=projectConfigBody)
    print(resp.json())

    # Authorize origin localhost
    print("Authorizing localhost...")
    localhostOriginBody = {
        "name": "Localhost",
        "origin": "http://localhost:8000",
    }
    resp = session.post("https://api.corbado.com/v1/webauthn/settings", json=localhostOriginBody)
    print(resp.json())

    # Authorize origin ngrok
    print("Authorizing ngrok...")
    ngrokOriginBody = {
        "name": ngrokUrl,
        "origin": ngrokUrl,
    }
    resp = session.post("https://api.corbado.com/v1/webauthn/settings", json=ngrokOriginBody)
    print(resp.json())


    print("Done!")
    print("############################################################")
    print("# YOUR NGROK URL: ", ngrokUrl + " #")
    print("############################################################")