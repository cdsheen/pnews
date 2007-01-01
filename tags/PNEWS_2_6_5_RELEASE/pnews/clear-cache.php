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

if( !isset( $_SERVER['argc'] ) ) {
	echo "Please run this script from command line.";
	exit;
}

if( file_exists('config.inc.php') ) {
	include('config.inc.php');
}
else {
	if( $argc > 1 && file_exists( $_SERVER['argv'][1] ) )
		include($_SERVER['argv'][1]);
	else {
		echo "\nUsage:  " . $_SERVER['argv'][0] . "  [config-file]\n\n";
		echo "ERROR: Can not find 'config.inc.php'\n\n";
		exit;
	}
}


if( $CFG['cache_dir'] )
	clear_cache( preg_replace('#/$#', '', $CFG['cache_dir']) );

function clear_cache( $dir ) {
	echo "[$dir]\n";
	if( file_exists( "$dir/artnum.idx" ) ) {
		echo "     deleting artnum.idx ..\n";
		unlink("$dir/artnum.idx");
	}
	if( file_exists( "$dir/thread.db" ) ) {
		echo "     deleting thread.db  ..\n";
		unlink("$dir/thread.db");
	}
	$d = opendir( $dir );
	while( $f = readdir($d) ) {
		if( is_dir("$dir/$f") && $f[0] != '.' )
			clear_cache( "$dir/$f");
		elseif( preg_match( '/^attach-\d+-/', $f ) )
			unlink( "$dir/$f" );
	}
	closedir($d);
}

?>
