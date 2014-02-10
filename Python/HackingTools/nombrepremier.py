print ("Verificateur nombre premier - ShoxX")
nombre = input("entrez un nombre superieur a 2       ")

diviseur=2
premier=1
stop=0

while (diviseur<nombre and stop==0):
      if (nombre%diviseur==0):
            stop=1
      diviseur+=1

if stop==1:
      print("Pas premier ..")
else:
      print('Premier')
