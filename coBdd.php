<?php

//connection a la bdd
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=esicad_qcm;charset=utf8', 'root', 'blackdream', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (exeption $e)
{
	die('Erreur : ' . $e->getMessage());
}

?>