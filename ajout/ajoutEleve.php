<?php
// cette page permet à un admin d'ajouter un eleve et de l'affecter à une classe dans la bdd

if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant' && $_SESSION['admin'] == 1)
{
	include('objets/autoloader.php');
	include('coBdd.php');
	
	$manager = New Manager($bdd);
	$err = 0;
	
	// si on a envoyé un formulaire
	// on vérifie l'existance des variables
	if(!empty($_POST['sent']) )
	{
		if(!empty($_POST['nom']))
		{
			if(!empty($_POST['prenom']))
			{
				if(!empty($_POST['classe']))
				{
					if(is_string($_POST['nom']) && is_string($_POST['prenom']))
					{
						// on crée le tableau de données
						$donnees['nomEleve'] = $_POST['nom'];
						$donnees['prenomEleve'] = $_POST['prenom'];
						$donnees['idClasse'] = (int) $_POST['classe'];
						
						// on appele la méthode addElv du manager
						$manager->addElv($donnees);
						
						echo '<p class="confirme" >'.ucfirst($_POST['prenom']).' '.ucfirst($_POST['nom']).' a bien été ajouté(e) à la liste des élèves.</p>';
					}
					else
					{
						$err += 1;
						$message = '<p class="erreur" >Erreur : Le nom et le prénom doivent être des chaines de caractères.</p>';
					}
				}
				else
				{
					$err += 1;
					$message = '<p class="erreur" >Erreur : La classe n\'a pas été renseignée.</p>';
				}
			}
			else
			{
				$err += 1;
				$message = '<p class="erreur" >Erreur : Il vous faut renseigner un prenom.</p>';
			}
		}
		else
		{
			$err += 1;
			$message = '<p class="erreur" >Erreur : Il vous faut renseigner un nom.</p>';
		}
	}
	
	// on affiche le formulaire
	
	$classe = $manager->getListClass();
	
	// si y a des erreurs faut afficher le message
	if(!empty($message))
		echo $message;
	
	?>
	<div class="creeEpr">
		<form action="" method="post" >
			<fieldset>
				<legend>Ajouter un éleve</legend>
				<table>
					<tr><td>Nom</td><td><input type="text" name="nom" <?php if(!empty($_POST['nom']) && $err == 1) { echo 'value="'.$_POST['nom'].'"' ; } ?>/></td></tr>
					<tr><td>Prenom</td><td><input type="text" name="prenom" <?php if(!empty($_POST['prenom']) && $err == 1) { echo 'value="'.$_POST['prenom'].'"' ; } ?>/></td></tr>
					<tr><td>Classe</td><td>
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
					</td></tr>
				</table>
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