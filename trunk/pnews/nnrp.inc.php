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

$php_news_agent = "PHP News Reader $pnews_version (CDSHEEN)";

function nnrp_open ( $nnrp_server, $ssl_enable = false ) {
	if( $ssl_enable )
		return( open_nntps( $nnrp_server ) );
	else
		return( open_nntp( $nnrp_server ) );
}

function open_nntp ( $nnrp_server ) {

	$nhd = null;
	$nhd = @fsockopen( $nnrp_server, 119, $errno, $errstr, 5 );

	if( ! $nhd )
		return(null);

	list( $code, $msg ) = get_status( $nhd );

	if( $code[0] != '2' )
		return(null);

	send_command( $nhd, "MODE READER" );
	list( $code, $msg ) = get_status( $nhd );
	if( $code[0] != '2' )
		return(null);

	return( $nhd );
}

function open_nntps ( $nnrp_server ) {

	$nhd = null;
	$nhd = fsockopen( "ssl://$nnrp_server", 563, $errno, $errstr, 5 );

	if( ! $nhd )
		return(null);

	list( $code, $msg ) = get_status( $nhd );
	if( $code[0] != '2' )
		return(null);

	send_command( $nhd, "MODE READER" );
	list( $code, $msg ) = get_status( $nhd );
	if( $code[0] != '2' )
		return(null);

	return( $nhd );
}

function nnrp_help( $nhd ) {
	send_command( $nhd, "HELP" );
	while( $buf = fgets( $nhd, 4096 ) ) {
		echo "$buf<br>";
		$buf = chop($buf);
		if( $buf == '.' )
			break;
	}
}

function nnrp_auth( $nhd, $username, $password ) {

	send_command( $nhd, "AUTHINFO USER $username" );
	list( $code, $msg ) = get_status( $nhd );
	if( $code[0] != '3' )
		return(false);

	send_command( $nhd, "AUTHINFO PASS $password" );
	list( $code, $msg ) = get_status( $nhd );
	if( $code[0] != '2' )
		return(false);

	return(true);
}

function nnrp_list_group( $nhd, $filter = '*', $func = null ) {

	if( $filter == '*' )
		$group_show = array( '' );
	else
		$group_show = explode( ',', $filter );

	$active = null;

	foreach( $group_show as $group ) {

		if( $group[0] == '!' ) {
			$group = substr( $group, 1 );
			foreach( $active as $key => $value) {
				if( eregi( "^$group" , $key ) )
					unset( $active[$key] );
			}
			continue;
		}

		if( strchr( $group, '*' ) && preg_match( '/\.((_-\w\*)+)$/', $group, $match ) ) {
			$re_match = $match[1];
			$re_group = str_replace( $re_match, '*', $group );
			$re_filter = str_replace( '*', '[^\.]*', $re_match );
		}
		else
			$re_match = '*';

#		echo "$re_match,$re_group,$re_filter<br>";

		if( $re_match == '*' )
			send_command( $nhd, "LIST ACTIVE $group");
		else
			send_command( $nhd, "LIST ACTIVE $re_group" );
		list( $code, $msg ) = get_status( $nhd );

		if( $code[0] != '2' )
			break;

		while( $buf = fgets( $nhd, 4096 ) ) {
#			echo "$buf<br>";
			$buf = chop($buf);
			if( $buf == '.' )
				break;
			$entry = split( " ", $buf );

			if( $re_match != '*' && !preg_match( "/\.$re_filter\$/i", $entry[0] ) )
				continue;
#			echo "$buf<br>";
			$active[$entry[0]] = array( (int)$entry[1], (int)$entry[2] );
		}

		send_command( $nhd, "LIST newsgroups $group");
		list( $code, $msg ) = get_status( $nhd );
		if( $code[0] != '2' )
			break;

		while( $buf = fgets( $nhd, 4096 ) ) {
#			echo "$buf<br>";
			$buf = chop( $buf );
			if( $buf == '.' )
				break;
			preg_match( '/^(\S+)\s+(.+)$/', $buf, $match );
#			echo "$match[1] $match[2]<br>\n";
			if( is_array( $active[$match[1]] ) ) {
				if( $func )
					array_push( $active[$match[1]], $func($match[2]) );
				else
					array_push( $active[$match[1]], $match[2] );
			}
		}

	}

	return( $active );

}

