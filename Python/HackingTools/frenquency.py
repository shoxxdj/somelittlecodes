import re
from collections import Counter

def char_frequency(string):
	frequency={ z : round(a*100.0/len(string),3) for z, a in Counter(string).iteritems() }
	return Counter(frequency)

txt= re.sub(r"\W","","""Ia t iooe "so vt sft. i.,thehst s fs  ho ihry To Fyepasoaoohr"oore  uiufe nru  pclg   yo  cyrhdhhbmotywhooa taaau oiaublb vsgrnull llefeii orlldee o ccet  eomnfrd  f egnn gawocpfwfeg'weianrhorfteti.rreyrroo ! t ld patnra.hhIy ltssgtn
""")

print char_frequency(txt)
