<?php
// cette page permet a un eleve connecté de selectionner une épreuve

if(isset($_SESSION['login']) && $_SESSION['login'] == 'eleve')
{
	include('objets/autoloader.php');	//autoloader de classe
	include('coBdd.php');		//connection a la bdd
	
	$manager = New Manager($bdd);
	
	// on recupere la date
	$uei = date('Y-m-d');
	
	// on recupere les id et libellé des epreuves
	$epreuves = $manager->getListEpr($_SESSION['idClasse'], $_SESSION['id'], $uei);

	?>
<div class="startQcm">
	<form action="dispacher2.php?page=2" method="post">
		<fieldset>
			<legend>Participer à un épreuve</legend>
			
			<label for="epreuve" >Selectionnez une épreuve :</label>
			
			<?php
			// on affiche les epreuves
			$i=1;
			
			if(!empty($epreuves))
			{
				echo '<select name="qcm" id="qcm">';
				while(!empty($epreuves[$i]['idEpreuve']))
				{
					echo '<option value="'.$epreuves[$i]['idEpreuve'].'" >'.ucfirst($epreuves[$i]['libelle']).'</option>
					';
					$i++;
				}
				echo '</select>
				<input class="dr" type="submit" value="Participer à l\'épreuve" />';
			}
			else
				echo '<p class="erreur">Il n\'y a pas d\'épreuve en cours pour votre classe.</p>';
			?>
			
		</fieldset>
	</form>
</div>
	<?php

}
else
{
	header('Location: bye.php');
	exit();
}
?>