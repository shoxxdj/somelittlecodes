#!/bin/sh
clear
echo	"Volatility Password Recover Easy Mode 0.1"
echo 	"ShoxX's Script" 

hivelist=hivelist
hashdump=hashdump

        echo "Entrez le nom du fichier dump :"
        read fichier
        python vol.py -f ./$fichier imageinfo
        echo "Entrez le profil choisit :"
        read profile
  	python vol.py -f ./$ficher --profile $profile $hivelist
	echo "Entrez l'adresse de registery Machine system"
	read y
	echo "Entrez l'adresse de systemRoot system32 config SAM"
	read s
	python vol.py -f ./$ficher --profile $profile $hashdump -y $y -s $s > dumplog.txt
	echo "Un fichier dumplog.txt a ete cree"

	

