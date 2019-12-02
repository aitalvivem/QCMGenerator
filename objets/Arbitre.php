<?php
class Arbitre
{
	private $_pdo;
	
	public function __construct($pdo)
	{
		$this->setPdo($pdo);
	}


	public function verifRep(array $info)
	// PE : array envoyé par formulaire
	// PS : resultat, un int, le score
	{
		$resultat = 0;
		
		foreach($info as $key => $value)
		{
			if(is_int($key))
			{
				$value = (int) $value;
				
				$req = $this->_pdo->prepare('SELECT
												vrai
											FROM
												reponse
											WHERE
												idReponse = '.$value.' AND 
												idQuestion = '.$key
											);
				
				$req->bindValue(':rang', $value, PDO::PARAM_INT);
				$req->bindValue(':idQuestion', $key, PDO::PARAM_INT);
								
				$req->execute();
								
				while($donnees = $req->fetch())
				{
					$donnees['vrai'] = (int) $donnees['vrai'];
					
					if($donnees['vrai'] == true)
						$resultat++;
				}
			}
		}
		
		return $resultat;
	}
	
// mutateur
	public function setPdo(PDO $pdo)
	{
		$this->_pdo = $pdo;
	}
}
?>