<?php

if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant' && $_SESSION['admin'] == 1)
{
	if(!empty($_POST['suppr']))
	{
		// on construit le nom de la page et on l'inclus
		$page = 'suppr/suppr'.ucfirst($_POST['suppr']).'.php';
		include($page);
		
		// pour faire une autre suppression
		?>
		<form action="dispacher3.php?page=2" method="post">
			<input type="submit" value="Effectuer une autre suppression" />
		</form>
		<?php
	}
	else
	{
	?>
	<div class="creeEpr">
		<p>
		Bonjour, vous êtes connecté en tant que <strong><?php echo $_SESSION['prenomEnseignant'].' '.$_SESSION['nomEnseignant'] ?></strong> (administrateur).
		</p>
		<form action="dispacher3.php?page=2" method="post" >
			<fieldset>
				Selectionnez le type de suppression que vous souhaitez effectuer.<br/><br/>
				Supprimer 
				<select name="suppr">
					<option value="eleve" >un élève</option>
					<option value="classe" >une classe</option>
					<option value="enseignant" >un enseignant</option>
					<option value="administrateur" >un administrateur</option>
					<option value="question" >une question</option>
					<option value="epreuve" >une épreuve</option>
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