<?php
// cette page permet de donner les droits d'administration à un enseignant existant

if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant' && $_SESSION['admin'] == 1)
{
	include('objets/autoloader.php');
	include('coBdd.php');
	
	$manager = New Manager($bdd);
	$err = 0;
	
	// si un formulaire à été envoyé on le traite
	if(!empty($_POST['sent']))
	{
		var_dump($_POST);
		if(!empty($_POST['idEns']))
		{
			$idEns = (int) $_POST['idEns'];
			
			// on retire les droits
			$manager->updateEns($idEns, 1);
			
			$message = '<p class="confirme" >L\'enseignant a bien reçu les droits d\'administrateur.</p>';
		}
		else
		{
			$err =+ 1;
			$message = '<p class="erreur" >Erreur : Vous devez sélectionner un enseignant.</p>';
		}
	}
	
	
	
	// si y a des erreurs faut afficher le message
	if(!empty($message))
		echo $message;
	
	// on récupère la liste des ens qui ne sont pas admin
	$listEns = $manager->getListEns();
	
	// formulaire
	?>
	<div class="creeEpr">
		<form action="" method="post" >
			<fieldset>
				<legend>Ajouter les droits d'administrateur à un enseignant</legend>
				Sélectionnez l'enseignant à qui vous souhaitez ajouter les droits d'administrateur
				<select name="idEns" >
				<?php
					foreach($listEns as $key => $value)
					{
						echo '<option value="'.$listEns[$key]['idEnseignant'].'" ';
						if(!empty($_POST['idEns']) && $err == 1)
							echo 'selected';
						echo '/>'.$listEns[$key]['nomEnseignant'].' '.$listEns[$key]['prenomEnseignant'].'</option>';
					}
					?>
				</select>
				<?php
				// pour recharger ce formulaire dans ajout
				echo '<input type="hidden" name="ajout" value="'.$_POST['ajout'].'" />
				<input type="hidden" name="sent" value="1" />';
				?>
				<input type="submit" value="Ajouter les droits" />
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