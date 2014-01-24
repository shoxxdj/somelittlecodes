import re
from collections import Counter

def char_frequency(string):
	frequency={ z : round(a*100.0/len(string),3) for z, a in Counter(string).iteritems() }
	return Counter(frequency)

txt= re.sub(r"\W","","""YOUR TEXT HERE""")

print char_frequency(txt)