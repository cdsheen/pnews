<?

// PostgesSQL Authentication Module
// Copyright (C) 2001-2003 - All rights reserved
// Shen Cheng-Da (cdsheen@csie.nctu.edu.tw)


function check_user_password( $username, $password ) {

	global $CFG;

	if( !isset( $CFG['db_server'], $CFG['db_name'], $CFG['db_table'], $CFG['db_username'], $CFG['db_password'], $CFG['db_field_username'] ) )
		return(null);

	$server = $CFG['db_server'];
	if( strstr( $server, ':' ) )
		list( $server, $port ) = split( '/:/', $server );
	else
		$port = 5432;

	$conn_string = sprintf( "host=%s port=%d dbname=%s user=%s password=%s", $server, $port, $CFG['db_name'], $CFG['db_username'], $CFG['db_password'] );
	$conn = pg_connect( $conn_string );
	if( !$conn )
		return(null);

	$extra_fields = '';
	if( $CFG['db_variable'] )
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

	if( isset($CFG['db_password_crypt']) )
		$password = $CFG['db_password_crypt']($password);

	if( $arr[1] != $password )
		return(null);

	/* authentication ok */

	if( $CFG['db_variable'] )
		foreach( $CFG['db_variable'] as $var => $field )
			$userinfo[$var] = $arr[strtolower($field)];

	$userinfo['%u'] = $username;

	return( $userinfo );
}

?>
