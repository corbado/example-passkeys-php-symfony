import requests

import time




if __name__ == "__main__":

    # Do stuff with your driver
    print("Making request...")

    response = requests.get("http://ngrok:4551/api/tunnels")
    print("response")
    print(response.status_code)
    print(response.json())