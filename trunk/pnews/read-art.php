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

include('utils.inc.php');

$artnum = $_GET['artnum'];

if( $server == $group_default_server )
	$reserver = '';
else
	$reserver = $server;

if( $CFG['url_rewrite'] ) {
	$redirect = "$urlbase/article/$reserver/$group/$artnum";
}
else {
	$redirect = "article.php?server=$server&group=$group&artnum=$nextnum";
}

header("Location: $redirect");

?>
