<?

# PHP News Reader
# Copyright (C) 2001-2007 Shen Cheng-Da
# 
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

session_start();

require_once('readcfg.inc.php');
require_once('html.inc.php');

if( !isset($_POST['target'], $_POST['loginName'], $_POST['passWord'] )   ) {
	header('Location: index.php');
	exit;
}

$user = $_POST['loginName'];
$pass = $_POST['passWord'];

if( isset( $_POST['domain'] ) )
	$user .= $_POST['domain'];

$xref = $_POST['target'];

/* Check for valid session before performing authentication */
if( $_SESSION['current_session_id'] == session_id() && !in_array( $user, $CFG['auth_deny_users']) )
	$info = check_user_password( $user, $pass );
else
	$info = false;

if( $info ) {
	$now = time();

	$_SESSION['auth_time']   = $now;
	$_SESSION['auth_name']   = $user;
	$_SESSION['auth_with']   = 'form';
	$_SESSION['auth_info']   = $info;
	$_SESSION['auth_ticket'] = md5( $user . session_id() . $now );

	header("Location: $xref");
}
else {
	unset($_SESSION['auth_ticket']);
	$retmsg = sprintf( $pnews_msg['ReadyReturn'], $xref );

	$region = $curr_language;
	$coding = $lang_coding[$region];

	echo <<<EOM
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache" />
<META HTTP-EQUIV=REFRESH CONTENT="3; URL=$xref" />
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=$coding" />
<META HTTP-EQUIV="Content-Language" CONTENT="$region" />
<STYLE>
body       { font-size: 12px; color: black; background: #EEEEFF; font-family: arial }
a          { text-decoration:none; color: blue }
a:visited  { color: blue }
a:hover    { text-decoration:underline; color:red }
hr         { height: 1pt; color: #8080A0 }
</STYLE>
<title>$pnews_msg[AuthFail]</title>
</head>
<body>
<center>
<br />
<br />
<font size=+1 face=Georgia>$pnews_msg[AuthFail]</font>
<br />
<br />
<br />
<font face=face=Georgia>$retmsg</font>
</center>
</body>
</html>

EOM;
}

?>
