# Easy Rot Script
# Rotate strings - ShoxX - Copyleft www.shoxx-website.com

import string

chaine=''

def make_rot_n(n):
 lc = string.lowercase
 trans = string.maketrans(lc, lc[n:] + lc[:n])
 return lambda s: string.translate(s, trans)


for x in xrange(1,27):
	rot= make_rot_n(x)
	print(rot(chaine))
	pass
