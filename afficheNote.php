<?php
// recoit un post
// enregistre les réponses de l'eleve dans la bdd
// on utilise un arbitre pour afficher la note

if(isset($_SESSION['login']) && $_SESSION['login'] == 'eleve')
{
	// si on à envoyé des réponses
	if(!empty($_POST['sent']))
	{
		include('objets/autoloader.php');	//autoloader de classe
		include('coBdd.php');		//connection a la bdd
		
		unset($_POST['sent']);
		
		$manager = New Manager($bdd);
		$arbitre = New Arbitre($bdd);
		
		$uei = date('Y-m-d');
		
		// pour chaque question on enregistre la réponse
		foreach($_POST as $key => $value)
		{
			$key = (int) $key;
			$value = (int) $value;
			
			$manager->addQcmRep(array(
									'idEpreuve' => $_SESSION['idEpreuve'],
									'idEleve' => $_SESSION['idEleve'],
									'idQuestion' => $key,
									'idReponse' => $value,
									'date' => $uei
									));
		}
		
		// calcul du score
		$resultat = $arbitre->verifRep($_POST);
		
		// on recupere le nombre de question de l'epreuve
		$max = $manager->getEprNbQue($_SESSION['idEpreuve']);
		
		// affichage des résultats
		?>
		<p class="confirme" >Vos réponses ont bien été enregistrées</p>
		
		<div class="startQcm">
			<fieldset>
				<legend>Resultats</legend>
				<p>Epreuve terminée, vous avez un score de <?php echo '<b>'.$resultat.'</b>/'.$max['max']; ?></p>
				
				<?php 
				// si il reste des épreuves non réalisées pour cette classe on affiche un lien vers selectEpr.php
				$epreuves = $manager->getListEpr($_SESSION['idClasse'], $_SESSION['idEleve'], $uei);
				
				if(!empty($epreuves))
					echo '<a href="dispacher2.php" ><button>Participer à une autre épreuve</button></a>';
				?>
				
				<a href="bye.php" ><button>Déconnexion</button></a>
			</fieldset>
		</div>
		<?php
	}
}
else
{
	header('Location: bye.php');
	exit();
}
?>