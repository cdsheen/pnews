<?

# PHP News Reader / PostgesSQL Authentication Module
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

	if( !isset( $CFG['db_server'], $CFG['db_name'], $CFG['db_table'], $CFG['db_username'], $CFG['db_password'], $CFG['db_field_username'] ) )
		return(null);

	$server = $CFG['db_server'];
	if( strstr( $server, ':' ) )
		list( $server, $port ) = split( ':', $server );
	else
		$port = 5432;

	$conn_string = sprintf( "host=%s port=%d dbname=%s user=%s password=%s", $server, $port, $CFG['db_name'], $CFG['db_username'], $CFG['db_password'] );
	$conn = pg_connect( $conn_string );
	if( !$conn )
		return(null);

	$extra_fields = '';
	if( isset($CFG['db_variable']) )
		foreach( $CFG['db_variable'] as $var => $field )
			$extra_fields .= ",$field";

	$escape_user = str_replace( "'", "''", $username );
	$sql_string = sprintf( "SELECT %s,%s%s FROM %s WHERE %s='%s'", $CFG['db_field_username'], $CFG['db_field_password'], $extra_fields, $CFG['db_table'], $CFG['db_field_username'], $escape_user );

	$result = @pg_exec( $conn, $sql_string );

	if( !$result ) {
		@pg_close($conn);
		return(null);
	}

	if( @pg_numrows($result) == 0 ) {
		@pg_free_result($result);
		@pg_close($conn);
		return(null);
	}

	$arr = @pg_fetch_array( $result, 0 );

	@pg_free_result($result);
	@pg_close($conn);

	if( $CFG['db_password_crypt'] ) {
		if( $CFG['db_password_crypt'] == 'md5' )
			$password = md5($password);
		elseif( $CFG['db_password_crypt'] == 'crypt' )
			$password = crypt($password, $arr[1]);
	}

	if( $arr[1] != $password )
		return(null);

	/* authentication ok */

	if( isset($CFG['db_variable']) )
		foreach( $CFG['db_variable'] as $var => $field )
			$userinfo[$var] = $arr[strtolower($field)];

	$userinfo['%u'] = $username;

	return( $userinfo );
}

?>
