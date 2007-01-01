<?

# PHP News Reader / Mail Authentication Module
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

	if( !isset( $CFG['pop3_mapping'] ) )
		return(null);

	$popuser = '';
	foreach( $CFG['pop3_mapping'] as $d => $s ) {
		if( preg_match( "/^(.+)($d)$/", $username, $match ) ) {
			$nick = $match[1];
			list($popuser, $domain) = split( '@', $username );
			$server = $s;
		}
	}

	if( $popuser == '' )
		return(null);

/*
	$domain = strstr( $username, '@' );
	if( !$domain || !isset( $CFG['pop3_mapping'][$domain] ) )
		return(null);

	$popuser = preg_replace( '/@.+$/', "", $username );

	$server = $CFG['pop3_mapping'][$domain];
*/

	if( preg_match( '/^(\w+):\/\/([^\/]+)\/?$/', $server, $match ) ) {
		$protocol = $match[1];
		$server = $match[2];
	}

	if( $protocol == 'pop3s' )
		$default_port = 995;	/* POP3 over SSL */
	else
		$default_port = 110;	/* POP3 */

	if( strstr( $server, ':' ) )
		list( $server, $port ) = split( ':', $server );
	else
		$port = $default_port;

	if( $protocol == 'pop3s' )
		$sock = @fsockopen( "ssl://$server", $port, $errno, $errstr, 5 );
	else
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

	$userinfo['%u'] = $nick;
	$userinfo['%e'] = $username;

	return( $userinfo );
}

?>
