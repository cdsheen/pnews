<?

// Mail Authentication Module
// Copyright (C) 2001-2003 - All rights reserved
// Shen Cheng-Da (cdsheen@csie.nctu.edu.tw)

function check_user_password( $username, $password ) {
	global $CFG;

	if( !isset( $CFG['pop3_mapping'] ) )
		return(null);

	$domain = strstr( $username, '@' );
	if( !$domain || !isset( $CFG['pop3_mapping'][$domain] ) )
		return(null);

	$popuser = preg_replace( '/@.+$/', "", $username );

	$server = $CFG['pop3_mapping'][$domain];
	if( strstr( $server, ':' ) )
		list( $server, $port ) = split( '/:/', $server );
	else
		$port = 110;

	$sock = fsockopen( $server, $port );
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

	$userinfo['%u'] = $popuser;
	$userinfo['%e'] = $username;

	return( $userinfo );
}

?>
