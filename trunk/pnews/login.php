<?

// Copyright (C) 2001-2003 - All rights reserved
// Shen Cheng-Da (cdsheen@csie.nctu.edu.tw)

session_start();

require_once('readcfg.inc.php');
require_once('html.inc.php');

if( !isset($_POST['target'], $_POST['loginName'], $_POST['passWord'] )   ) {
	header('Location: index.php');
	exit;
}

$user = $_POST['loginName'];
$pass = $_POST['passWord'];

$uri  = $_POST['target'];

$info = check_user_password( $user, $pass );

if( $info ) {
	$now = time();

	$_SESSION['auth_time']   = $now;
	$_SESSION['auth_name']   = $user;
	$_SESSION['auth_with']   = 'form';
	$_SESSION['auth_info']   = $info;
	$_SESSION['auth_ticket'] = md5( $user . session_id() . $now );
}
else
	unset($_SESSION['auth_ticket']);

header("Location: $uri");

?>
