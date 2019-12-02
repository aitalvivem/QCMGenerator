<?php
// cette page contiens un corp de page et un dispacher

session_start();

// si c'est un prof
if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant')
{
	// si l'utilisateur a demandé une page on la charge
	if(!empty($_GET['page']))
		$_SESSION['page'] = $_GET['page'];
	else
		$_SESSION['page'] = 0;
	
	// si c'est la primiere connection d'un utilisateur
	if(!empty($_SESSION['premiereCo']) && $_SESSION['premiereCo'] == true){
		$_SESSION['page'] = 5;
	}
	
	
?>
<html>
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="script_qcm" />
		<link rel="shortcut icon" href="img/priorities-checkbox.png" />
		<title>QCMGenerator</title>
	</head>
	<body>
		<?php
		include('navEns.php');
		?>
		<div class="corp" >
			<?php
			//la on switche une variable SESSION['page'] pour savoir quelle page on include
			switch($_SESSION['page'])
			{
				case 1:
					include('ajoutQuestion.php');
					break;
				case 2:
					include('creeEpr.php');
					break;
				case 3:
					include('debuterEpr.php');
					break;
				case 4:
					include('afficheEpr.php');
					break;	
				case 5:
					include('premiereCo.php');
					break;
				default:
					include('creeEpr.php');
			}
			?>
		</div>
	</body>
</html>
<?php
}
else
{
	header('Location: bye.php');
	exit();
}

?>