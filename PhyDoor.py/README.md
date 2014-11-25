#<u><center>PhyDoor.py</center></u>
####Php Backdoor Generator in Python

#####<u>Pourquoi ?</u>
Le but de ce projet est d'offrir la possibilité de génerer des backdoors PHP rapidement. Tout en offrant une interface de controle.

####phydoor.py
Phydoor.py est le génerateur de backdoor.
Options disponibles : 
<table>
<tr><th>Action</th><th>Option</th><th>Argument</th></tr>
<tr><td>Affiche le code dans le shell</td><td>--plain</td><td>/</td></tr>
<tr><td>Enregistre le code dans un fichier</td><td>--file</td><td>path/to/file/filename.php</td></tr>
<tr><td>Encode la backdoor</td><td>--encode</td><td>none,base64</td></tr>
<tr><td>Utilise des tags PHP différents de <?php ?></td><td>--no-classics-tags</td><td>/</td></tr>
<tr><td>Actions a ajouter dans la backdoor</td><td>--stuff</td><td>shell_system,shell_nosystem,scandir</td></tr>
</table>

####attacker_stuff.py
Attacker_stuff.py est l'interface de controle de la backdoor.
Vous devez simplement remplir les champs ip,port avec vos données locales puis adresse_backdoor ( obvious :D ).

Options disponibles : 
<table>
<tr><th>Action</th><th>Option</th><th>Argument</th></tr>
<tr><td>Affiche le code dans le shell</td><td>--plain</td><td>/</td></tr>
<tr><td>Action a executer</td><td>--action</td><td>shell,scandir</td></tr>
<tr><td>Parametres de l'action</td><td>--param</td><td>Depend on action!</td></tr>
</table>
