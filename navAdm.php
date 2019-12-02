<?php

if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant' && $_SESSION['admin'] == 1)
{
?>
<header>
	<nav>
		<ul>
			<li><a href="dispacher3.php?page=1" >Ajouts</a></li>
			<li><a href="dispacher3.php?page=2" >Suppressions</a></li>
			<li><a href="dispacher3.php?page=3" >Arret d'une épreuve en cours</a></li>
			<li class="dr"><a href="bye.php" >Déconnexion</a></li>
			<li class="dr"><a href="dispacher.php" >Retour à la section Enseignant</a></li>
			<li class="dr" >Utilisateur : <strong><?php echo $_SESSION['prenomEnseignant'].' '.$_SESSION['nomEnseignant']; ?></strong> (admin)</li>
		</ul>
	</nav>
</header>
<?php
}
else
{
	header('Location: bye.php');
	exit();
}
?>