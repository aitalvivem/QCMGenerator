<?php
// reçoit une idEpreuve

if(isset($_SESSION['login']) && $_SESSION['login'] == 'eleve')
{
	// si on a reçu une id
	if(!empty($_POST['qcm']))
	{
		include('objets/autoloader.php');	//autoloader de classe
		include('coBdd.php');		//connection a la bdd
	
		$idQcm = (int) $_POST['qcm'];
		$uei = date('Y-m-d');
		$manager = New Manager($bdd);
		
		// on récupère le qcm et on ajoute le nom de l'étudiant
		$qcm = $manager->getQcm($idQcm, $_SESSION['libClasse'], $uei);
		$qcm->setNomEleve($_SESSION['nomEleve']);
		$qcm->setPrenomEleve($_SESSION['prenomEleve']);
		
		// on se rapelle de l'idEpreuve
		$_SESSION['idEpreuve'] = $idQcm;
		
		$qcm->printQcm();
	}
	else
	{
		header('Location: selectEpr.php');
		exit();
	}
}
else
{
	header('Location: bye.php');
	exit();
}
?>