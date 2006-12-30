<?

# PHP News Reader / POP3 Authentication Module
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

function check_user_password( $username, $password ) {
	global $CFG;

	$popuser = sprintf( str_replace( '%u', '%s', $CFG['pop3_user_modify'] ), $username );

	if( !isset( $CFG['pop3_server']) )
		return(null);

	$server = $CFG['pop3_server'];
	if( strstr( $server, ':' ) )
		list( $server, $port ) = split( ':', $server );
	else
		$port = 110;

	$sock = @fsockopen( $server, $port );
	if( !$sock )
		return(null);

	socket_set_timeout( $sock, 10, 0 );

	$msg = fgets( $sock, 256 );
	if( !strstr( $msg, "+OK" ) ) {
		fclose( $sock );
		return(null);
	}

	fputs( $sock, "USER $popuser\r\n" );

	$msg = fgets( $sock, 256 );
	if( !strstr( $msg, "+OK" ) ) {
		fclose( $sock );
		return(null);
	}

	fputs( $sock, "PASS $password\r\n" );

	$msg = fgets( $sock, 256 );
	if( !strstr( $msg, "+OK" ) ) {
		fclose( $sock );
		return(null);
	}

	fputs( $sock, "QUIT\r\n" );
	fclose( $sock );

	$userinfo['%u'] =  $username;

	return( $userinfo );
}

?>
