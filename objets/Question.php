<?php
class Question
{
	private $_idQuestion;
	private $_question;
	private $_idMatiere;
	private $_reponses; // un array de Reponse
	
	
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
	{
		echo '<div class="question" >
			<h3>'.$this->question().'</h3>';
		
		$reponse = $this->reponses();
		
		foreach($reponse as $reponse)
		{
			$reponse->afficher();
		}
		echo '</div>';
	}
	
	public function printQ() 
	//affiche la question avec les réponses et la case a cocher dans un formulaire,
	//formulaire crée dans l'objet epreuve
	{
		echo '
		<fieldset>
			<legend><strong>'.$this->question().'</strong></legend>';
		foreach($this->reponses() as $key => $rep)
		{
			echo '<input type="radio" id="'.$rep->idReponse().'" name="'.$this->idQuestion().'" value="'.$rep->idReponse().'"/>
			<label for="'.$rep->idReponse().'">'.$rep->reponse().'</label><br/>';
		}
		echo '</fieldset>';
	}


//mutateur
	public function setIdQuestion($id)
	{
		$id = (int) $id;
		
		if($id > 0)
		{
			$this->_idQuestion = $id;
		}
	}
	
	public function setQuestion($question)
	//un string
	{
		if(is_string($question))
			$this->_question = $question;
	}
	
	public function setIdMatiere($idMatiere)
	//un id l'id de la matiere
	{
		$idMatiere = (int) $idMatiere;
		
		if($idMatiere > 0)
			$this->_idMatiere = $idMatiere;
	}
	
	public function setReponses(array $info)
	//un tableau d'instance de réponses
	{
		$i = 0;
		
		foreach($info as $value)
		{
			if(!($value instanceof Reponse))
				$i = 1;
		}
		
		if($i == 0)
			$this->_reponses = $info;
	}
	
	
//accesseur
	public function idQuestion()
	{
		return $this->_idQuestion;
	}
	
	public function question()
	{
		return $this->_question ;
	}
	
	public function idMatiere()
	{
		return $this->_idMatiere;
	}
	
	public function reponses()
	{
		return $this->_reponses;
	}
}
?>