<?
	# Use this script to test the connection from Web to News Server
	# Change the following assignment to your news server
	$server = 'news-server.foobar.com';
	$port   = 119;

	$sock = fsockopen( $server, $port, $errno, $errstr, 5 );
	if( !$sock ) {
		echo "$errstr ($errno)<br />\n";
	}
	fclose($sock);
?>
