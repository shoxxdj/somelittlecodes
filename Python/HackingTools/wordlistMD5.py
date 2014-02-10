import hashlib

objectif=input("Entrez le hash cible :  ");
f = input("Nom du dictionnaire :  ") #Fichier dictionnaire


success = False
for word in open(f):
    print (word)
    test=hashlib.md5(word.encode('utf-8').strip()).hexdigest()
    if(test==objectif):
        success = True 
        break

if success:
    print (objectif,"Correspond a : ",word)
else:
    print ("Pas dans le dictionnaire")
