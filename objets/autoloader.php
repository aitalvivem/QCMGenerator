<?php

//autoloader de classe
function chargerClasse($classe)
{
	require $classe.'.php';
}
spl_autoload_register('chargerClasse');

?>