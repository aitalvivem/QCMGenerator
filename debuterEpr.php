<?php 
// cette page permet de débuter une epreuve pour une classe

if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant')
{
	include('objets/autoloader.php');	//autoloader de classe
	include('coBdd.php');		//connection a la bdd
	
	$manager = New Manager($bdd);
	
	// si un formulaire a été envoyé, on le traite
	if(!empty($_POST))
	{
		if(!empty($_POST['epreuve']) && !empty($_POST['classe']))
		{
			$idEpr = (int) $_POST['epreuve'];
			
			// on recupere l'epreuve
			$epr = $manager->getEpr($idEpr);
			
			// création d'un objet Qcm (sans nomEleve/prenomEleve)
			$qcm = New Qcm(array(
								'epreuve' => $epr,
								'classe' => $_POST['classe']
								));

			$manager->addQcm($qcm);
			
			$message = '<p class="confirme">L\'épreuve a débuté avec succès.</p>';
		}
		else
			$message = '<p class="erreur"><strong>Erreur :</strong> Vous devez renseigner un classe et une épreuve.</p>';
	}

	// on récupère la liste des épreuves
	$epr = $manager->getAllEpr();
	
	// on récupère la liste des classes
	$classes = $manager->getListClass();
	
	// si il y a un message a afficher
	if(!empty($message))
		echo $message;
	
?>
<div class="startQcm">
	<p>
	Bonjour, vous êtes connecté en tant que <strong><?php echo $_SESSION['prenomEnseignant'].' '.$_SESSION['nomEnseignant'] ?></strong><br /><br />
	
	Pour débuter une épreuve sélectionnez simplement la classe et l'épreuve concernées et cliquez sur "Débuter l'épreuve".
	</p>
	<form action="#" method="post" >
		<fieldset>
			<legend>Débuter une épreuve</legend>
			
			<table>
				<tr>
					<td><label for="epreuve" >Epreuve :</label></td>
					<td>
						<select name="epreuve" id="epreuve">
						<?php
						foreach($epr as $key => $value)
						{
							echo '<option value="'.$value->idEpreuve().'" >'.ucfirst($value->libelle()).'</option>
							'; 
						}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="classe" >Classe :</label></td>
					<td>
						<select name="classe" id="classe" >
						<?php
						foreach($classes as $key => $value)
						{
							echo '<option value="'.$value['libClasse'].'" >'.ucfirst($value['libClasse']).'</option>
							'; 
						}
						?>
						</select>
					</td>
				</tr>
			<table>			
			
			<input type="submit" class="dr" value="Débuter l'épreuve" />
		</fieldset>
	</form>
</div>
<?php
}
else
{
	header('Location: bye.php');
	exit();
}

?>