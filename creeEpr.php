<?php
		
// cette page contient : 
	// le formulaire pour créer une épreuve 
	// le traitement du formulaire (création d'un objet epreuve et enregistrement de cet objet)
			
if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant')
{
	include('objets/autoloader.php');	// autoloader de classe
	include('coBdd.php');		// connection a la bdd
	include('verifEsp.php');	// la fonction verifEsp
	
	$manager = new Manager($bdd);	
		
	// si le formulaire a été envoyé on le traite
	if(!empty($_POST['libEpr']) && !empty($_SESSION['nomEnseignant']) && !empty($_SESSION['prenomEnseignant']))
	{
		if(!empty($_POST['libEpr']))
		{
			$libEpr = $_POST['libEpr'];
			unset($_POST['libEpr']);
			
			$libEpr = verifEsp($libEpr);
			
			// on vérifie qu'il y a des questions
			$i = 0;
			foreach($_POST as $key => $value)
			{
				if(!empty($_POST[$key]))
					$i++;
			}
			
			if($i > 0)
			{
				$questions = [];
				
				//faut créer le tableau de questions
				foreach($_POST as $key => $value)
				{
					$id = (int) $value;
					$questions[] = $manager->getQue($id);
				}
				
				// on crée le tableau de donnees pour créer l'instance d'epreuve
				$donnees = array(
									'libelle' => $libEpr,
									'nomEnseignant' => $_SESSION['nomEnseignant'],
									'prenomEnseignant' => $_SESSION['prenomEnseignant'],
									'questions' => $questions
									);
				
				$epr = new Epreuve($donnees);
				
				// ensuite faut enregistrer l'epreuve
				$manager->addEpr($epr);
				
				$message = '<p class="confirme">L\'Epreuve \''.$epr->libelle().'\' a bien été crée.</p>';
			}
			else
			{
				$message = '<p class="erreur"><strong>Erreur :</strong> Vous devez selectionner au moins une question pour votre épreuve.</p>';
				$err = 1;
			}
		}
		else
		{
			$message = '<p class="erreur"><strong>Erreur :</strong> Vous devez renseigner un libellé pour votre épreuve.</p>';
			$err = 1;
		}
	}

	// si il y a un message a afficher
	if(!empty($message))
		echo $message;
	?>
	<div class="creeEpr" >
		<p>
		Bonjour, vous êtes connecté en tant que <strong><?php echo $_SESSION['prenomEnseignant'].' '.$_SESSION['nomEnseignant'] ?></strong>.<br /><br />
		
		Pour créer une épreuve entrez un libellé pour l'épreuve puis selectionnez les questions qui composeront l'épreuve.
		</p>
		<form action="#" method="post">
			<fieldset>
				<legend>Recherche</legend>
				
				<label for="matiere">Filtrer les questions par matiere : </label>
				<?php
				// on recupère les matières
				$mat = $manager->getMat();
				
				echo '<select name="matiere" id="matiere">';
				echo '	<option value="*" >Toutes les matières</option>';
				foreach($mat as $key => $value)
				{
					echo '<option value="'.$value['idMatiere'].'"';
					
					if(!empty($_POST['matiere']) && $_POST['matiere'] == $value['idMatiere'])
						echo 'selected';
					
					echo '>'.ucfirst($value['libMatiere']).'</option>
					';
				}
				echo '</select>';
				?>
				<input type="submit" class="dr" value="Appliquer le filtre" />
			</fieldset>
		</form>
		<form action="#" method="post" >
			<fieldset>
				<legend>Créer une épreuve</legend>
				
				<label for="libEpr">Libellé de l'épreuve : </label>
				<input type="text" name="libEpr" <?php if(!empty($err) && $err == 1 && !empty($libEpr)){ echo 'value="'.htmlspecialchars($libEpr).'"'; } ?>/>
				
				<input type="submit" class="dr" value="Créer l'épreuve" />
			</fieldset>
			<?php
			// la faut aller chercher toute les questions, les afficher avec des cases a selectionner
			// si il y a des filtres on les applique 
			if(!empty($_POST['matiere']))
			{
				$matiere = (int) $_POST['matiere'];
				$tab = $manager->getAllQue($matiere);
			}
			else
				$tab = $manager->getAllQue();
			
			foreach($tab as $question)
			{
				// faut mettre une check box devant chaque question
				echo '<div class="checkQue">
					<input type="checkbox" id="'.$question->idQuestion().'" name="'.$question->idQuestion().'" value="'.$question->idQuestion().'"';
				if(!empty($err) && $err == 1 && !empty($_POST[$question->idQuestion()]))
					echo 'checked';
				echo'>
					<label for="'.$question->idQuestion().'">' ;
				$question->afficher();
				echo '	</label>
				</div>';
			}
			?>
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