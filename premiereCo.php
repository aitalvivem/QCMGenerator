<?php
// cette s'affiche lors de la premiere connection d'un utilisateur et lui demande de rentrer un nouveau mdp

if(isset($_SESSION['login']) && ($_SESSION['login'] == 'enseignant' || $_SESSION['login'] == 'eleve')){
	include('objets/autoloader.php');
	include('coBdd.php');
	
	// on traite le formulaire
	if(!empty($_POST['sent'])){
		if(!empty($_POST['newMdp']) && !empty($_POST['confirmNewMdp']))
		{
			// on vérifie la longueur minimale pour le mot de passe
			if(strlen($_POST['newMdp']) >= 10)
			{
				// on vérifie qu'il y ait au moins une majuscule
				if(preg_match("#[A-Z ]#",$_POST['newMdp']))
				{
					// on vérifie si les deux mdp sont les meme
					if($_POST['newMdp'] == $_POST['confirmNewMdp'])
					{
						$manager = new Manager($bdd);
						
						// on appele la fonction du manager updateMdp(id, login, newMdp)
						if($manager->updateMdp($_SESSION['id'], $_SESSION['login'],  $_POST['newMdp']) == 1)
						{
							$message = '<p class="erreur" >Erreur : Il y a eu une erreur dans l\'enregistrement de votre mot de passe.</p>';
						}
						else
						{
							echo '<p class="confirme" >Votre mot de passe a été modifié avec succés.</p>';
							unset($_SESSION['premiereCo']);
							$_SESSION['page'] = 0;
							
							if($_SESSION['login'] == 'enseignant')
								header('Location: dispacher.php');
							else
								header('Location: dispacher2.php');
						}
					}
					else
					{
						$message = '<p class="erreur" >Erreur : Votre nouveau mot de passe ne correspond pas à la confirmation que vous avez entré.</p>';
					}
				}
				else
				{
					$message = '<p class="erreur" >Erreur : Votre nouveau mot de passe doit contenir au moins une majuscule.</p>';
				}
			}
			else
			{
				$message = '<p class="erreur" >Erreur : Votre nouveau mot de passe doit contenir au moins 10 caractères.</p>';
			}
		}
		else
		{
			$message = '<p class="erreur" >Erreur : Il vous faut renseigner les champs "Nouveau mot de passe" et "Confirmation du nouveau mot de passe".</p>';
		}
	}
	
	if(!empty($message))
		echo $message;

	// on affiche un formulaire demandant de rentrer un nouveau mot de passe
	?>
	<div class="creeEpr">
		<p>
			Bonjour, il semble que ce soit votre première connection sur cette plateforme. <br />
			Un mot de passe	par défaut vous a été attribué mais il n'est pas très sécurisé. <br />
			Avant de continuer la navigation sr notre plateforme nous vous invitons à renseigner un nouveau mot de passe qui vous sera propre.<br /><br />
			Votre mot de passe doit être composé de 10 caractères (au minimum) et contenir au moins une majuscule.
		</p>
		<form action="" method="post" >
			<fieldset>
				<legend>Modification de votre mot de passe</legend>
				<table>
					<tr><td>Nouveau mot de passe</td><td><input type="password" name="newMdp" /></td></tr>
					<tr><td>Confirmation du nouveau mot de passe</td><td><input type="password" name="confirmNewMdp" /></td></tr>
				</table>
				<input type="hidden" name="sent" value="1" />
				<input type="submit" value="Confirmer" />
			</fieldset>
		</form>
	</div>
	<?php
}else{
	header('Location: bye.php');
	exit();
}

?>