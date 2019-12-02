<?php

// cette page permet : 
	// d'affiche une liste des épreuves existantes
	// de visualiser une épreuve existante

if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant')
{
	include('objets/autoloader.php');	//autoloader de classe
	include('coBdd.php');		//connection a la bdd
	
	$manager = new Manager($bdd);
	
	// si on a selectionné une épreuve à afficher on l'affiche 
	if(!empty($_GET['e']))
	{
		$id = (int) $_GET['e'];
		$epr = $manager->getEpr($id);
		
		$epr->afficher();
		
		echo '<a href="dispacher.php?page=4" ><button>Retour à la liste des épreuves</button></a>';
	}
	else
	// on récupère l'ensemble des épreuves et on les affiche dans un formulaire
	{
		echo '<p id="affEpr">Bonjour, vous êtes connecté en tant que <strong>'.$_SESSION['prenomEnseignant'].' '.$_SESSION['nomEnseignant'].'</strong><br /><br />
		Voici la liste des épreuves existantes. Vous pouvez cliquer sur une épreuve pour en voir le contenu détaillé.</p>';
		
		$tabEpr = $manager->getAllEpr();
		$i = 0;
		
		foreach($tabEpr as $key => $value)
		{
			$value->shortEpr();
			$i++;
		}
		
		if ($i==0)
			echo '<p class="erreur"><strong>Erreur :</strong> il n\'y a pas d\'epreuves disponibles pour le moment.</p>';
	}
}
else
{
	header('Location: bye.php');
	exit();
}

?>