function nnrp_group ( $nhd, $group ) {
	send_command( $nhd, "GROUP $group");
	list( $code, $msg ) = get_status( $nhd );
	list( $count, $lowmark, $highmark ) = split( ' ', $msg );
	return( array( $code, $count, $lowmark, $highmark ) );
}

function nnrp_xover ( $nhd, $from, $to=null ) {

	global $CFG;

	if( $to == null )
		send_command( $nhd, "XOVER $from" );
	else
		send_command( $nhd, "XOVER $from-$to" );
	list( $code, $msg ) = get_status( $nhd );

	if( $code[0] != '2' )
		return(null);

	$n = 0 ;
	while( $buf = fgets( $nhd, 4096 ) ) {
#		echo "$buf<br>";
		$buf = chop( $buf );
		if( $buf == "." )
			break;
		$xover[$n] = preg_split( '/\t/', $buf );

#		$overview[$n] = array();
		$overview[$n][0] = $xover[$n][0];
		$overview[$n][1] = decode_subject($xover[$n][1]);
		$overview[$n][3] = strftime( $CFG['time_format'], strtotime( $xover[$n][3] ));
		$overview[$n][4] = $xover[$n][4];	/* Message-ID */
		$overview[$n][2] = $overview[$n][5] = '';

		if( preg_match( '/^<(.+)@(.+)>$/', $xover[$n][2], $from ) ) {
			$overview[$n][2] = $from[1];
			$overview[$n][5] = $from[0];
		}
		elseif( preg_match( '/^(\S+)@(\S+)$/', $xover[$n][2], $from ) ) {
			$overview[$n][2] = $from[1];
			$overview[$n][5] = $from[0];
		}
		elseif( preg_match( '/^"?([^"]+)?"? <(.+)>$/', $xover[$n][2], $from ) ) {
			$overview[$n][2] = decode_subject($from[1]);
			$overview[$n][5] = $from[2];
		}
		elseif( preg_match( '/^(\S+) \("?([^"]+)?"?\)$/', $xover[$n][2], $from ) ) {
			$overview[$n][2] = decode_subject($from[2]);
			$overview[$n][5] = $from[1];
		}
		$n++;
	}
	return( $overview );
}

function nnrp_xover_limit ( $nhd, $from, $count, $limit, $forward = true ) {

	$next = ( $forward ? 1 : -1 );

	$artnum = $from;

	for( $i = 0 ; $i < $count ;  ) {

		$xover = nnrp_xover( $nhd, $artnum );

		if( sizeof($xover) > 0 )
			$overview[$i++] = $xover[0];

		$artnum += $next;

		if( $forward && $artnum > $limit )
			break;
		if( !$forward && $artnum < $limit )
			break;
	}

	if( $forward )
		return( $overview );
	elseif( $i == 0 )
		return( null );
	else
		return( array_reverse($overview) );
}

function nnrp_stat( $nhd, $artnum ) {
	send_command( $nhd, "STAT $artnum" );
	list( $code, $msg ) = get_status( $nhd );

	return( $code[0] == '2' );
}

function nnrp_next( $nhd, $artnum ) {

	send_command( $nhd, "STAT $artnum" );
	list( $code, $msg ) = get_status( $nhd );
	if( $code[0] != '2' )
		return(-1);

	send_command( $nhd, "NEXT" );
	list( $code, $msg ) = get_status( $nhd );
	if( $code[0] != '2' )
		return(-1);

	list( $nextart, $artid, $rest ) = split( ' ', $msg );

	return( $nextart );
}

