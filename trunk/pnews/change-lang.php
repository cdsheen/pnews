<?

# PHP News Reader
# Copyright (C) 2001-2003 Shen Chang-Da
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
