<?

// FTP Authentication Module
// Copyright (C) 2001-2003 - All rights reserved
// Shen Cheng-Da (cdsheen@csie.nctu.edu.tw)

function check_user_password( $username, $password ) {

	global $CFG;

	if( !isset( $CFG['ftp_server'] ) )
		return(null);

	if( is_array($CFG['ftp_deny']) && in_array( $username, $CFG['ftp_deny'] ) )
		return(null);

	$server = $CFG['ftp_server'];
	if( strstr( $server, ':' ) )
		list( $server, $port ) = split( '/:/', $server );
	else
		$port = 21;

	$sock = fsockopen( $server, $port );

	if( !$sock )
		return(null);

	socket_set_timeout( $sock, 10, 0 );

	$msg = fgets( $sock, 256 );
	if( !strstr( $msg, "2" ) ) {
		fclose( $sock );
		return(null);
	}

	fputs( $sock, "USER $username\r\n" );

	$msg = fgets( $sock, 256 );
	if( !strstr( $msg, "3" ) ) {
		fclose( $sock );
		return(null);
	}

	fputs( $sock, "PASS $password\r\n" );

	$msg = fgets( $sock, 256 );
	if( !strstr( $msg, "2" ) ) {
		fclose( $sock );
		return(null);
	}

	fputs( $sock, "QUIT\r\n" );
	fclose( $sock );

	$userinfo['%u'] =  $username;

	return( $userinfo );
}

?>
