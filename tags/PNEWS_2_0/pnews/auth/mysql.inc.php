<?

// MySQL Authentication Module
// Copyright (C) 2001-2003 - All rights reserved
// Shen Cheng-Da (cdsheen@csie.nctu.edu.tw)

function check_user_password( $username, $password ) {

	global $CFG;

	if( !isset( $CFG['db_server'], $CFG['db_name'], $CFG['db_table'], $CFG['db_username'], $CFG['db_password'], $CFG['db_field_username'] ) )
		return(null);

	$conn = @mysql_connect( $CFG['db_server'], $CFG['db_field_username'], $CFG['db_field_password'] );
	if( !$conn )
		return(null);

	if( !@mysql_select_db( $CFG['db_name'] ) ) {
		@mysql_close($conn);
		return(null);
	}

	$extra_fields = '';
	if( $CFG['db_variable'] )
		foreach( $CFG['db_variable'] as $var => $field )
			$extra_fields .= ",$field";

	$escape_user = str_replace( "'", "''", $username );
	$sql_string = sprintf( "SELECT %s,%s%s FROM %s WHERE %s='%s'", $CFG['db_field_username'], $CFG['db_field_password'], $extra_fields, $CFG['db_table'], $CFG['db_field_username'], $escape_user );

	$result = @mysql_query( $sql_string );

	if( !$result ) {
		@mysql_close($conn);
		return(null);
	}

	if( @mysql_num_rows($result) == 0 ) {
		@mysql_free_result($result);
		@mysql_close($conn);
		return(null);
	}

	$arr = @mysql_fetch_array( $result );

	@mysql_free_result($result);
	@mysql_close($conn);

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