function nnrp_last( $nhd, $artnum ) {

	send_command( $nhd, "STAT $artnum" );
	list( $code, $msg ) = get_status( $nhd );
	if( $code[0] != '2' )
		return(-1);

	send_command( $nhd, "LAST" );
	list( $code, $msg ) = get_status( $nhd );
	if( $code[0] != '2' )
		return(-1);

	list( $lastart, $artid, $rest ) = split( ' ', $msg );

	return( $lastart );
}

function nnrp_article ( $nhd, $artnum, $prepend = "", $postpend = "" ) {
	send_command( $nhd, "ARTICLE $artnum" );
	list( $code, $msg ) = get_status( $nhd );

	if( $code[0] != '2' )
		return(null);

	$n = 0 ;
	while( $buf = fgets( $nhd, 4096 ) ) {
		$buf = chop( $buf );
		if( $buf == "." )
			break;
		echo $prepend . htmlspecialchars($buf, ENT_NOQUOTES ) . $postpend;
	}
}

function nnrp_body ( $nhd, $artnum, $prepend = "", $postpend = "", $urlquote = true, $grep_signature = false, $trans_func = null, $leading_space = true ) {
	global $CFG;
	send_command( $nhd, "BODY $artnum" );
	list( $code, $msg ) = get_status( $nhd );

	if( $code[0] != '2' )
		return(null);

	$n = 0 ;
	$skip = false;
	while( $buf = fgets( $nhd, 4096 ) ) {
		$buf = chop( $buf );
		if( $buf == "." )
			break;
		if( $skip )
			continue;
		if( $grep_signature && $buf == '--' ) {
			$skip = true;
			continue;
		}
		# quote out special chars
		$buf = htmlspecialchars($buf, ENT_NOQUOTES );

		if( $urlquote ) {

			# hyperlink/email auto-detection

			$pattern = array( '/(((http)|(ftp)|(https)):\/\/([\w-.:\/~+=?,#]|(&amp;))+)/', '/\b([\w-.]+)@([\w-.]+)/' );
			$replacement = array( '<a href="$1" target=_blank>$1</a>', ' <a href="mailto:$0" target=_blank>$0</a>' );

			$buf = preg_replace( $pattern, $replacement , $buf );

		}
		# filter ANSI codes
		if( $CFG['filter_ansi_color'] )
			$buf = preg_replace( '/\033\[[\d;]*m/', '', $buf );

		if( $trans_func )
			$buf = $trans_func( $buf );

		# replace the leading space as &nbsp;
		if( $leading_space && preg_match( '/^(\s+)(.+)$/', $buf , $match ) )
			$buf = str_repeat( '&nbsp;', strlen($match[1]) ) . $match[2];

		echo $prepend . $buf . $postpend;
	}
}

function nnrp_head ( $nhd, $artnum, $def_charset = 'utf-8' ) {
	send_command( $nhd, "HEAD $artnum" );
	list( $code, $msg ) = get_status( $nhd );

	if( $code[0] != '2' )
		return(null);

	$n = 0 ;
	$nowline = fgets( $nhd, 4096 );
	$nowline = chop($nowline);
	while( $nowline && $nowline != '.' ) {
		$nextline = fgets( $nhd, 4096 );
		$nextline = chop($nextline);
		while( preg_match( '/^\s/', $nextline ) ) {
			$nowline .= ' ' . trim($nextline);
			$nextline = fgets( $nhd, 4096 );
			$nextline = chop($nextline);
		}

		preg_match( '/^([^:]+): (.+)$/', $nowline, $match );

		$headers[$match[1]] = $match[2];

		$nowline = $nextline;
	}

	return( get_mime_info( $headers, $def_charset ) );
}

