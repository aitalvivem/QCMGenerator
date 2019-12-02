<?php

// il faut demander quel qcm on souhaite supprimmer
	// recherche par classe (retourne les idEpreuve dans passer pour une idClasse donnée)
		// arreter un qcm pour cette classe (PE: idEpreuve, idClasse)
		// arreter tous les qcm pour cette classe (PE: idClasse)
	// recherche par épreuve (retourne les idClasse dans passer pour une idEpreuve donnée)
		// arreter le qcm pour une classe (PE: idEpreuve, idClasse)
		// arreter le qcm pour toute les classes (PE: idEpreuve)
		
		
	// RECHERCHE PAR DATE ?????

// une fois qu'on sait ce qu'il faut supprimer 
	// on crée un tableau associatif $tab
	// on appele $manager->delQcm($tab) dans le manager



// delQcm() :
	// PE: $donnees : array associatif :
		// { 
			// 'idClasse' = int,
			// 'idEpreuve' = int
		// }
		
	// SI (!empty($donnees['idClasse']) && !empty($donnes['idEpreuve'])){
		// on vérifie que c'est des int
			// on effectue la requete pour retirer un qcm selon idClasse et idEpreuve
	// SINON
		// SI (!empty($donnees['idClasse']))
			// on vérifie si c'est un int
				// on effectue la requete pour retirer un qcm selon idClasse
		// SINON SI (!empty($donnees['idEpreuve'])			
			// on vérifie si c'est un int
				// on effectue la requete pour retirer un qcm selon idEpreuve

?>