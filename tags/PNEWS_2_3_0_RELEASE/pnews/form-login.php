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

require_once('readcfg.inc.php');
require_once('html.inc.php');

if( !isset($_POST['target'], $_POST['loginName'], $_POST['passWord'] )   ) {
	header('Location: index.php');
	exit;
}

$user = $_POST['loginName'];
$pass = $_POST['passWord'];

$xref = $_POST['target'];

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

header("Location: $xref");

?>
