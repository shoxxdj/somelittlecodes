import argparse


txt_to_cube={"a":"R2UR'U'RU'R'MBM'","b":"R2UR'U'RU'R'L'2ULU'L'UL","c":"EBS'E'","d":"RB2R'BREMEMEMEM","e":"EBE'","f":"L'DLEBE'","g":"EMEMEMEMRBR'","h":"M'B2MB'M","i":"EB2E'B'E","j":"M'SB'M'L'B'L","k":"M'B2MB'M'EB'","l":"M'RSB'MR'","m":"MBM'","n":"MSBM'","o":"EMEMEMEM","p":"L'DL","q":"EMEMEMEMR'BR","r":"L'EDL","s":"L'B'SLR'BS'R","t":"LR'BS'RL'","u":"M'B'SM","v":"M'BS'MLR'BRL'","w":"M'B'M","x":"2R2L2E","y":"M'BMLR'BL'R","z":"LBS'L'RBS'"}
cube_to_txt={"R2UR'U'RU'R'MBM'":"a","R2UR'U'RU'R'L'2ULU'L'UL":"b","EBS'E'":"c","RB2R'BREMEMEMEM":"d","EBE'":"e","L'DLEBE'":"f","EMEMEMEMRBR'":"g","M'B2MB'M":"h","EB2E'B'E":"i","M'SB'M'L'B'L":"j","M'B2MB'M'EB'":"k","M'RSB'MR'":"l","MBM'":"m","MSBM'":"n","EMEMEMEM":"o","L'DL":"p","EMEMEMEMR'BR":"q","L'EDL":"r","L'B'SLR'BS'R":"s","LR'BS'RL'":"t","M'B'SM":"u","M'BS'MLR'BRL'":"v","M'B'M":"w","2R2L2E":"x","M'BMLR'BL'R":"y","LBS'L'RBS'":"z"}

parser = argparse.ArgumentParser()
parser.add_argument("--tocube","-t",action="store_true",dest="to_cube",help="Plain Text -> Cube text")
parser.add_argument("--input","-i",action="store",dest="input",help="Your value to transform")
parser.add_argument("--totext","-p",action="store_true",dest="to_text",help="Cube text -> Plain Text")
parser.add_argument("--verbose","--v","-v",action="store_true",dest="verbose",help="Verbose mode")
#parser.add_argument("--help","-h",action="store_true",dest="explain",help="Explain how to use this code")

args = parser.parse_args()

def textToCube(text):
	res =""
	for i in text:
		if((ord(i)>64 and ord(i)<91) or(ord(i)>96 and ord(i)<123)):
			res = "%s%s%s"%(res,",",txt_to_cube[i.lower()])
	return res[1:]

def cubeToText(cube):
	cube=cube.split(',')
	res=""
	for i in cube:
		res="%s%s"%(res,cube_to_txt[i])
	return res

def explain():
	print "Why use this code ? \r\n Rubki's cube is so awsome ! \r\n But Who knows that it can be used for cryptography ? \r\n Based on this alphabet : \r\n Just send to your destinator "


if(args.input):
	if(args.verbose):
		print "Your input : "+str(args.input)
	if(args.to_cube):
		if(args.verbose):
			print "You asked from Plain text to Cube, result is : "
		print textToCube(args.input)
	if(args.to_text):
		if(args.verbose):
			print "You asked from Cube to Plain text, result is :"
		print cubeToText(args.input)
