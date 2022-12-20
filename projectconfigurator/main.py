import requests
import os


if __name__ == "__main__":

    response = requests.get("http://ngrok:4551/api/tunnels")
    tunnels = response.json()['tunnels']
    ngrokUrl:str = tunnels[0]['public_url']

    if ngrokUrl.startswith("http"):
        ngrokUrl = ngrokUrl.replace("http", "https", 1)

    print("ngrokUrl: ", ngrokUrl)
    print("Configuring project...")

    session = requests.Session()
    session.auth = (os.environ['PROJECT_ID'], os.environ['API_SECRET'])

    projectConfigBody = {
    "externalName": "Local Test",
    "emailFrom": "localtest@corbado.com",
    "smsFrom": "Corbado Localtest",
    "externalApplicationUsername": os.environ['HTTP_BASIC_AUTH_USERNAME'],
    "externalApplicationPassword": os.environ['HTTP_BASIC_AUTH_PASSWORD'],
    "legacyAuthMethodsUrl": ngrokUrl + "/api/loginInfo",
    "passwordVerifyUrl": ngrokUrl + "/api/passwordVerify",
    "authSuccessRedirectUrl": ngrokUrl + "/api/sessionToken",
    "allowUserRegistration": True
    }
    resp = session.post("https://api.corbado.com/v1/projectConfig", json=projectConfigBody)
    print(resp.json())


    print("Authorizing localhost...")
    localhostOriginBody = {
        "name": "Localhost",
        "origin": "http://localhost:8000",
    }
    resp = session.post("https://api.corbado.com/v1/webauthn/settings", json=localhostOriginBody)
    print(resp.json())


    print("Authorizing ngrok...")
    ngrokOriginBody = {
        "name": "Ngrok",
        "origin": ngrokUrl,
    }
    resp = session.post("https://api.corbado.com/v1/webauthn/settings", json=ngrokOriginBody)
    print(resp.json())


    print("Done!")
    print("YOUR NGROK URL: ", ngrokUrl)