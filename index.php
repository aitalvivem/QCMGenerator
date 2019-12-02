<?php

// AJOUT
	// SI LE MOT DE PASSE EST VIDE => PREMIERE CONNEXION
	// IL FAUT DEMANDER A L'UTILISATEUR D'EN DEFINIR UN


if(!empty($_POST['login']) && !empty($_POST['mdp']) && !empty($_POST['typeLog']))
{
	include('objets/autoloader.php');
	include('coBdd.php');
	
	$manager = New Manager($bdd);
	
	// si c'est un enseignant
	if($_POST['typeLog'] == 'enseignant')
	{
		// on recupere le mdp par rapport au login renseigné
		$mdp = $manager->getMdp('ens', $_POST['login']);
		
		// si le mdp est bon
		if($mdp == $_POST['mdp'])
		{
			session_start();
			
			// on recupere le nom et le prenom de l'ens
			$manager->getEns($_POST['login']);
			
			// si le mdp est 'azerty' alors on on crée la variable de session premiereCo et on l'initialise a true
			if($_POST['mdp'] == 'azerty')
				$_SESSION['premiereCo'] = true;
			
			header('Location: dispacher.php');
			exit();
		}
		else
		{
			header('Location: index.php');
			exit;
		}
	}
	// si c'est un eleve
	elseif($_POST['typeLog'] == 'eleve')
	{
		// on recupere le mdp par rapport au login renseigné
		$mdp = $manager->getMdp('elv', $_POST['login']);
		
		// si le mdp est bon
		if($mdp == $_POST['mdp'])
		{
			session_start();
			
			$manager->getElv($_POST['login']);
			
			if($_POST['mdp'] == 'azerty')
				$_SESSION['premiereCo'] = true;
			
			header('Location: dispacher2.php');
			exit();
		}
		else
		{
			header('Location: index.php');
			exit;
		}
	}
}
else
{
?>
<html>
	<head>
		<title>QCMGenerator</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="script_qcm" />
		<link rel="shortcut icon" href="img/priorities-checkbox.png" />
	</head>
	
	<body>
		<img src="img/bck.png" height="4%" />
		<div class="co" >
			<form action="#" method="post">
				<fieldset>
					<legend>Login Enseignant</legend>
					
					<label for="login" >Login </label>
					<input type="text" name="login" />
					
					<label for="mdp" >Mot de passe </label>
					<input type="password" name="mdp" />
					
					<input type="hidden" name="typeLog" value="enseignant" />
					<input type="submit" value="Valider" />
				</fieldset>
			</form>
			<form action="#" method="post">
				<fieldset>
					<legend>Login Eleve</legend>
					
					<label for="login" >Login </label>
					<input type="text" name="login" />
					
					<label for="mdp" >Mot de passe </label>
					<input type="password" name="mdp" />
					
					<input type="hidden" name="typeLog" value="eleve" />
					<input type="submit" value="Valider" />
				</fieldset>
			</form>
		</div>
	<body>
</html>
<?php
}
?>