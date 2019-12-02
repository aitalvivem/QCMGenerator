<?php
// fonction qui vérifie les espaces a la fin d'une chaine de caractère
function verifEsp(string $str)
{
	while(substr($str,-1) == ' ')
	{
		$str = substr($str,0,-1);
	}
	
	return $str;
}
?>