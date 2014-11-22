import argparse
import base64
import sys

parser = argparse.ArgumentParser()
#Options de generation de la backdoor
#parser.add_argument("--generate", "-g", action="store_true", dest='generate', help="say a ! ")
#OUTPUT
parser.add_argument("--plain", "-p", action="store_true", dest="plain", help="Write the backdoor's code in your terminal")
parser.add_argument("--file", "-f", action="store", dest="file", help="Write the backdoor's code in the FILE file")
#Encode
parser.add_argument("--encode","-e", action="store", dest="encode", choices=['base64', 'none', '#'])
#Options to be included
parser.add_argument("--stuff",action="store",dest="stuff",help="Stuff to be included in the backdoor separed by comma\r\nPossibles values:shell_system,shell_nosystem")
#Identifiants
parser.add_argument("--login","-l",action="store",dest="login",help="Your Private backdoor login")
parser.add_argument("--password",action="store",dest="password",help="Your Private backdoor password")
#Version
parser.add_argument("--no-classics-tags",action="store_true",dest="tags",help="If selected backdoor will not implement classics php tags")
parser.add_argument("--version", action='version', version='%(prog)s ALPHA')
args = parser.parse_args()

def generate_backdoor(option):
	if(option=="shell_system"):
		return "echo 'lala';"
	if(option=="shell_nosystem"):
		return "echo 'WTF';"

#Let's write our backdoor ! 
backdoor=""


supported_stuff=['shell_system','shell_nosystem']

if(args.stuff):
	options=args.stuff.split(',')
	for option in options:
		if(option not in supported_stuff):
			sys.exit("Unsuported stuff "+str(option)+" Maybe you were searching for : "+str(supported_stuff))
			break
	for option in options:
		backdoor+=generate_backdoor(option)


#Encodage
if(args.encode):
	if(args.encode=="base64"):
		backdoor=base64.b64encode(backdoor)
		backdoor="eval(base64_decode('"+backdoor
		backdoor+="'))"
#Finaly End the backdoor generation
if(args.tags):
	backdoor='<script language="php"> '+backdoor
	backdoor+=' </script>'
else:
	backdoor='<? '+backdoor
	backdoor+='?>'

if(args.plain):
	print backdoor

if(args.file):
	fichier = open(str(args.file),"w")
	fichier.write(backdoor)
	fichier.close()
	print "Writed in "+str(args.file)
