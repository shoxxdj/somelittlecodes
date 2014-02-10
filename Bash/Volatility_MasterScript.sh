#!/bin/bash
quitter=2
1=envars
2=pslist



clear
echo "                                                          
      _/_/_/  _/                            _/      _/   
   _/        _/_/_/      _/_/    _/    _/    _/  _/      
    _/_/    _/    _/  _/    _/    _/_/        _/         
       _/  _/    _/  _/    _/  _/    _/    _/  _/        
_/_/_/    _/    _/    _/_/    _/    _/  _/      _/ "


echo " Volatility Framework Easy mode"
echo "Connaissez vous votre dump ? "
echo "1) Oui 2) Non"
read connait
case $connait in
	"1")

	echo " Entrez le nom du fichier dump"
	read fichier
	echo "Entrez le profil"
	read profile
	;;
	"2")

	echo "Entrez le nom du fichier dump :"
	read fichier
	python vol.py -f ./$fichier imageinfo
	echo "Entrez le profil choisit :"
	read profile
	;;
	*)
	echo "lala"
	;;
esac
#
# ici on  vas boucler pour savoir si l'utilisateur a encore besoin de nos services
#
echo "action choisie :"
echo "1) Voir variables d'environements"
echo "2) Voir liste des processus"
#
# liste a completer ainsi que le ca se
#

read choix
case $choix in
	"1")
	action=envars
	;;
	"2")
	action=pslist
	;;
	# ajouter les actions aussi ici
	*)
	echo fail
	;;
esac
	echo "Choix de l'affichage"
	echo "1 dans une console"
	echo "2 dans un fichier texte poney.txt"
	read affichage
	case $affichage in
		"1")
		python vol.py -f ./$fichier --profile=$profile $action
		;;
		"2")
		python vol.py -f ./$fichier --profile=$profile $action > poney.txt
		;;
		*)
		echo "fail"
	esac

	echo "Voulez vous tenter une nouvelle action ? "
	echo "1) Oui 2) Non"
	read quitter
