#!/usr/bin/python
#######################
#This code is free !  #
#www.shoxx-website.com#
#######################
# Requirements        #
# Python-requests     #
# Python-json         #
#######################
import requests
import json

page=raw_input("Entrez le nom de la page Facebook : ")
tableau = requests.get('https://graph.facebook.com/'+str(page)).json()
print ("La page a %s Followers" % tableau["likes"])