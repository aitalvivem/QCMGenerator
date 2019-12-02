<?php
class Epreuve
{
	private $_idEpreuve;
	private $_libelle;
	private $_nomEnseignant;
	private $_prenomEnseignant;
	private $_questions;
	
	
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
		echo '<div class="epreuve">
			<p>
			<strong>Libelle de l\'épreuve : </strong>'.$this->libelle().'<br/>
			<strong>Enseignant : </strong>'.$this->prenomEnseignant().' '.$this->nomEnseignant().'
			</p>';
			
		$questions = $this->questions();
		
		foreach($questions as $questions)
		{
			echo '<div class="checkQue">';
			$questions->afficher();
			echo '</div>';
		}
		echo '</div>';
	}
	
	public function printEpr()
	//affiche le formulaire de l'épreuve
	{
		//la faut commencer le formulaire
		echo '<form action="dispacher2.php?page=3" method="post" >';
			
		foreach($this->questions() as $que)
		{
			$que->printQ();
		}
		//bouton valider du formulaire
		echo '<input class="dr" type="submit" name="sent" />
		</form>';
	}
	
	public function shortEpr()
	// affiche un résumé de l'épreuve
	{
		// on recupere le nb de questions
		$nbQ = 0;
		
		while(!empty($this->questions()[$nbQ]))
		{
			$nbQ++;
		}

		echo '<div class="shortEpr">
			<p><strong>Intitullé de l\'épreuve :</strong> '.$this->libelle().'<br/>
			<strong>Crée par :</strong> '.$this->prenomEnseignant().' '.$this->nomEnseignant().'<br/>
			<strong>Nombre de questions :</strong> '.$nbQ.'
			<br /><br />
			<a href="dispacher.php?page=4&e='.$this->idEpreuve().'">
				<button>Afficher l\'épreuve</button>
			</a>
			</p>
		</div>
		';
	}
	
	
//mutateurs
	public function setIdEpreuve($id)
	{
		$id = (int) $id;
		
		if($id > 0)
			$this->_idEpreuve = $id;
	}
	
	public function setLibelle($lib)
	{
		if(is_string($lib))
			$this->_libelle = $lib;
	}
	
	public function setNomEnseignant($ens)
	{
		if(is_string($ens))
			$this->_nomEnseignant = $ens;
	}
	
	public function setprenomEnseignant($ens)
	{
		if(is_string($ens))
			$this->_prenomEnseignant = $ens;
	}
	
	public function setQuestions(array $info)
	//enregistre un tableau de questions dans l'epreuve
	{
		$i = 0;
			
		foreach($info as $key => $value)
		{
			if(!($value instanceof Question))
				$i = 1;
		}
		
		if($i == 0)
			$this->_questions = $info;
	}
	
	
//accesseurs
	public function idEpreuve()
	{
		return $this->_idEpreuve;
	}
	
	public function libelle()
	{
		return $this->_libelle;
	}
	
	public function nomEnseignant()
	{
		return $this->_nomEnseignant;
	}
	
	public function prenomEnseignant()
	{
		return $this->_prenomEnseignant;
	}
	
	public function questions()
	{
		return $this->_questions;
	}
}

?>