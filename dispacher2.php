<?php
// DU COTE ELEVE
	
	// PARTICIPER A UNE EPREUVE
		// quand un eleve se connecte on recupère son id son nom et son prenom et sa classe
		// on les retiens ($_SESSION), et on lui affiche la liste des epreuves correspondant à sa classe et à la date actuelle
		// il sélectionne une épreuve

		// -> on crée le qcm grâce aux 4 informations : date, epreuve, classe, eleve(nom prenom)
		// -> on affiche le qcm ($qcm->printQcm())
		// -> le qcm se termine par envoie du formulaire

		// on enregistre ses réponses dans la table réaliser
			// idQuestion, idEpreuve, idEleve ($manager->addQcmRep())
		// on vérifie les réponses (objet Arbitre)
		// on affiche le score

		//on propose de logout ou de réaliser une autre épreuve

	
session_start();

// si c'est un eleve
if(isset($_SESSION['login']) && $_SESSION['login'] == 'eleve')
{
	if(isset($_GET['page']))
		$_SESSION['page'] = $_GET['page'];
	else
		$_SESSION['page'] = 0;
	
	// si c'est la primiere connection d'un utilisateur
	if(!empty($_SESSION['premiereCo']) && $_SESSION['premiereCo'] == true){
		$_SESSION['page'] = 5;
	}
	
?>
<html>
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="script_qcm" />
		<link rel="shortcut icon" href="img/priorities-checkbox.png" />
		<title>QCMGenerator</title>
	</head>
	<body>
		<?php
		include('navElv.php');
		?>
		<div class="corp" >
			<?php	
	
			switch($_SESSION['page'])
			{
				case 1:
					include('selectEpr.php');
					break;
				case 2:
					include('passeEpr.php');
					break;
				case 3:
					include('afficheNote.php');
					break;
				case 5:
					include('premiereCo.php');
					break;
				default:
					include('selectEpr.php');
			}
			?>
		</div>
	</body>
</html>
<?php

}
else
{
	header('Location: bye.php');
	exit();
}
?>