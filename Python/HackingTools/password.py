import random
import sys

if(len(sys.argv)<2):
	print "Syntax : password.py [int]"
	print "For a good security use at least 8 characters"
	sys.exit(2)

password=""
for i in range(0,int(sys.argv[1])):
	r = random.randint(32,125)
	password= "%s%s" % (password,chr(r))

print password
