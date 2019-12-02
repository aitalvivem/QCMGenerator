<?php
// cette page contiens un corp de page et un dispacher

session_start();

// si c'est un admin
if(isset($_SESSION['login']) && $_SESSION['login'] == 'enseignant' && $_SESSION['admin'] == 1)
{
	if(isset($_GET['page']))
		$_SESSION['page'] = $_GET['page'];
	else
		$_SESSION['page'] = 0;
	
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
		include('navAdm.php');
		?>
		<div class="corp" >
		<?php
		//la on switche une variable SESSION['page'] pour savoir quelle page on include
		switch($_SESSION['page'])
		{
			case 1:
				include('ajout.php');
				break;
			case 2:
				include('suppression.php');
				break;
			case 3:
				include('arretEpr.php');
				break;
			default:
				include('ajout.php');
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