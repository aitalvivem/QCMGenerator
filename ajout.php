<?php
// cette page permet a un administrateur d'ajouter
	// des eleves (à une classe)
	// des classes
	// des matières
	// des professeurs
	// des administrateurs

if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant' && $_SESSION['admin'] == 1)
{
	// si on a selectionné un ajout
	if(!empty($_POST['ajout']))
	{
		// on construit le nom de la page et on l'inclus
		$page = 'ajout/ajout'.ucfirst($_POST['ajout']).'.php';
		include($page);
		
		// pour faire un autre ajout
		?>
		<form action="dispacher3.php?page=1" method="post">
			<input type="submit" value="Effectuer un autre ajout" />
		</form>
		<?php
	}
	// sinon on affiche le formulaire de selection d'ajouts
	else
	{
	?>
	<div class="creeEpr">
		<p>
		Bonjour, vous êtes connecté en tant que <strong><?php echo $_SESSION['prenomEnseignant'].' '.$_SESSION['nomEnseignant'] ?></strong> (administrateur).
		</p>
		<form action="dispacher3.php?page=1" method="post" >
			<fieldset>
				Selectionnez le type d'ajout que vous souhaitez effectuer.<br/><br/>
				Ajouter 
				<select name="ajout">
					<option value="eleve" >un élève</option>
					<option value="classe" >une classe</option>
					<option value="matiere" >une matière</option>
					<option value="enseignant" >un enseignant</option>
					<option value="administrateur" >un administrateur</option>
				</select>
				<input type="submit" value="valider" />
			</fieldset>
		</form>
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