<?

# PHP News Reader
# Copyright (C) 2001-2004 Shen Cheng-Da
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
		echo "$buf<br />";
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

#		echo "$re_match,$re_group,$re_filter<br />";

		if( $re_match == '*' )
			send_command( $nhd, "LIST ACTIVE $group");
		else
			send_command( $nhd, "LIST ACTIVE $re_group" );
		list( $code, $msg ) = get_status( $nhd );

		if( $code[0] != '2' )
			break;

		while( $buf = fgets( $nhd, 4096 ) ) {
#			echo "$buf<br />";
			$buf = chop($buf);
			if( $buf == '.' )
				break;
			$entry = split( ' ', $buf );

			if( $re_match != '*' && !preg_match( "/\.$re_filter\$/i", $entry[0] ) )
				continue;
#			echo "$buf<br />";
			$active[$entry[0]] = array( (int)$entry[1], (int)$entry[2] );
		}

		send_command( $nhd, "LIST newsgroups $group");
		list( $code, $msg ) = get_status( $nhd );
		if( $code[0] != '2' )
			break;

		while( $buf = fgets( $nhd, 4096 ) ) {
#			echo "$buf<br />";
			$buf = chop( $buf );
			if( $buf == '.' )
				break;
			preg_match( '/^(\S+)\s+(.+)$/', $buf, $match );
#			echo "$match[1] $match[2]<br />\n";
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

	$ovfmt = array( 'Subject:' => 1,
			'From:' => 2,
			'Date:' => 3,
			'Message-ID:' => 4,
			'References:' => 5,
			'Bytes:' => 6,
			'Lines:' => 7,
			'Xref:full' => 8 );

	send_command( $nhd, "LIST OVERVIEW.FMT" );
	list( $code, $msg ) = get_status( $nhd );
	if( $code[0] == '2' ) {
		$n = 1;
		while( $buf = fgets( $nhd, 4096 ) ) {
			$buf = trim( $buf );
			if( $buf == "." )
				break;
#			echo "<!-- [$buf] => $n -->\n";
			$ovfmt[$buf] = $n++;
		}
	}

	if( $to == null )
		send_command( $nhd, "XOVER $from" );
	else
		send_command( $nhd, "XOVER $from-$to" );
	list( $code, $msg ) = get_status( $nhd );

	if( $code[0] != '2' )
		return(null);

	while( $buf = fgets( $nhd, 4096 ) ) {
#		echo "$buf<br />";
		$buf = chop( $buf );
		if( $buf == "." )
			break;
		$xover = split( "\t", $buf );

		$n = $xover[0];

		$ov[$n] = array( decode_subject($xover[$ovfmt['Subject:']]),
						'',
						strtotime( $xover[$ovfmt['Date:']] ),
						'',
						$xover[$ovfmt['Message-ID:']] );

		if( preg_match( '/^<(.+)@(.+)>$/', $xover[$ovfmt['From:']], $from ) ) {
			$ov[$n][1] = $from[1];
			$ov[$n][3] = $from[0];
		}
		elseif( preg_match( '/^(\S+)@(\S+)$/', $xover[$ovfmt['From:']], $from ) ) {
			$ov[$n][1] = $from[1];
			$ov[$n][3] = $from[0];
		}
		elseif( preg_match( '/^(.+)? <(.+)>$/', $xover[$ovfmt['From:']], $from ) ) {
			$from[1] = strip_quotes( $from[1] );
			$ov[$n][1] = decode_subject($from[1]);
			$ov[$n][3] = $from[2];
		}
		elseif( preg_match( '/^(\S+) \((.+)?\)$/', $xover[$ovfmt['From:']], $from ) ) {
			$from[2] = strip_quotes( $from[2] );
			$ov[$n][1] = decode_subject($from[2]);
			$ov[$n][3] = $from[1];
		}

		$refs = trim($xover[$ovfmt['References']]);
		if( $refs == '' )
			$ov[$n][5] = array();
		else
			$ov[$n][5] = preg_split( '/\s+/', $refs );

		$n++;
	}
#	print_r( $ov );
	return( $ov );
}

