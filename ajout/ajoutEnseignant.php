<?php
// cette page permet a un administrateur d'ajouter un enseignant à la base de donnée

if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant' && $_SESSION['admin'] == 1)
{
	include('objets/autoloader.php');
	include('coBdd.php');
	
	$manager = New Manager($bdd);
	$err = 0;
	
	// si un formulaire à été envoyé on le traite
	if(!empty($_POST['sent']))
	{
		// vérification des données
		if(!empty($_POST['nom']))
		{
			if(!empty($_POST['prenom']))
			{
				if(is_string($_POST['nom']) && is_string($_POST['prenom']))
				{
					// on récupère la valeur de admin (case à cocher, si existe alors admin = 1 sinon 0)
					if(!empty($_POST['admin']) && $_POST['admin'] == '1')
						$admin = 1;
					else
						$admin = 0;
				
					// mise en forme des données et création du tableau de valeur pour la methode manager->addEns(array $tab)
						// nomEnseignant -> string
						// prenomEnseignant -> string
						// admin -> bool
					$donnees['nomEnseignant'] = $_POST['nom'];
					$donnees['prenomEnseignant'] = $_POST['prenom'];
					$donnees['admin'] = $admin;
					
					$manager->addEns($donnees);
					
					echo '<p class="confirme" >'.ucfirst($_POST['prenom']).' '.ucfirst($_POST['nom']).' a bien été ajouté(e) à la liste des enseignants.</p>';

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
				$message = '<p class="erreur" >Erreur : Il vous faut renseigner un prenom.</p>';
			}
		}
		else
		{
			$err += 1;
			$message = '<p class="erreur" >Erreur : Il vous faut renseigner un nom.</p>';
		}
	}
	
	// si y a des erreurs faut afficher le message
	if(!empty($message))
		echo $message;
	
	// formulaire
	?>
	<div class="creeEpr">
		<form action="" method="post" >
			<fieldset>
				<legend>Ajouter un enseignant</legend>
				<table>
					<tr><td>Nom</td><td><input type="text" name="nom" <?php if(!empty($_POST['nom']) && $err == 1) { echo 'value="'.$_POST['nom'].'"' ; } ?>/></td></tr>
					<tr><td>Prenom</td><td><input type="text" name="prenom" <?php if(!empty($_POST['prenom']) && $err == 1) { echo 'value="'.$_POST['prenom'].'"' ; } ?>/></td></tr>
					<tr><td colspan="2">
						Cochez cette case pour donner les droits d'administrateurs au nouvel enseignant :
						<input type="checkbox" name="admin" value="1" />
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