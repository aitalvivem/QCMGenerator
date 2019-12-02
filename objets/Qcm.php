<?php
class Qcm
{
	private $_epreuve;
	private $_classe;
	private $_nomEleve;
	private $_prenomEleve;
	private $_dateQcm;
	
	// -> il faut ajouter tout ce qu'il faut pour gérer le QCM dans le manager /!\
	
	// construct
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
		
		$this->setDateQcm();
	}
	
	// afficheQCM
	public function printQcm()
	{
		
		echo '<div class="qcm">
	<div class="en-tete_qcm" >
		<p>N° Epreuve : <b>'.$this->epreuve()->idEpreuve().'</b><br />
		Epreuve : <b>'.$this->epreuve()->libelle().'</b><br />
		Date : <b>'.$this->dateQcm().'</b><br />
		Classe : <b>'.$this->classe().'</b></p>
		<div>
			<p>ELEVE : <b>'.$this->prenomEleve().' '.$this->nomEleve().'</b></p>
			<p>PROFESSEUR : <b>'.$this->epreuve()->prenomEnseignant().' '.$this->epreuve()->nomEnseignant().'</b></p>
		</div>
	</div>
	';
			$this->epreuve()->printEpr();
			echo '
</div>';
	}

	
// mutateurs
	public function setEpreuve(Epreuve $epr)
	{
		$this->_epreuve = $epr;
	}
	public function setClasse($cla)
	{
		if(is_string($cla))
			$this->_classe = $cla;
	}
	public function setNomEleve($ele)
	{
		if(is_string($ele))
			$this->_nomEleve = $ele;
	}
	public function setPrenomEleve($ele)
	{
		if(is_string($ele))
			$this->_prenomEleve = $ele;
	}
	public function setDateQcm()
	{
		$this->_dateQcm = date('Y-m-d');
	}
	
// accesseur
	public function epreuve()
	{
		return $this->_epreuve;
	}
	public function classe()
	{
		return $this->_classe;
	}
	public function prenomEleve()
	{
		return $this->_prenomEleve;
	}
	public function nomEleve()
	{
		return $this->_nomEleve;
	}
	public function dateQcm()
	{
		return $this->_dateQcm;
	}
}
?>