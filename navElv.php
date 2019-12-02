<?php

if(isset($_SESSION['login']) && $_SESSION['login'] == 'eleve')
{
?>
<header>
	<nav>
		<ul>
			<li>Utilisateur : <strong><?php echo ucfirst($_SESSION['prenomEleve']).' '.ucfirst($_SESSION['nomEleve']).'</strong> ('.$_SESSION['libClasse'].')'; ?></li>
			<li class="dr"><a href="bye.php" >Déconnexion</a></li>
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