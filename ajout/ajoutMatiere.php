<?php
// cette page permet à un administrateur d'ajouter une matiere à la base de données

if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant' && $_SESSION['admin'] == 1)
{
	include('objets/autoloader.php');
	include('coBdd.php');
	
	$manager = New Manager($bdd);
	$err = 0;
	
	// si un formulaire a été envoyer on le traite 
		// gestion des erreurs
		// message de confirmation
	if(!empty($_POST['sent']))
	{
		if(!empty($_POST['libelle']))
		{
			if(is_string($_POST['libelle']))
			{
				$manager->addMat($_POST['libelle']);
				
				echo '<p class="confirme" >La matière '.ucfirst($_POST['libelle']).' a bien été ajoutée à la liste des matières.</p>';
			}
			else
			{
				$err += 1;
				$message = '<p class="erreur" >Erreur : Le libellé de la matière doit être une chaine de caractères.</p>';
			}
		}
		else
		{
			$err += 1;
			$message = '<p class="erreur" >Erreur : Le libellé de la matière n\'a pas été renseigné.</p>';
		}
	}
	
	// si y a des erreurs faut afficher le message
	if(!empty($message))
		echo $message;
	
	?>
	<div class="creeEpr">
		<form action="" method="post">
			<fieldset>
				<legend>Ajouter une matière</legend>
				<label for="libelle" >Libellé de la matière</label>
				<input type="text" name="libelle" <?php if(!empty($_POST['libelle']) && $err == 1) { echo 'value="'.$_POST['libelle'].'"' ; } ?> />
				<?php
				// pour recharger ce formulaire dans ajout
				echo '<input type="hidden" name="ajout" value="'.$_POST['ajout'].'" />
				<input type="hidden" name="sent" value="1" />';
				?>
				<input type="submit" value="Ajouter" />
			</fieldset>
		</form>
	</div>
	<?php
}
else
{
	header('Location: ../bye.php');
	exit();
}
?>