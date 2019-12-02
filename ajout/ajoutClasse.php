<?php
// c'est page permet à un administrateur d'ajouter une classe

if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant' && $_SESSION['admin'] == 1)
{
	include('objets/autoloader.php');
	include('coBdd.php');
	
	$manager = New Manager($bdd);
	$err = 0;
	
	// si le fomulaire à été envoyé on le traite
	if(!empty($_POST['sent']))
	{
		if(!empty($_POST['libClasse']))
		{
			if(!empty($_POST['niveau']))
			{
				if(!empty($_POST['effectif']))
				{
					// crée le tab de données
					// on crée le tableau de données
					$donnees['libClasse'] = $_POST['libClasse'];
					$donnees['niveau'] = $_POST['niveau'];
					$donnees['effectif'] = (int) $_POST['effectif'];
					
					$manager->addClasse($donnees);
					
					echo '<p class="confirme" >La classe de '.ucfirst($_POST['libClasse']).' a bien été ajoutée à la liste des classes.</p>';
				}
				else
				{
					$err += 1;
					$message = '<p class="erreur" >Erreur : L\'effectif de la classe n\'a pas été renseigné.</p>';
				}
			}
			else
			{
				$err += 1;
				$message = '<p class="erreur" >Erreur : Le niveau de la classe n\'a pas été renseigné.</p>';
			}
		}
		else
		{
			$err += 1;
			$message = '<p class="erreur" >Erreur : Le libellé de la classe n\'a pas été renseigné.</p>';
		}
	}
	
	// sinon on affiche le formulaire
	
	// si y a des erreurs faut afficher le message
	if(!empty($message))
		echo $message;
	
	?>
	<div class="creeEpr">
		<form action="" method="post" >
			<fieldset>
				<legend>Ajouter une classe</legend>
				<table>
					<tr><td>Libellé de la classe</td><td><input type="text" name="libClasse" <?php if(!empty($_POST['libClasse']) && $err == 1) { echo 'value="'.$_POST['libClasse'].'"' ; } ?> /></td></tr>
					<tr><td>Niveau</td><td><input type="text" name="niveau" <?php if(!empty($_POST['niveau']) && $err == 1) { echo 'value="'.$_POST['niveau'].'"' ; } ?> /></td></tr>
					<tr><td>Effectif</td><td><input type="text" name="effectif" <?php if(!empty($_POST['effectif']) && $err == 1) { echo 'value="'.$_POST['effectif'].'"' ; } ?> /></td></tr>
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