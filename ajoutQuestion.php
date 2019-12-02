<?php
		
// cette page contient : 
	// le formulaire pour ajouter une question
	// le traitement du formulaire (création d'objets Reponse, puis d'un objet Question puis enregistrement de l'objet Question)
		
if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant')
{
	include('objets/autoloader.php');	//autoloader de classe
	include('coBdd.php');		//connection a la bdd
	include('verifEsp.php');	// la fonction verifEsp
		
	$manager = new Manager($bdd);
	
	// si le formulaire a été envoyé on le traite
	if(!empty($_POST))
	{
		if(!empty($_POST['vrai']))
		{
			// on recupere la vrai réponse
			$vraiA = 0;
			$vraiB = 0;
			$vraiC = 0;
			$vraiD = 0;
			
			switch($_POST['vrai'])
			{
				case 'A':
					$vraiA = 1;
					break;
				case 'B':
					$vraiB = 1;
					break;
				case 'C':
					$vraiC = 1;
					break;
				case 'D':
					$vraiD = 1;
					break;
			}
			
			// on vérifie l'existence des variables
			if(!empty($_POST['A']) && !empty($_POST['B']) && !empty($_POST['C']))
			{
				$reponses = [];
				
				$_POST['A'] = verifEsp($_POST['A']);
				$_POST['B'] = verifEsp($_POST['B']);
				$_POST['C'] = verifEsp($_POST['C']);
				
				// on crée les objets reponse sans id question
				$reponses[]  = New Reponse(array(
								'reponse' => $_POST['A'],
								'vrai' => $vraiA,
								'rang' => 'A'
								));
								
				$reponses[]  = New Reponse(array(
								'reponse' => $_POST['B'],
								'vrai' => $vraiB,
								'rang' => 'B'
								));
				
				$reponses[]  = New Reponse(array(
								'reponse' => $_POST['C'],
								'vrai' => $vraiC,
								'rang' => 'C'
								));
								
				// si D existe, D étant facultatif
				if(!empty($_POST['D']))
				{
					$_POST['D'] = verifEsp($_POST['D']);
					
					$reponses[]  = New Reponse(array(
									'reponse' => $_POST['D'],
									'vrai' => $vraiD,
									'rang' => 'D'
									));
				}
				
				// la faut récupérer l'id de la matiere
				$idMatiere = (int) $_POST['matiere'];
				
				if(!empty($_POST['question']))
				{
					$_POST['question'] = verifEsp($_POST['question']);
					
					// on crée l'objet question
					$question = New Question(array(
													'question' => $_POST['question'],
													'idMatiere' => $idMatiere,
													'reponses' => $reponses
													));
					
					// on enregistre l'objet question
					$manager->addQue($question);
					
					$message = '<p class="confirme">Votre question a bien été enregistrée.</p>';
				}
				else
				{
					$message = '<p class="erreur"><strong>Erreur :</strong> Vous devez renseigner une question.</p>';
					$err = 1;
				}
			}
			else
			{
				$message = '<p class="erreur"><strong>Erreur :</strong> Vous devez renseigner les réponses A, B et C au minimum pour votre questions.</p>';
				$err = 1;
			}
		}
		else
		{
			$message = '<p class="erreur"><strong>Erreur :</strong> Vous devez selectionner bonne la réponse.</p>';
			$err = 1;
		}
	}

	// on affiche le formulaire
	
	// si il y a un message a afficher
	if(!empty($message))
		echo $message;
	
	?>
	<div class="addQ" >
		<form action="#" method="post" >
			<p>
			Bonjour, vous êtes connecté en tant que <strong><?php echo $_SESSION['prenomEnseignant'].' '.$_SESSION['nomEnseignant'] ?></strong><br /><br />
			
			Pour ajouter une question à la base de donnée merci de remplir le formulaire ci-dessous. Il doit y avoir au moins trois réponses par question (la réponse D est facultative).</p>
			<fieldset>
				<legend>Ajouter une question</legend>
				
				<?php 
				// on recupere les matieres
				$donnees = $manager->getMat();
				?>
				
				<label for="matiere" >Matiere :</label>
				<select name="matiere" id="matiere" >
				<?php
				foreach($donnees as $key => $value)
				{
					echo '<option value="'.$value['idMatiere'].'"';
					
					if(!empty($err) && $err == 1 && !empty($_POST['matiere']) && $_POST['matiere'] == $value['idMatiere'])
						echo 'selected';
						
					echo '>'.ucfirst($value['libMatiere']).'</option>
					'; 
				}
				?>
				</select>
				
				<br /><br />
				
				<label for="name">Question :</label><br />
				<textarea name="question" id="question" cols="100" placeholder="Votre Question" ><?php if(!empty($err) && $err == 1 && !empty($_POST['question'])){ echo htmlspecialchars($_POST['question']); } ?></textarea>
				
				<br /><br />
				
				<table>
					<tr>
						<td><label for="A" >Reponse A</label></td>
						<td><input type="text" name="A" <?php if(!empty($err) && $err == 1 && !empty($_POST['A'])){ echo 'value="'.htmlspecialchars($_POST['A']).'"'; } ?>/></td>
						<td><input type="radio" name="vrai" value="A" <?php if(!empty($err) && $err == 1 && !empty($_POST['vrai']) && $_POST['vrai'] == 'A'){ echo 'checked'; } ?>/></td>
					</tr>
					<tr>
						<td><label for="B" >Reponse B</label></td>
						<td><input type="text" name="B" <?php if(!empty($err) && $err == 1 && !empty($_POST['B'])){ echo 'value="'.htmlspecialchars($_POST['B']).'"'; } ?>/></td>
						<td><input type="radio" name="vrai" value="B" <?php if(!empty($err) && $err == 1 && !empty($_POST['vrai']) && $_POST['vrai'] == 'B'){ echo 'checked'; } ?>/></td>
					</tr>
					<tr>
						<td><label for="C" >Reponse C</label></td>
						<td><input type="text" name="C" <?php if(!empty($err) && $err == 1 && !empty($_POST['C'])){ echo 'value="'.htmlspecialchars($_POST['C']).'"'; } ?>/></td>
						<td><input type="radio" name="vrai" value="C" <?php if(!empty($err) && $err == 1 && !empty($_POST['vrai']) && $_POST['vrai'] == 'C'){ echo 'checked'; } ?>/></td>
					</tr>
					<tr>
						<td><label for="D" >Reponse D</label></td>
						<td><input type="text" name="D" <?php if(!empty($err) && $err == 1 && !empty($_POST['D'])){ echo 'value="'.htmlspecialchars($_POST['D']).'"'; } ?>/></td>
						<td><input type="radio" name="vrai" value="D" <?php if(!empty($err) && $err == 1 && !empty($_POST['vrai']) && $_POST['vrai'] == 'D'){ echo 'checked'; } ?>/></td>
					</tr>
				</table>
				
				<br />
				
				<input type="submit" class="dr" value="Ajouter la question" />
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