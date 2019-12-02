<?php

if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant')
{
?>
<header>
	<nav>
		<ul>
			<li><a href="dispacher.php?page=2" >Créer une épreuve</a></li>
			<li><a href="dispacher.php?page=3" >Débuter une épreuve</a></li>
			<li><a href="dispacher.php?page=4" >Afficher les épreuves existantes</a></li>
			<li><a href="dispacher.php?page=1" >Ajouter une question</a></li>
			<li class="dr"><a href="bye.php" >Déconnexion</a></li>
			<?php
			if($_SESSION['admin'] == 1)
				echo '<li class="dr" ><a href="dispacher3.php" >Section Administrateur</a></li>';
			?>
			<li class="dr" >Utilisateur : <strong><?php echo $_SESSION['prenomEnseignant'].' '.$_SESSION['nomEnseignant']; ?></strong></li>
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