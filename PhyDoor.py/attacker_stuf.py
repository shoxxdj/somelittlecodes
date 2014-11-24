import requests
import socket
import time
import thread


def send_command():
	headers={'CMD':'ls'}
	r =requests.get('http://localhost/lala.php',headers=headers)
	print r.text

def listen():
	s = socket.socket(socket.AF_INET,socket.SOCK_STREAM)
	s.connect(("localhost",1223))
	data = s.recv(1024)
	s.close()
	print data

thread.start_new_thread(send_command())
thread.start_new_thread(listen())


