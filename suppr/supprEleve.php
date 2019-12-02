<?php
// cette page permet à un admin d'ajouter un eleve et de l'affecter à une classe dans la bdd

if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant' && $_SESSION['admin'] == 1)
{
	include('objets/autoloader.php');
	include('coBdd.php');
	
	$manager = New Manager($bdd);
	$err = 0;
	
	
	// si formulaire 2 envoyé
	if(!empty($_POST['sent']) && $_POST['sent'] == 2){
		// on vérifie la variable d'entrée
		if(!empty($_POST['classe'])){
			$idClasse = (int) $_POST['classe'];
			
			// on recupere la liste des eleves de la classe
			$listElvClass = $manager->getElvClasse($idClasse);
			
			// on crée un fomulaire "formulaire 3" avec une liste déroulante des eleves
			echo '<div class="creeEpr">
				<form action="" method="post" >
					<fieldset>
						<legend>Selectionnez l\'élève à retirer de la base de donnée.</legend>';
			// on génère la liste des elv
			echo '<select name="eleve">';
			foreach($listElvClass as $key => $value)
			{
				echo '<option value="'.$value['idEleve'].'" ';
				if(!empty($_POST['eleve']) && $err == 1)
					echo 'selected';
				echo '/>'.$value['nomEleve'].' '.$value['prenomEleve'].'</option>';
			}
			echo '</select>';
			
			// pour recharger ce formulaire dans ajout
			echo '<input type="hidden" name="suppr" value="'.$_POST['suppr'].'" />
						<input type="hidden" name="sent" value="3" />
						<input type="submit" value="Supprimer" />
					</fieldset>
				</form>
			</div>';
			
		}else{
			$err += 1;
			$message = '<p class="erreur" >Erreur : Vous devez renseigner la classe de l\'élève.</p>';
		}
	}else{
	//sinon le reste
		
		// si le formulaire 1 envoyé
		if(!empty($_POST['sent']) && $_POST['sent'] == 1){
			// on véifie les variable d'entrée
			if(!empty($_POST['nom'])){
				if(!empty($_POST['prenom'])){
					// on récupère la liste des élèves
					$listElv = $manager->getListElv();
					
					// on parcours la liste avec l'algorithme "R1"
					$i = 0;
					$trouve = false;
					
					do{
						if(($listElv[$i]['nomEleve'] == $_POST['nom']) && ($listElv[$i]['prenomEleve'] == $_POST['prenom'])){
							$idElv = (int) $listElv[$i]['idEleve'];						
							$trouve = true;
						}
						$i++;
					}while(!($trouve) && !empty($listElv[$i]));
					
					
					// si on a trouvé l'eleve on le supprime
					if($trouve == true){
						$manager->delElv($idElv);
						$message = '<p class="confirme" >L\'élève '.$_POST['nom'].' '.$_POST['prenom'].' a bien été retiré de la base de données.</p>';
					}
					else{
						$err += 1;
						$message = '<p class="erreur" >Erreur : élève introuvable, vérifiez les informations renseignées et réessayez.</p>';
					}
					
					
				}else{
					$err += 1;
					$message = '<p class="erreur" >Erreur : Vous devez renseigner le prénom de l\'élève.</p>';
				}
			}else{
				$err += 1;
				$message = '<p class="erreur" >Erreur : Vous devez renseigner le nom de l\'élève.</p>';
			}
		}
		// si formulaire 3 envoyé 
		elseif(!(empty($_POST['sent'])) && $_POST['sent'] == 3){
			$idElv = (int) $_POST['eleve'];
			
			$manager->delElv($idElv);
			
			$message = '<p class="confirme" >L\'élève a bien été retiré de la base de données.</p>';
		}		
		
		// si y a des erreurs faut afficher le message
		if(!empty($message))
			echo $message;
		
		?>
		<!--Formulaire de recherche d'eleve par Nom/Prenom -->
		<div class="creeEpr">
			<form action="" method="post" >
				<fieldset>
					<legend>Rchercher un éleve par Nom/Prénom</legend>
					<table>
						<tr><td>Nom</td><td><input type="text" name="nom" <?php if(!empty($_POST['nom']) && $err == 1) { echo 'value="'.$_POST['nom'].'"' ; } ?>/></td></tr>
						<tr><td>Prenom</td><td><input type="text" name="prenom" <?php if(!empty($_POST['prenom']) && $err == 1) { echo 'value="'.$_POST['prenom'].'"' ; } ?>/></td></tr>
					</table>
					<?php
					// pour recharger ce formulaire dans ajout
					echo '<input type="hidden" name="suppr" value="'.$_POST['suppr'].'" />
					<input type="hidden" name="sent" value="1" />';
					?>
					<input type="submit" value="Rechercher" />
				</fieldset>
			</form>
		</div>
		
		<!--Formulaire de recherche d'eleve par Classe -->
		<?php
		// on récupère les classes
		$classe = $manager->getListClass();
		?>
		<div class="creeEpr">
			<form action="" method="post" >
				<fieldset>
					<legend>Rchercher un éleve par Classe</legend>
					Sélectionnez la classe de l'élève à supprimer
					<select name="classe" >
						<?php
						foreach($classe as $key => $value)
						{
							echo '<option value="'.$classe[$key]['idClasse'].'" ';
							if(!empty($_POST['classe']) && $err == 1)
								echo 'selected';
							echo '/>'.$classe[$key]['libClasse'].'</option>';
						}
						?>
					</select>
					<?php
					// pour recharger ce formulaire dans ajout
					echo '<input type="hidden" name="suppr" value="'.$_POST['suppr'].'" />
					<input type="hidden" name="sent" value="2" />';
					?>
					<input type="submit" value="Rechercher" />
				</fieldset>
			</form>
		</div>
	<?php
	}
}
else
{
	header('Location: ../bye.php');
	exit();
}
?>