function nnrp_post_begin( $nhd, $name, $email, $subject, $newsgroups, $organization, $ref = null, $real_email, $art_charset ) {

	global $php_news_agent;

	$client = $_SERVER['HTTP_X_FORWARDED_FOR'] ;

	if( $client == '' ) {
		$proxy = '';
		$client = $_SERVER['REMOTE_ADDR'];
	}
	else
		$proxy = $_SERVER['REMOTE_ADDR'];

	send_command( $nhd, "POST" );
	list( $code, $msg ) = get_status( $nhd );
	fwrite( $nhd, "From: $name <$email>\r\n" );
	fwrite( $nhd, "Newsgroups: $newsgroups\r\n" );
	fwrite( $nhd, "Subject: $subject\r\n" );
	fwrite( $nhd, "Organization: $organization\r\n" );
	fwrite( $nhd, "X-User-Real-E-Mail: $real_email\r\n" );
	fwrite( $nhd, "User-Agent: $php_news_agent\r\n" );
	fwrite( $nhd, "Mime-Version: 1.0\r\n" );
	fwrite( $nhd, sprintf("Content-Type: text/plain; charset=\"%s\"\r\n", $art_charset ) );
	fwrite( $nhd, "Content-Transfer-Encoding: 8bit\r\n" );
#	fwrite( $nhd, "X-User-Agent-URL: http://pnews.sourceforge.net/\r\n" );
	fwrite( $nhd, "X-HTTP-Posting-Host: $client\r\n" );
	if( $proxy != '' )
		fwrite( $nhd, "X-HTTP-Proxy-Server: $proxy\r\n" );
#	fwrite( $nhd, "NNTP-Posting-Host: unknown@$remote\r\n" );
#	echo( "From: $name [$email]<br>\n" );
#	echo( "Newsgroups: $newsgroups<br>\n" );
#	echo( "Subject: $subject<br>\n" );
	if( $ref )
		fwrite( $nhd, "References: $ref\r\n" );
	fwrite( $nhd, "\r\n" );
}

function nnrp_post_writeln( $nhd, $buf ) {
	if( $buf == '.' )
		fwrite( $nhd, "..\r\n" );
	else
		fwrite( $nhd, $buf . "\r\n" );
}

function nnrp_post_write( $nhd, $buf ) {
	$tok = strtok( $buf, "\n" );
	while ($tok) {
		$tok = rtrim($tok);
		nnrp_post_writeln( $nhd, $tok );
		$tok = strtok ("\n");
	}
}

function nnrp_post_finish( $nhd ) {
	fwrite( $nhd, ".\r\n");
	list( $code, $msg ) = get_status( $nhd );
}

function nnrp_cancel( $nhd, $name, $email, $msgid, $newsgroup, $subject = null ) {
	send_command( $nhd, "POST" );
	list( $code, $msg ) = get_status( $nhd );
	fwrite( $nhd, "From: $name <$email>\r\n" );
	fwrite( $nhd, "Newsgroups: $newsgroup\r\n" );
	fwrite( $nhd, "Subject: cmsg cancel $msgid\r\n" );
	fwrite( $nhd, "Control: cancel $msgid\r\n" );
	fwrite( $nhd, "\r\n" );
	if( $subject )
		fwrite( $nhd, "$subject deleted from $newsgroups\r\n");
	fwrite( $nhd, ".\r\n");
	list( $code, $msg ) = get_status( $nhd );
}

function nnrp_close( $nhd ) {
	if( $nhd )
		fclose($nhd);
}

function send_command( $nhd, $cmd ) {
	global $nnrp_last_command;
#	echo "[$cmd]<br>\n";
	$nnrp_last_command = $cmd;
	@fwrite( $nhd, "$cmd\r\n");
}

function get_status( $nhd ) {
	global $nnrp_last_result;
	$responds = @fgets( $nhd, 1024 );
#	echo "[$responds]<br>\n";
	$nnrp_last_result = $responds;
	preg_match( '/^(\d+)\s(.+)$/', $responds, $match );
	return( array($match[1], $match[2]) );
}

