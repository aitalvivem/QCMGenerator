<?php
// cette page permet de retirer les droit d'administration à un enseignant

if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant' && $_SESSION['admin'] == 1)
{
	include('objets/autoloader.php');
	include('coBdd.php');
	
	$manager = New Manager($bdd);
	$err = 0;
	
	// si le formulaire a été envoyé on le traite
	if(!empty($_POST['sent'])){
		if(!empty($_POST['idAdmin'])){
			$idAdmin = (int) $_POST['idAdmin'];
			
			// on retire les droits d'administration
			$manager->updateEns($idAdmin, 0);
			
			$message = '<p class="confirme" >L\'enseignant a bien perdu ses droits d\'administrateur.</p>';
		}else{
			$err =+ 1;
			$message = '<p class="erreur" >Erreur : Vous devez sélectionner un administrateur.</p>';
		}
	}
	
	// si y a des erreurs faut afficher le message
	if(!empty($message))
		echo $message;
	
	// on récupère la liste des admin
	$listAdmin = $manager->getListAdmin();

	// formulaire
	?>
	<div class="creeEpr">
			<form action="" method="post" >
				<fieldset>
					<legend>Retirer des droits d'administration</legend>
					Sélectionnez l'administrateur à qui vous souhaitez retirer les droits
					<select name="idAdmin" >
					<?php
						foreach($listAdmin as $key => $value)
						{
							echo '<option value="'.$listAdmin[$key]['idEnseignant'].'" ';
							if(!empty($_POST['idAdmin']) && $err == 1)
								echo 'selected';
							echo '/>'.$listAdmin[$key]['nomEnseignant'].' '.$listAdmin[$key]['prenomEnseignant'].'</option>';
						}
						?>
					</select>
					<?php
					// pour recharger ce formulaire dans ajout
					echo '<input type="hidden" name="suppr" value="'.$_POST['suppr'].'" />
					<input type="hidden" name="sent" value="1" />';
					?>
					<input type="submit" value="Retirer les droits" />
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