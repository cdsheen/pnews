<?

// Copyright (C) 2001-2003 - All rights reserved
// Shen Cheng-Da (cdsheen@csie.nctu.edu.tw)

session_start();

setcookie( 'cookie_language', $_GET['language'], time()+86400*30 );

$_SESSION['session_language'] = $_GET['language'];

include('language.inc.php');

$referal = $_GET['from'];

//header( "Location: $referal" );

echo "<html>
<head>
<META HTTP-EQUIV=\"Pragma\" CONTENT=\"no-cache\">
<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=$referal\">
<LINK REL=STYLESHEET TYPE=\"text/css\" HREF=\"style.css\">
</head>
<center>
<p>
<br>
<br>
<font size=3 face=Georgia>
Changing Interface Language to <b>${_GET['language']}</b> ...
</font>
</center>
</html>";

exit;

?>