function decode_subject( $instr ) {
	$enstr = $instr;
	while( preg_match( '/^([^?]+)?=\?[^?]+\?(B|Q)\?([^?]+)=?=?\?=(.+)?$/i', $enstr, $match ) ) {
		if( $match[2] == 'b' || $match[2] == 'B' )
			$enstr = $match[1] . base64_decode( $match[3] ) . $match[4];
		else
			$enstr = $match[1] . quoted_printable_decode( $match[3] );
	}
	return( $enstr );
}

function mb_wordwrap($str)
{
	$width = 75;
	$break = "\n";

	switch(func_num_args()) {
		case 4 : $cut = func_get_arg(3);
		case 3 : $break = func_get_arg(2);
		case 2 : $width = func_get_arg(1);
	}

	$str_len = strlen($str);

	for($start = 0; $start < $str_len; ) {
		$width_conv = $width;
		$end_chr = ord($str[$start + $width - 1]);

		if( !$cut && $end_chr != 32 )
			$width_conv += strpos(substr($str, $start + $width), " ") + 1;
		elseif($cut && $end_chr > 127) {
			for($end = $start + $width - 1, $h = 0; $end >= $start; $end--)
				if(ord($str[$end]) > 127)
					$h++;
				else
					break;
			if($h%2!=0)
				$width_conv = $width - 1;
		}

		$str_line = substr($str, $start, $width_conv);
		$start += $width_conv;

		if($start < $str_len)
			$str_line .= $break;
		$str_conv .= $str_line;
	}

	return $str_conv;
}

function get_mime_info( $headers, $def_charset = 'utf-8' ) {

	global $CFG;

	$artinfo['charset'] = $def_charset;

	if( $headers['Content-Type'] ) {
		$ctype = preg_split( '/[;\s]+/', strtolower($headers['Content-Type']) );
		if( is_array( $ctype ) ) {
			list( $type, $subtype ) = preg_split( '/\//', $ctype[0]);
			$artinfo['type'] = $type;
			$artinfo['subtype'] = $subtype;
			array_shift( $ctype );
			foreach( $ctype as $c_param ) {
				list( $param, $value ) = preg_split( '/=/', $c_param );
				if( $param == 'charset' )
					$artinfo['charset'] = str_replace( '"', '', $value );
			}
		}
	}

	if( $headers['Content-Transfer-Encoding'] )
		$artinfo['encoding'] = $headers['Content-Transfer-Encoding'];
	else
		$artinfo['encoding'] = '7bit';

	if( $headers['Date'] )
		$artinfo['date'] = strftime( $CFG['time_format'], strtotime($headers['Date']) );

	$artinfo['msgid'] = $headers['Message-ID'];

	if( $headers['From'] ) {
		if( preg_match( '/^<(.+)@(.+)>$/', $headers['From'], $from ) ) {
			$artinfo['name'] = $from[1];
			$artinfo['mail'] = $from[0];
		}
		elseif( preg_match( '/^(\S+)@(\S+)$/', $headers['From'], $from ) ) {
			$artinfo['name'] = $from[1];
			$artinfo['mail'] = $from[0];
		}
		elseif( preg_match( '/^"?([^"]+)?"? <(.+)>$/', $headers['From'], $from ) ) {
			$artinfo['name'] = decode_subject($from[1]);
			$artinfo['mail'] = $from[2];
		}
		elseif( preg_match( '/^(\S+@\S+) \("?([^"]+)?"?\)$/', $headers['From'], $from ) ) {
			$artinfo['name'] = decode_subject($from[2]);
			$artinfo['mail'] = $from[1];
		}
	}
	else
		$artinfo['name'] = $artinfo['mail'] = '';

	$artinfo['org'] = decode_subject($headers['Organization']);

	$artinfo['subject'] = decode_subject($headers['Subject']);

	return($artinfo);
}

// Copyright (C) 2001-2003 - All rights reserved
// Shen Cheng-Da (cdsheen@users.sourceforge.net)


?>
