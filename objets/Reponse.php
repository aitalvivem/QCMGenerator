<?php
class Reponse
{
	private $_idReponse;
	private $_reponse;	//une cc
	private $_vrai;	//un int
	private $_rang;	//un caractere A,B,C,D
	private $_idQuestion;	//un int, l'id de la question
	
	
	public function __construct(array $donnees)
	{
		$this->hydrate($donnees);
	}

	public function hydrate(array $donnees)	//hydratation
	{
		foreach($donnees as $key => $value)
		{
			$method = 'set'.ucfirst($key);
			
			if(method_exists($this, $method))
			{
				$this->$method($value);
			}
		}
	}
	
	
//methodes
	public function afficher()
	//un bout de formulaire qui affiche la réponse (pour la selection)
	{
		echo '<div class="reponse" >
			<strong>'.$this->rang().')</strong> '.$this->reponse().'
		</div>' ;
	}
	
	
//mutateurs
	public function setIdReponse($id)
	{
		$id = (int) $id;
		
		if($id > 0)
		{
			$this->_idReponse = $id;
		}
	}
	
	public function setReponse($rep)
	{
		if(is_string($rep))
			$this->_reponse = $rep;
	}
	
	public function setVrai($vrai)
	{
		$vrai = (int) $vrai;
		
		$this->_vrai = $vrai;
	}
	
	public function setRang($rang)
	{
		$choix = array('A', 'a', 'B', 'b', 'C', 'c', 'D', 'd');
		
		if(in_array($rang, $choix))
			$this->_rang = $rang;
	}
	
	public function setIdQuestion($idQue)
	{
		$idQue = (int) $idQue;
		
		if($idQue > 0)
			$this->_idQuestion = $idQue;
	}
	
	
//accesseurs
	public function idReponse()
	{
		return $this->_idReponse;
	}
	
	public function reponse()
	{
		return $this->_reponse;
	}
	
	public function vrai()
	{
		return $this->_vrai;
	}
	
	public function rang()
	{
		return $this->_rang;
	}
	
	public function idQuestion()
	{
		return $this->_idQuestion;
	}
}

?>