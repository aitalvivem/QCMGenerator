<?php
class Manager
{
	private $_pdo;
	
	// A tester/débugger
		// - updateRep
		// - updateQue
		// - delRep
		// - delEpr
		// - delQue
		
	// Peut etre utile ?
		// public function updateQcm(Qcm $qcm)
		// public function delQcm(Qcm $qcm)
	
	
	public function __construct($pdo)
	{
		$this->setPdo($pdo);
	}
	
	
	
// AJOUTS
	
	public function addEpr(Epreuve $epr)
	{
		// on récupère idEnseignant en fonction de nom et prenom
		$q = $this->_pdo->query('SELECT
									idEnseignant
								FROM
									enseignant
								WHERE
									nomEnseignant = \''.$epr->nomEnseignant().'\' AND
									prenomEnseignant = \''.$epr->prenomEnseignant().'\''
								);
								
		$rep = $q->fetch(PDO::FETCH_ASSOC);
		
		foreach($rep as $key => $value)
			$idEnseignant = (int) $value;
		
		// on enregistre libelle et idEnseignant dans epreuve
		$q = $this->_pdo->prepare('INSERT INTO
										epreuve(libelle,
												idEnseignant)
									VALUES
										(:libelle,
										:idEnseignant)'
								);
								
		$q->bindValue(':libelle', $epr->libelle());
		$q->bindValue(':idEnseignant', $idEnseignant);
		
		$q->execute();
		
		// on recupere la derniere id
		$idEpreuve = $this->_pdo->lastInsertId();
		
		// on enregistre chaque idQuestion avec idEpreuve dans composer
		$q = $this->_pdo->prepare('INSERT INTO
											composer(idEpreuve,
													idQuestion)
										VALUES
											(:idEpreuve,
											:idQuestion)');
											
		$q->bindValue(':idEpreuve', $idEpreuve);
		
		foreach($epr->questions() as $que)
		{
			if($que instanceof Question)
			{
				$q->bindValue(':idQuestion', $que->idQuestion());
				$q->execute();
			}
			else
				echo 'Nan je ne peut pas enregistrer idEpreuve = '.$idEpreuve.' et idQuestion = '.$que->idQuestion().' car $que n\'est pas une instance de Question' ;
		}
	}
	
	public function addQue(Question $que)
	{
		// on ajoute la question dans question
		$q = $this->_pdo->prepare('INSERT INTO
										question(question,
												idMatiere)
									VALUES
										(:question,
										:idMatiere)');
		
		$q->bindValue(':question', $que->question());
		$q->bindValue(':idMatiere', $que->idMatiere(), PDO::PARAM_INT);

		$q->execute();
		
		$idQue = $this->_pdo->lastInsertId();
		
		// on ajoute les reponses à la question dans reponse
		foreach($que->reponses() as $rep)
		{
			if($rep instanceof Reponse)
			{
				$rep->setIdQuestion($idQue);
				$this->addRep($rep);
			}
			else
				echo 'Nan je ne peut pas enregistrer $rep dans reponses car ce n\'est pas une instance de Reponse';
		}
	}
	
	public function addRep(Reponse $rep)
	// ajoute une reponse à une question
	{
		$q = $this->_pdo->prepare('INSERT INTO
										reponse(reponse,
												vrai,
												rang,
												idQuestion)
									VALUES
										(:reponse,
										:vrai,
										:rang,
										:idQuestion)');
										
		$q->bindValue(':reponse', $rep->reponse());
		$q->bindValue(':vrai', $rep->vrai(), PDO::PARAM_INT);
		$q->bindValue(':rang', $rep->rang());
		$q->bindValue(':idQuestion', $rep->idQuestion(), PDO::PARAM_INT);
		
		$q->execute();
	}
		
	public function addMat($matiere)
	// ajoute une matiere à la bdd
	{
		$matiere = ucfirst($matiere);
		
		$q = $this->_pdo->prepare('INSERT INTO
										matiere(libMatiere)
									VALUES(:libMatiere)');
									
		$q->bindValue(':libMatiere', $matiere);
		
		$q->execute();
	}
		
	public function addQcm(Qcm $qcm)
	// enregistre idEpreuve(c'est une epreuve qui existe deja), date, idClasse dans passer
	{
		//on recupere la classe
		$q = $this->_pdo->query('SELECT
									idClasse
								FROM
									classe
								WHERE
									libClasse = \''.$qcm->classe().'\'');
									
		$rep = $q->fetch(PDO::FETCH_ASSOC);
		
		foreach($rep as $key => $value)
			$idClasse = (int) $value;
		
		// on prepare la requete a enregistrer dans passer
		$q = $this->_pdo->prepare('INSERT INTO
										passer(idClasse,
												idEpreuve,
												date)
									VALUES
										(:idClasse,
										:idEpreuve,
										:date)');
										
		$q->bindValue(':idClasse', $idClasse, PDO::PARAM_INT);
		$q->bindValue(':idEpreuve', $qcm->epreuve()->idEpreuve(), PDO::PARAM_INT);
		$q->bindValue(':date', $qcm->dateQcm());
		
		$q->execute();
	}
	
	public function addQcmRep(array $donnees)
	// enregistre les réponses de l'eleve dans réaliser
	{
		$q = $this->_pdo->prepare('INSERT INTO
										realiser(idQuestion,
												idEleve,
												idEpreuve,
												idReponse,
												date)
									VALUES
										(:idQuestion,
										:idEleve,
										:idEpreuve,
										:idReponse,
										:date)');
										
		$q->bindValue(':idQuestion', $donnees['idQuestion'], PDO::PARAM_INT);
		$q->bindValue(':idEleve', $donnees['idEleve'], PDO::PARAM_INT);
		$q->bindValue(':idEpreuve', $donnees['idEpreuve'], PDO::PARAM_INT);
		$q->bindValue(':idReponse', $donnees['idReponse'], PDO::PARAM_INT);
		$q->bindValue(':date', $donnees['date']);
		
		$q->execute();
	}
	
	public function addClasse(array $donnees)
	// enregistre une nouvelle classe dans classe
	{
		$q = $this->_pdo->prepare('INSERT INTO
										classe(libClasse,
												niveau,
												effectif)
									VALUES
										(:libClasse,
										:niveau,
										:effectif)');
										
		$q->bindValue(':libClasse', $donnees['libClasse']);
		$q->bindValue(':niveau', $donnees['niveau']);
		$q->bindValue(':effectif', $donnees['effectif'], PDO::PARAM_INT);
		
		$q->execute();
	}		
	
	public function addElv(array $donnees)
	{
		// on construit le login et le noms/prenom
		
		// pour les noms prénoms	
		$nom = $donnees['nomEleve'];
		$pre = $donnees['prenomEleve'];
			// on met la premiere lettre en majuscule (en concervant l'accent)
		$pre = mb_strtoupper( mb_substr( $pre, 0, 1 )) . mb_substr( $pre, 1 );
		$nom = mb_strtoupper( mb_substr( $nom, 0, 1 )) . mb_substr( $nom, 1 );
		
		// pour le login
			// on vire les accents
		$preL = iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', $donnees['prenomEleve'] );
		$nomL = iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', $donnees['nomEleve'] );
			// on enleve les apostrophes
		$nomL = str_replace( array('\'','"'), '', $nomL);
		$preL = str_replace( array('\'','"'), '', $preL);
			// on enleve les espaces du nom pour le login
		$nomL = str_replace(' ', '', $nomL);
		
		// on construit le login et on met en minuscule
		$login = strtolower(substr($preL, 0, 1).'.'.$nomL) ;
		
		// on met un mot de passe par défaut qu'on changera lors de la premiere connexion
		$mdp = 'azerty';
		
		// on prepare la requete
		$q = $this->_pdo->prepare('INSERT INTO
									eleve(nomEleve,
											prenomEleve,
											loginElv,
											mdpElv,
											idClasse)
									VALUES
										(:nomEleve,
										:prenomEleve,
										:loginElv,
										:mdpElv,
										:idClasse)');
										
		$q->bindValue(':nomEleve', ucfirst($nom));
		$q->bindValue(':prenomEleve', ucfirst($pre));
		$q->bindValue(':loginElv', $login);
		$q->bindValue(':mdpElv', $mdp);
		$q->bindValue(':idClasse', $donnees['idClasse'], PDO::PARAM_INT);
		
		$q->execute();
	}
	
	public function addEns(array $donnees)
	{
		// on prepare les nom/prénom
		$nom = $donnees['nomEnseignant'];
		$pre = $donnees['prenomEnseignant'];
			// on met la premiere lettre en majuscule (en concervant l'accent)
		$pre = mb_strtoupper( mb_substr( $pre, 0, 1 )) . mb_substr( $pre, 1 );
		$nom = mb_strtoupper( mb_substr( $nom, 0, 1 )) . mb_substr( $nom, 1 );
		
		// pour le login
			// on vire les accents
		$preL = iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', $donnees['prenomEnseignant'] );
		$nomL = iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', $donnees['nomEnseignant'] );
			// on enleve les apostrophes
		$nomL = str_replace( array('\'','"'), '', $nomL);
		$preL = str_replace( array('\'','"'), '', $preL);
			// on enleve les espaces du nom pour le login
		$nomL = str_replace(' ', '', $nomL);
		
		// on construit le login et on met en minuscule
		$login = strtolower(substr($preL, 0, 1).'.'.$nomL) ;
		
		// mot de passe par défaut
		$mdp = 'azerty';
		
		// préparation de la requete et execution
		$q = $this->_pdo->prepare('INSERT INTO
									enseignant(nomEnseignant,
											prenomEnseignant,
											loginEns,
											mdpEns,
											admin)
									VALUES
										(:nomEnseignant,
										:prenomEnseignant,
										:loginEns,
										:mdpEns,
										:admin)');
										
		$q->bindValue(':nomEnseignant', ucfirst($nom));
		$q->bindValue(':prenomEnseignant', ucfirst($pre));
		$q->bindValue(':loginEns', $login);
		$q->bindValue(':mdpEns', $mdp);
		$q->bindValue(':admin', $donnees['admin'], PDO::PARAM_INT);
		
		$q->execute();
	}
	
	
// MISE A JOUR
	
	public function updateEpr(Epreuve $epr)
	{
		$q= $this->_pdo->prepare('UPDATE
										epreuve
									SET
										libelle = :libelle,
										enseignant = :enseignant,
										');
										
		// on update les questions de l'epreuve
		foreach($epr->questions() as $key => $value)
		{
			$this->updateQue($value);
		}
	}
	
	public function updateQue(Question $que)
	{
		// on update la question
		$q = $this->_pdo->prepare('UPDATE
										question
									SET
										question = :question,
										idMatiere = :idMatiere
									WHERE
										idQuestion = :idQuestion
									');
									
		$q->bindValue(':question', $que->question());
		$q->bindValue(':idMatiere', $que->idMatiere(), PDO::PARAM_INT);
		$q->bindValue(':idQuestion', $que->idQuestion(), PDO::PARAM_INT);
		
		$q->execute();
		
		//on update les réponses de la question
		foreach($que->reponses() as $key => $value)
		{
			$this->updateRep($value);
		}
	}
	
	public function updateRep(Reponse $rep)
	{
		$q = $this->_pdo->prepare('UPDATE
										reponse
									SET
										reponse = :reponse,
										vrai = :vrai,
										rang = :rang,
										idQuestion = :idquestion)
									WHERE
										idReponse = :idReponse
									');
									
		$q->bindValue(':reponse', $rep->reponse());
		$q->bindValue(':vrai', $rep->vrai(), PDO::PARAM_INT);
		$q->bindValue(':rang', $rep->rang());
		$q->bindValue(':idquestion', $rep->idQuestion(), PDO::PARAM_INT);
		$q->bindValue(':idReponse', $rep->idReponse(), PDO::PARAM_INT);
		
		$q->execute();
	}
	
	public function updateMdp(int $id, string $table, string $newMdp)
	// fonction qui met a jour le mdp d'un utilisateur, retourne 1 si probleme sinon 0
	{
		if($table == 'eleve'){
			$q = $this->_pdo->prepare('UPDATE
											eleve
										SET
											mdpElv = :newMdp
										WHERE
											idEleve = :id
										');
										
			$q->bindValue(':newMdp', $newMdp);
			$q->bindValue(':id', $id, PDO::PARAM_INT);
			
			$q->execute();
			
			return 0;
		}elseif($table == 'enseignant'){
			$q = $this->_pdo->prepare('UPDATE
											enseignant
										SET
											mdpEns = :newMdp
										WHERE
											idEnseignant = :id
										');
										
			$q->bindValue(':newMdp', $newMdp);
			$q->bindValue(':id', $id, PDO::PARAM_INT);
			
			$q->execute();
			
			return 0;
		}else
			return 1;
	}

	public function updateEns(int $idAdmin, int $admin)
	{
		$q = $this->_pdo->prepare('UPDATE
										enseignant
									SET
										admin = :admin
									WHERE
										idEnseignant = :idEnseignant
									');
									
		$q->bindValue(':admin', $admin, PDO::PARAM_INT);
		$q->bindValue(':idEnseignant', $idAdmin, PDO::PARAM_INT);

		$q->execute();
	}


// SELECT
	
	public function getEpr($info)
	// recupere une epreuve selon une idEpreuve
	{
		if(is_int($info))
		{
			//on recupere l'epreuve
			$req = $this->_pdo->prepare('SELECT 
											idEpreuve,
											libelle,
											nomEnseignant,
											prenomEnseignant
										FROM
											epreuve,
											enseignant
										WHERE
											epreuve.idEnseignant = enseignant.idEnseignant AND
											idEpreuve = ?');
			
			$req->execute(array($info));
			$donnees = $req->fetch(PDO::FETCH_ASSOC);
			
			$epr = new Epreuve(array(
									'idEpreuve' => $donnees['idEpreuve'], 
									'libelle' => $donnees['libelle'], 
									'nomEnseignant' => $donnees['nomEnseignant'],
									'prenomEnseignant' => $donnees['prenomEnseignant']
								));
			
			$req->closeCursor();
			
			//le tab contenant les questions
			$tabQ = [];
			$tabId = [];
			
			$req1 = $this->_pdo->prepare('SELECT
											idQuestion
										FROM
											composer,
											epreuve
										WHERE
											composer.idEpreuve = epreuve.idEpreuve AND
											epreuve.idEpreuve = ?');
			
			$req1->execute(array($info));
			while($question = $req1->fetch(PDO::FETCH_ASSOC))
			{
				$tabId[] = (int) $question['idQuestion'];
			}
			
			foreach($tabId as $key => $value)
			{
				$que = $this->getQue($value);
				$tabQ[] = $que;
			}

			$req1->closeCursor();

			$epr->setQuestions($tabQ);
			
			return $epr;
		}
	}
	
	public function getAllEpr()
	// recupère l'ensemble des épreuves enregistrées, retourne un tableau d'epreuves
	{
		$reponse = $this->_pdo->query('SELECT
											idEpreuve
										FROM
											epreuve');
		
		$tabEpr = [];
		
		while($donnees = $reponse->fetch(PDO::FETCH_ASSOC))
		{
			$id = (int) $donnees['idEpreuve'];
			$tabEpr[] = $this->getEpr($id);
		}
		
		$reponse->closeCursor();
		
		return $tabEpr;
	}
		
	public function getListEpr(int $idClasse, int $idEleve, $date)
	// on recupere les id et libellé des epreuves démarées et que l'eleve n'a pas deja réalisé pour un date et classe
	{
		$req = $this->_pdo->prepare('SELECT
									passer.idEpreuve,
									libelle
								FROM
									passer,
									epreuve
								WHERE
									idClasse = :idClasse AND
									date = :date AND
									epreuve.idEpreuve = passer.idEpreuve AND
									passer.idEpreuve NOT IN(SELECT 
														realiser.idEpreuve
													FROM 
														realiser
													WHERE 
														idEleve = :idEleve AND
														realiser.date = :date)
								');
								
		$req->bindValue(':idClasse', $idClasse, PDO::PARAM_INT);
		$req->bindValue(':idEleve', $idEleve, PDO::PARAM_INT);
		$req->bindValue(':date', $date);
		
		$req->execute();
		
		$epreuve = [];
		$i = 1;
		// on crée un tableau dim 2
		while($donnees = $req->fetch(PDO::FETCH_ASSOC))
		{
			$epreuve[$i]['idEpreuve'] = (int) $donnees['idEpreuve'];
			$epreuve[$i]['libelle'] = $donnees['libelle'];
			$i++;
		}
		
		$req->closeCursor();
		
		return $epreuve;
	}
	
	public function getEprNbQue($idEpreuve)
	// recupere le nombre de question d'une épreuve
	{
		$q = $this->_pdo->query('SELECT COUNT(idQuestion) AS max
							FROM composer 
							WHERE idEpreuve ='.$_SESSION['idEpreuve'].'
							GROUP BY idEpreuve');
							
		$max = $q->fetch();
		
		$q->closeCursor();
		
		return $max;
	}
	
	public function getQue($info)
	// recupere un question selon un idQuestion
	{
		//il faut aller chercher la question et les réponses correspondantes
		if(is_int($info))
		{
			//on recupere la question
			$req = $this->_pdo->prepare('SELECT
											idQuestion,
											question,
											idMatiere
										FROM
											question
										WHERE
											idQuestion = ?');
			
			$req->execute(array($info));
			$donnees = $req->fetch(PDO::FETCH_ASSOC);
			
			$req->closeCursor();
			
			$que = new Question($donnees);
			
			//la on ajoute des réponses
			$que->setReponses($this->getRep($info));

			return $que;
		}
	}
	
	public function getAllQue(int $mat = 0)
	// recupere l'ensemble des questions, retourne un tableau d'objets question
	{
		// on recupere toute les idQuestion
		$q = 'SELECT
					idQuestion
				FROM
					question';
		
		if(!empty($mat))
		{
			$q = $q.' WHERE question.idMatiere = :idMatiere';
			$req = $this->_pdo->prepare($q);
			$req->bindValue(':idMatiere', $mat, PDO::PARAM_INT);
		}
		else
			$req = $this->_pdo->prepare($q);
		
		$req->execute();
		
		$tabQ = []; // un tableau qui contiendra les questions
		
		while($donnees = $req->fetch(PDO::FETCH_ASSOC))
		{
			$donnees['idQuestion'] = (int) $donnees['idQuestion'];
			$tabQ[] = $this->getQue($donnees['idQuestion']);
		}
		
		$req->closeCursor();
		
		return $tabQ;
	}
	
	public function getRep($info)
	// recupere un tableau de Reponses selon un idQuestion
	{
		if(is_int($info))
		{
			$req = $this->_pdo->prepare('SELECT
											idReponse,
											reponse,
											vrai,
											rang,
											idQuestion
										FROM
											reponse
										WHERE
											idQuestion = ?');
											
			$req->execute(array($info));
			
			$tabR = [];
			
			while($donnees = $req->fetch(PDO::FETCH_ASSOC))
			{
				$tabR[] = new Reponse($donnees);
			}
			
			$req->closeCursor();
			
			return $tabR;
		}
	}
	
	public function getRepOnly($info)
	// recupère une reponse via une idReponse
	{
		if(is_int($info))
		{
			$req = $this->_pdo->query('SELECT
											idReponse,
											reponse,
											vrai,
											rang,
											idQuestion
										FROM
											reponse
										WHERE
											idReponse = ?');
			
			$req->execute(array($info));
			$donnees = $req->fetch(PDO::FETCH_ASSOC);
			
			$req->closeCursor();
			
			return new Reponse($donnees);
		}
	}
	
	public function getQcm($idEpreuve, $libclasse, $date)
	// recupère un qcm via une idEpreuve, un libClasse et une date
	{
		$qcm = New Qcm(array(
						'epreuve' => $this->getEpr($idEpreuve),
						'classe' => $libclasse,
						'dateQcm' => $date,
						));
						
		return $qcm;
	}
	
	public function getMdp($qualite, $log)
	// récupère un mot de passe selon un login donné
	{
		if($qualite == 'ens')
		{
			$q = $this->_pdo->prepare('SELECT mdpEns FROM enseignant WHERE loginEns = :loginEns');
			$q->bindValue(':loginEns', $log);
		}
		elseif($qualite == 'elv')
		{
			$q = $this->_pdo->prepare('SELECT mdpElv FROM eleve WHERE loginElv = :loginElv');
			$q->bindValue(':loginElv', $log);
		}	

		$q->execute();
		
		$mdp = $q->fetch(PDO::FETCH_ASSOC);
		
		foreach($mdp as $key => $value)
			$mdp = $value;
			
		$q->closeCursor();
		
		return $mdp;
	}
	
	public function getEns($log)
	// cette fonction récupère les nom et prénom d'un enseignant selon un login et les enregistre dans session
	{
		$q = $this->_pdo->prepare('SELECT
										idEnseignant,
										nomEnseignant,
										prenomEnseignant,
										admin
									FROM
										enseignant
									WHERE
										loginEns = :loginEns');
									
		$q->bindValue(':loginEns', $log);
		$q->execute();
		
		$nom = $q->fetch(PDO::FETCH_ASSOC);
		
		// on crée les variables $_SESSION['nomEnseignant'], $_SESSION['prenomEnseignant']
		$_SESSION['id'] = (int) $nom['idEnseignant'];
		$_SESSION['nomEnseignant'] = $nom['nomEnseignant'];
		$_SESSION['prenomEnseignant'] = $nom['prenomEnseignant'];
		$_SESSION['login'] = 'enseignant';
		$_SESSION['admin'] = (int) $nom['admin'];
		
		$q->closeCursor();
	}
	
	public function getElv($log)
	// récupère les infos d'un eleve et les enregistre dans $_SESSION
	{
		$q = $this->_pdo->prepare('SELECT
										idEleve,
										nomEleve,
										prenomEleve,
										eleve.idClasse,
										libClasse
									FROM
										eleve,
										classe
									WHERE
										loginElv = :loginElv AND
										eleve.idClasse = classe.idClasse');
								
		$q->bindValue(':loginElv', $log);
		$q->execute();
		
		$nom = $q->fetch(PDO::FETCH_ASSOC);
		
		$q->closeCursor();
		
		// on les mémorise dans la session
		$_SESSION['id'] = (int) $nom['idEleve'];
		$_SESSION['nomEleve'] = $nom['nomEleve'];
		$_SESSION['prenomEleve'] = $nom['prenomEleve'];
		$_SESSION['idClasse'] = (int) $nom['idClasse'];
		$_SESSION['libClasse'] = $nom['libClasse'];
		$_SESSION['login'] = 'eleve';
	}
	
	public function getMat()
	// recupere la liste des matières
	{
		$reponse = $this->_pdo->query('SELECT 
										idMatiere,
										libMatiere 
									FROM 
										matiere');


		$donnees = $reponse->fetchAll();
		
		$reponse->closeCursor();
		
		return $donnees;
	}
	
	public function getListClass()
	// retourne la liste des classes
	{
		$reponse = $this->_pdo->query('SELECT 
										idClasse,
										libClasse
									FROM 
										classe');

		$classes = $reponse->fetchAll();
		
		$reponse->closeCursor();
		
		return $classes;
	}

	public function getListElv()
	// fonction qui retourne un tableau contenant les id/nom/prenom/classes de tous les eleves dans la table eleve
	{
		$reponse = $this->_pdo->query('SELECT
											idEleve,
											nomEleve,
											prenomEleve,
											libClasse
										FROM
											eleve,
											classe
										WHERE
											eleve.idClasse = classe.idClasse');
											
		$eleves = $reponse->fetchAll();
		
		$reponse->closeCursor();
		
		return $eleves;
	}
	
	public function getElvClasse(int $idClasse)
	// fonction qui retourne une liste d'eleve pour un idClasse donné
	{
		$reponse = $this->_pdo->query('SELECT
											idEleve,
											nomEleve,
											prenomEleve
										FROM 
											eleve
										WHERE 
											idClasse = '.$idClasse.'
										ORDER BY nomEleve');
										
		$classe = $reponse->fetchAll();
		
		$reponse->closeCursor();
		
		return $classe;
	}
	
	public function getListAdmin()
	// fonction qui retourne une liste avec les nom/prenom/id des administrateurs
	{
		$reponse = $this->_pdo->query('SELECT
											idEnseignant,
											nomEnseignant,
											prenomEnseignant
										FROM
											enseignant
										WHERE
											admin = 1
										ORDER BY
											nomEnseignant');
											
		$admins = $reponse->fetchAll();
		
		$reponse->closeCursor();
		
		return $admins;
	}
	
	public function getListEns()
	// fonction qui retourne une liste des enseignants qui ne sont pas administrateurs
	{
		$reponse = $this->_pdo->query('SELECT
											idEnseignant,
											nomEnseignant,
											prenomEnseignant
										FROM
											enseignant
										WHERE
											admin = 0
										ORDER BY
											nomEnseignant');
											
		$enseignants = $reponse->fetchAll();
		
		$reponse->closeCursor();
		
		return $enseignants;
	}


// SUPPRESSIONS
	
	public function delEpr(Epreuve $epr)
	{
		// del les associations correspondant à cette epreuve dans composer
		$this->_pdo->exec('DELETE
							FROM composer
							WHERE idEpreuve = '.$epr->idEpreuve());
		
		// supprime l'épreuve de la table epreuve
		$this->_pdo->exec('DELETE
							FROM epreuve
							WHERE idEpreuve = '.$epr->idEpreuve());
	}
	
	public function delQue(Question $que)  
	// supprime une question et les réponses associées
	{
		// on supprime les réponses à la question
		foreach($que->reponses as $key => $value)
		{
			$this->delRep($value);
		}
							
		// on supprime la question
		$this->_pdo->exec('DELETE
							FROM question
							WHERE idQuestion = '.$epr->idQuestion());
	}
	
	public function delRep(Reponse $rep)
	{
		$this->_pdo->exec('DELETE
							FROM reponse
							WHERE idReponse = '.$epr->idReponse());
	}
	
	public function delElv(int $idElv)
	{
		// fonction qui retire un eleve des tables "realiser" et "eleve"
		$this->_pdo->exec('DELETE
							FROM realiser
							WHERE idEleve = '.$idElv);
		
		
		$this->_pdo->exec('DELETE
							FROM eleve
							WHERE idEleve = '.$idElv);
	}
	
	public function delQcm(array $donnees)
	// fonction qui retire un qcm de la table passe selon :
		// - soit un idClasse
		// - soit un idEpreuve
		// - soit une paire (idClasse, idEpreuve)
	{
		if(!empty($donnees['idClasse']) && !empty($donnes['idEpreuve']))
		{
			$idClasse = (int) $donnees['idClasse'];
			$idEpreuve = (int) $donnees['idEpreuve'];
			
			$req = 'DELETE FROM passer WHERE idClasse = '.$idCLasse.' AND idEpreuve = '.$idEpreuve.' AND date = DATE(NOW())';
		}
		else
		{
			if(!empty($donnees['idClasse']))
			{
				$idClasse = (int) $donnees['idClasse'];
				$req = 'DELETE FROM passer WHERE idClasse = '.$idCLasse.' AND date = DATE(NOW())' ;
			}
			elseif(!empty($donnees['idEpreuve']))
			{
				$idEpreuve = (int) $donnees['idEpreuve'];
				$req = 'DELETE FROM passer WHERE idEpreuve = '.$idEpreuve.' AND date = DATE(NOW())' ;
			}
		}
	}
	
	
	// mutateur
	public function setPdo(PDO $pdo)
	{
		$this->_pdo = $pdo;
	}
}
?>