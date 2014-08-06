import requests
import json
import sys

if(len(sys.argv)<2):
	print "Need at least one IP !"
	print "Syntax : ip.py X.X.X.X Y.Y.Y.Y"
	sys.exit(2)

for ip in xrange(1,len(sys.argv)):
	r=requests.get('http://ipinfo.io/'+str(sys.argv[ip])+'/json').json()
	print "%s : %s => %s  / %s : %s" % (ip, sys.argv[ip],r['country'],r['city'],r['org'])