function nnrp_article_list ( $nhd, $lowmark, $highmark, $cache_file = false ) {

	$new_art = $lowmark;

	$artlist = array();

	if( $cache_file ) {
		$fp = @fopen( $cache_file, 'rb');
		if( $fp ) {
			$cache_max = -1;
			$artlist = @unserialize( fread( $fp, filesize($cache_file)) );
			fclose($fp);
			if( $artlist ) {
#				echo "<!-- Cache size: " . count($artlist) . " -->\n";
				foreach( $artlist as $idx => $artnum ) {
					if( $artnum < $lowmark || $artnum > $highmark )
						unset($artlist[$idx]);
					else {
						if( $artnum > $cache_max )
							$cache_max = $artnum;
					}
				}
			}
			if( $cache_max > 0 )
				$new_art = $cache_max + 1;
		}
	}

	if( $new_art <= $highmark ) {
		if( $new_art == $highmark )
			send_command( $nhd, "XOVER $new_art" );
		else
			send_command( $nhd, "XOVER $new_art-$highmark" );
		list( $code, $msg ) = get_status( $nhd );

		echo "\n<!-- XOVER $new_art-$highmark   STATUS: $code -->\n";

		if( $code[0] != '2' )
			return($artlist);

		while( $buf = fgets( $nhd, 4096 ) ) {
#			echo "$buf<br />";
			$buf = chop( $buf );
			if( $buf == '.' )
				break;

			$artinfo = split( "\t", $buf );

			$artlist[] = intval($artinfo[0]);
		}
	}


	if( $cache_file ) {
		$artlist = array_values( $artlist );
#		$artlist = sort( $artlist );
		$fp = @fopen( $cache_file, 'w' );
		if( $fp ) {
			if( flock( $fp, LOCK_EX|LOCK_NB ) ) {
				@fputs( $fp, serialize( $artlist ) );
				@flock( $fp, LOCK_UN );
			}
			@fclose($fp);
		}
	}
	return( $artlist );
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

define( 'SHOW_HYPER_LINK',  1 );
define( 'SHOW_SIGNATURE',   2 );
define( 'SPACE_ASIS',       4 );
define( 'SHOW_NULL_LINE',   8 );
define( 'SHOW_HEADER',     16 );
define( 'FILTER_ANSI',     32 );
define( 'IMAGE_INLINE',    64 );
define( 'HIDE_EMAIL',     128 );

function nnrp_show ( $nhd, $artnum, $artinfo, $mode, $prepend = '', $postpend = '', $trans_func = null, $download_url = '' ) {

	$mode = intval($mode);

	$show_hlink     = ($mode & SHOW_HYPER_LINK) >0;
	$show_sig       = ($mode & SHOW_SIGNATURE)  >0;
	$space_asis     = ($mode & SPACE_ASIS)      >0;
	$show_null_line = ($mode & SHOW_NULL_LINE)  >0;
	$show_header    = ($mode & SHOW_HEADER)     >0;
	$filter_ansi    = ($mode & FILTER_ANSI)     >0;
	$image_inline   = ($mode & IMAGE_INLINE)    >0;
	$hide_email     = ($mode & HIDE_EMAIL)      >0;

	if( $show_header )
		send_command( $nhd, "ARTICLE $artnum" );
	else
		send_command( $nhd, "BODY $artnum" );

	list( $code, $msg ) = get_status( $nhd );

	if( $code[0] != '2' )
		return(null);

	if( $show_header ) {
		while( $buf = fgets( $nhd, 4096 ) ) {
			$buf = chop($buf);
			if( $buf == '.' || $buf == '' )
				break;

			list($field, $value) = explode( ':', $buf );

			$buf = htmlspecialchars( $buf, ENT_NOQUOTES );

			if( $hide_email && !strstr( $buf, $artinfo['msgid'] ) )
				$buf = preg_replace( '/(\A|\s|[:;*+&"<{\/\(\[\'])([\w-_.]+)@([\w-_.]+)/e', '"$1".hide_mail("$2@$3")' , $buf );

			# convert charset if required
			if( $trans_func )
				$buf = $trans_func( $buf );

			# replace the leading space as &nbsp;
			if( !$space_asis && preg_match( '/^(\s+)(.+)$/', $buf , $match ) )
				$buf = str_repeat( '&nbsp;', strlen($match[1]) ) . $match[2];

			echo $prepend . $buf . $postpend;
		}
		echo $prepend . $postpend;
	}

	$uu = array();
	$uuencode_skip = false;
	$i = 0;
	while( $buf = fgets( $nhd, 4096 ) ) {
		$buf = chop($buf);
		if( $buf == '.' )
			break;
		if( $uuencode_skip ) {
			if( strtolower($buf) == 'end' )
				$uuencode_skip = false;
		}
		elseif( $buf[0] == '.' )
			$body[$i++] = substr( $buf, 1 );
		elseif( preg_match( '/^begin\s+(\d+)\s+(.+)\s*$/i', $buf, $match ) ) {
			$uuencode_skip = true;
			$body[$i] = $match[2];
			$uu[] = $i++;
		}
		else
			$body[$i++] = $buf;
	}

	if( $artinfo['encoding'] == 'base64' )
		$body = preg_split( '/[\r\n]+/', base64_decode( implode( '', $body ) ) );

	$n = count($body);

	$skip_sig = false;
	for( $i = 0 ; $i < $n ; $i++ ) {

		if( $skip_sig )
			continue;

		if( !$show_sig && $body[$i] == '--' ) {
			$skip_sig = true;
			continue;
		}

		if( in_array( $i, $uu ) ) {
			if( $show_hlink ) {
				$ext = substr( $body[$i], strrpos( $body[$i], '.') + 1);
				if( $image_inline && strstr( 'jpg.jpeg.gif.bmp.png', strtolower($ext) ) ) {
					echo "$prepend<img src=\"";
					printf( "$download_url", $body[$i] );
					echo "\" alt=\"{$body[$i]}\" />$postpend";
				}
				else {
					echo "$prepend &lt;&lt; <a href=\"";
					printf( "$download_url", $body[$i] );
					echo "\">{$body[$i]}</a> &gt;&gt; $postpend";
				}
			}
			else
				echo "$prepend &lt;&lt; {$body[$i]} &gt;&gt; $postpend";
			continue;
		}

		if( $artinfo['encoding'] == 'quoted-printable' )
			$body[$i] = quoted_printable_decode( $body[$i] );

		$body[$i] = htmlspecialchars( $body[$i], ENT_NOQUOTES );

		# replace the space(s) as &nbsp;
#		if( !$space_asis && preg_match( '/^(\s+)(.+)$/', $body[$i] , $match ) )
#			$body[$i] = str_repeat( '&nbsp;', strlen($match[1]) ) . $match[2];
		if( !$space_asis )
			$body[$i] = preg_replace( '/\s/', '&nbsp;', $body[$i] );

		# hyperlink/email auto-detection
		if( $show_hlink ) {
			/* replace hyperlink */
			$body[$i] = preg_replace( '/(((http)|(ftp)|(https)):\/\/([\w-.:\/~+=?,#;]|(&amp;))+)/', '<a href="$1" target=_blank>$1</a>' , $body[$i] );
			/* replace mail link */
			if( $hide_email )
				$body[$i] = preg_replace( '/(\A|\s|[:;*+&"<{\/\(\[\'])([\w-_.]+)@([\w-_.]+)/e', '"$1".hide_mail_link("$2@$3")', $body[$i] );
			else
				$body[$i] = preg_replace( '/(\A|\s|[:;*+&"<{\/\(\[\'])([\w-_.]+)@([\w-_.]+)/', '$1<a href="mailto:$2@$3">$2@$3</a>', $body[$i] );
		}

		# filter ANSI codes
		if( $filter_ansi )
			$body[$i] = preg_replace( '/\033\[[\d;]*m/', '', $body[$i] );

		# filter null line
		if( !$show_null_line && $body[$i] == '' )
			continue;

		# convert charset if required
		if( $trans_func )
			$body[$i] = $trans_func( $body[$i] );

		echo $prepend . $body[$i] . $postpend;
	}
}

function nnrp_get_attachment ( $nhd, $artnum, $type, $filename ) {

	send_command( $nhd, "BODY $artnum" );

	list( $code, $msg ) = get_status( $nhd );

	if( $code[0] != '2' )
		return(null);

#	$filename = trim($filename);

	if( $type == 'uuencode' ) {

		function DEC( $char ) {
			return( (ord($char[0]) - 32) & 077 );
		}

		$pass = 0;
		while( $buf = fgets( $nhd, 4096 ) ) {
			$tbuf = trim($buf);
			if( $tbuf == '.' )
				break;
			if( $pass == 2 ) {	# Skip the rest
				continue;
			}
			elseif( $pass == 1 ) {
				if( strtolower($tbuf) == 'end' )
					$pass = 2;
				else {
					$i = DEC($buf[0]);

					if( $i <= 0 ) {
						continue;
					}

					for( $p = 1 ; $i > 0 ; $p += 4, $i -= 3 ) {
						if( $i >= 3 ) {
							$byte[0] = DEC($buf[$p]);
							$byte[1] = DEC($buf[$p+1]);
							$byte[2] = DEC($buf[$p+2]);
							$byte[3] = DEC($buf[$p+3]);

							$tmp = chr(($byte[0] << 2 | $byte[1] >> 4) & 0xff);
							$tmp.= chr(($byte[1] << 4 | $byte[2] >> 2) & 0xff);
							$tmp.= chr(($byte[2] << 6 | $byte[3] ) & 0xff);
						}
						else {
							$byte[0] = DEC($buf[$p]);
							$byte[1] = DEC($buf[$p+1]);
							$tmp = chr(($byte[0] << 2 | $byte[1] >> 4) & 0xff);
							if( $i > 1 ) {
								$byte[2] = DEC($buf[$p+2]);
								$tmp .= chr(($byte[1] << 4 | $byte[2] >> 2) & 0xff);
							}
						}
						$binary.= $tmp;
					}
				}
			}
			elseif( preg_match( '/^begin\s(\d+)\s'.$filename.'$/i', $tbuf, $match ) ) {
				$pass = 1;
			}
		}
	}

	return($binary);
}


function nnrp_head ( $nhd, $artnum, $def_charset = 'utf-8', $time_format = '%Y/%m/%d %H:%M:%S' ) {
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

	return( get_mime_info( $headers, $def_charset, $time_format ) );
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
#	echo( "From: $name [$email]<br />\n" );
#	echo( "Newsgroups: $newsgroups<br />\n" );
#	echo( "Subject: $subject<br />\n" );
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
#	echo "[$cmd]<br />\n";
	$nnrp_last_command = $cmd;
	@fwrite( $nhd, "$cmd\r\n");
}

function get_status( $nhd ) {
	global $nnrp_last_result;
	$responds = @fgets( $nhd, 1024 );
#	echo "[$responds]<br />\n";
	$nnrp_last_result = $responds;
	preg_match( '/^(\d+)\s*(.+)$/', $responds, $match );
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

function strip_quotes( $str ) {
	if( preg_match( '/^"(.+)"$/', $str, $quotes ) )
		return( $quotes[1] );
	else
		return( $str );
}

function get_mime_info( $headers, $def_charset, $time_format ) {

	$artinfo['charset'] = $def_charset;

	if( $headers['Content-Type'] ) {
		$ctype = preg_split( '/[;\s]+/', strtolower($headers['Content-Type']) );
		if( is_array( $ctype ) ) {
			list( $type, $subtype ) = split( '/', $ctype[0]);
			$artinfo['type'] = $type;
			$artinfo['subtype'] = $subtype;
			array_shift( $ctype );
			foreach( $ctype as $c_param ) {
				if( preg_match( '/^(.+)\s*=\s*(.+)$/', $c_param, $match ) ) {
					$match[2] = strip_quotes( $match[2] );
					if( $match[1] == 'charset' )
						$artinfo['charset'] = strtolower($match[2]);
					elseif( $match[1] == 'boundary' )
						$artinfo['boundary'] = $match[2];
				}
			}
		}
	}

	if( $headers['Content-Transfer-Encoding'] )
		$artinfo['encoding'] = $headers['Content-Transfer-Encoding'];
	else
		$artinfo['encoding'] = '7bit';

	if( $headers['Date'] )
		$artinfo['date'] = strftime( $time_format, strtotime($headers['Date']) );

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
		elseif( preg_match( '/^(.+)? <(.+)>$/', $headers['From'], $from ) ) {
			$from[1] = strip_quotes( $from[1] );
			$artinfo['name'] = decode_subject($from[1]);
			$artinfo['mail'] = $from[2];
		}
		elseif( preg_match( '/^(\S+@\S+) \((.+)?\)$/', $headers['From'], $from ) ) {
			$from[2] = strip_quotes( $from[2] );
			$artinfo['name'] = decode_subject($from[2]);
			$artinfo['mail'] = $from[1];
		}
	}
	else
		$artinfo['name'] = $artinfo['mail'] = '';

	$artinfo['org'] = decode_subject($headers['Organization']);

	$artinfo['subject'] = decode_subject($headers['Subject']);

	$refs = trim($headers['References']);
	if( $refs == '' )
		$artinfo['ref'] = array();
	else
		$artinfo['ref'] = preg_split( '/\s+/', $refs );

	return($artinfo);
}

// Copyright (C) 2001-2004 - All rights reserved
// Shen Cheng-Da (cdsheen at users.sourceforge.net)


?>
