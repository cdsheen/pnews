<?

// Copyright (C) 2001-2003 - All rights reserved
// Shen Cheng-Da (cdsheen@csie.nctu.edu.tw)

session_start();

# Read and check the configuration (config.inc.php)
require_once('readcfg.inc.php');

require_once('version.inc.php');
require_once('html.inc.php');
require_once('nnrp.inc.php');

# Global variables

$uri              = $_SERVER['REQUEST_URI'];
$self             = $_SERVER['PHP_SELF'];
$ip_from          = $_SERVER['REMOTE_ADDR'];
$self_base        = basename( $self );

$post_restriction = $CFG['post_restriction'];

$mail_add_header  = "X-Mailer: $pnews_name $pnews_version (CDSHEEN)\n"; 
$mail_add_header .= "X-Source: $ip_from";

# Limits definition

$lineppg          = 20;	# Lines Per Page
$subject_limit    = 45;	# Chars Limit for Subject
$nick_limit       = 15;	# Chars Limit for Nickname
$id_limit         = 18;	# Chars Limit for ID ( E-Mail before @ )
$org_limit        = 15;	# Chars Limit for Organization

$textcol          = 66;

##############################################################################
# Read the newsgroups definition

$lst = fopen( $CFG['group_list'], 'r' );

if( !$lst )
	show_error( "Can not loading " . $CFG['group_list'] . " . Copy examples/newsgroups.lst as a template.");

$catalog_num = -1;
$default_catalog = 0 ;

#$testing_groups_flag = false;

$group_default_language = $CFG['language']['grouplst'];
$private_catalogs = array();

while( $buf = fgets( $lst, 512) ) {
	$buf = chop( $buf );
	if( $buf[0] == '#' || strlen( $buf ) == 0 )
		continue;

	if( preg_match( '/^\[(.+)\]$/', $buf, $match ) ) {
		$catalog_num++;
		$news_catalog[$catalog_num]  = $match[1];
		$news_language[$catalog_num] = $group_default_language;
		$news_server[$catalog_num]   = $group_default_server;
		$news_authperm[$catalog_num] = false;
		$options = array();
		continue;
	}

	if( $catalog_num == -1 ) {
		if( preg_match( '/^server\s+(.+)$/', $buf, $match ) )
			$group_default_server = $match[1];
		if( preg_match( '/^lang\s+(.+)$/', $buf, $match ) ) {
			if( in_array( $match[1], $valid_language ) )
				$group_default_language = $match[1];
		}
	}
	elseif( preg_match( '/^option\s+(.+)$/', $buf, $match ) ) {
		$options = split( ',', $match[1] );
		if( in_array( 'default', $options ) )
			$default_catalog = $catalog_num;
		if( in_array( 'private', $options ) ) {
			$news_authperm[$catalog_num] = true;
			$private_catalogs[] = $catalog_num;
		}
	}
	elseif( preg_match( '/^server\s+(\S+)$/', $buf, $match ) ) {
		$news_server[$catalog_num] = $match[1];
	}
	elseif( preg_match( '/^groups?\s+(.+)$/', $buf, $match ) ) {
		$news_groups[$catalog_num] = $match[1];
	}
	elseif( preg_match( '/^lang\s+(.+)$/', $buf, $match ) ) {
		if( in_array( $match[1], $valid_language ) )
			$news_language[$catalog_num] = $match[1];
	}
#	elseif( preg_match( '/^testing\s+(.+)$/', $buf, $match ) ) {
#		$testing_groups[] = $news_server[$catalog_num] . '/' . $match[1] ;
#	}
}
fclose($lst);

$catalog_num++;


##############################################################################
# Determine the $curr_catalog

if( isset($_SESSION['rem_catalog']) )
	$curr_catalog = $_SESSION['rem_catalog'];
else
	$curr_catalog = $default_catalog;

if( $self_base == 'index.php' && isset( $_GET['catalog'] ) )
	$curr_catalog = $_GET['catalog'];

if( $curr_catalog == '' || $curr_catalog >= $catalog_num )
	$curr_catalog = $default_catalog;

##############################################################################
# Language conversion Phase 1


$config_convert   = get_conversion( $CFG['language']['config'], $curr_language );
$grouplst_convert = get_conversion( $CFG['language']['grouplst'], $curr_language );
$database_convert = get_conversion( $CFG['language']['database'], $curr_language );

#echo "curr: $curr_language<br>\n";
#echo "define: " . $lang_define[$curr_language] . "<br>\n";
#echo "cfgc: " . $config_convert['to'] . "<br>\n";
#echo "grpc: " . $grouplst_convert['to'] . "<br>\n";
#echo "dbsc: " . $database_convert['to'] . "<br>\n";

if( $config_convert['to'] ) {
	$CFG['auth_organization'] = $config_convert['to']( $CFG['auth_organization'] );
	$CFG['organization']      = $config_convert['to']( $CFG['organization'] );
	$CFG['title']             = $config_convert['to']( $CFG['title'] );
	$CFG['banner']            = $config_convert['to']( $CFG['banner'] );
	$CFG['post_signature']    = $config_convert['to']( $CFG['post_signature'] );
	$CFG['auth_http_realm']   = $config_convert['to']( $CFG['auth_http_realm'] );
}

$title = $CFG['title'];
if( $grouplst_convert['to'] ) {
	for( $i = 0 ; $i < $catalog_num ; $i++ )
		$news_catalog[$i] = $grouplst_convert['to']( $news_catalog[$i] );
}

##############################################################################
# Authentication

$auth_success     = false;

if( $CFG['auth_type'] != 'open' ) {

	if( isset($_GET['logout']) ) {
		session_destroy();
		$uri = str_replace( 'logout=1', '', $uri );
		$uri = str_replace( '&&', '', $uri );
		$uri = preg_replace( '/[\?&]$/', '', $uri );
		if( $CFG['auth_prompt'] == 'http' )
			http_logout();
		else {
			header("Location: $uri");
			exit;
		}
	}

	$need_auth = false;

	if( $CFG['auth_type'] == 'optional' ) {


		if( isset($_GET['login']) )
			$need_auth = true;
		elseif( $self_base == 'indexing.php' || $self_base == 'read-art.php' ) {
			$server = $_GET['server'];
			$group  = $_GET['group'];
			if( $server == '' || $group == '' || !verifying( $server, $group ) ) {
				html_head( $title, 'index.php' );
				html_tail();
				exit;
			}
			foreach( $private_catalogs as $pc )
				if( $server == $news_server[$pc] && match_group( $group, $news_groups[$pc] ) )
					$need_auth = true;
		}
		elseif( need_postperm( $self_base ) || $news_authperm[$curr_catalog] )
			$need_auth = true;
	}

	$is_expire = false;

	if( $CFG['auth_expire_time'] > 0 && isset( $_SESSION['auth_time']) && $_SESSION['auth_time'] + $CFG['auth_expire_time'] < time() ) {
		$is_expire = true;
		unset($_SESSION['auth_ticket']);
	}

	if( isset( $_SESSION['auth_name'], $_SESSION['auth_ticket'] )
			&& $_SESSION['auth_ticket'] == md5( $_SESSION['auth_name'] . session_id() . $_SESSION['auth_time'] ) ) {
#		echo "<!-- authentication ticket is acknowledged -->\n";
		$auth_success = true;
	}
	elseif( $need_auth )
		switch($CFG['auth_prompt']) {
		case 'form':
			html_head( $title . ' - ' . $strAuthentication );
			form_login_dialog( $is_expire );
			html_foot();
			html_tail();
			exit;
			break;
		case 'http':
			if( !$is_expire && isset( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'] ) ) {
				$info = check_user_password( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'] );
				if( !$info )
					http_login_auth();
				$now = time();
				$_SESSION['auth_time'] = $now;
				$_SESSION['auth_name'] = $_SERVER['PHP_AUTH_USER'];
				$_SESSION['auth_with'] = 'http';
				$_SESSION['auth_info'] = $info;
				$_SESSION['auth_ticket'] = md5( $_SERVER['PHP_AUTH_USER'] . session_id() . $now );
				$auth_success = true;
			}
			else {
				unset($_SESSION['auth_ticket']);
				http_login_auth();
			}
			break;
		}
}

$info = $_SESSION['auth_info'];
if( is_array($info) && $database_convert['to'] )
	foreach( $info as $var => $value )
		$_SESSION['auth_info'][$var] = $database_convert['to']($value);

$auth_user  = vars_convert( $CFG['auth_user_fullname'] );
$auth_email = vars_convert( $CFG['auth_user_email'] );

# After authentication, $curr_catalog is permitted to access
$_SESSION['rem_catalog'] = $curr_catalog ;

##############################################################################
# Language conversion - Dependent on $curr_catalog after authentication

$article_convert  = get_conversion( $news_language[$curr_catalog], $curr_language );

#echo "artc: " . $article_convert['to'] . "<br>\n";

#if( $article_convert['to'] ) {
#	$read_claim = '// Article auto-converted from ' . $article_convert['source'] . ' to ' . $article_convert['result'];
#	$post_claim = '// Article original posted with ' . $article_convert['result'] . ' encoding';
#}

#############################################################################
# Write access log 

if( isset($CFG['log']) ) {
	$fp = fopen( $CFG['log'], 'a' );
	if( $fp ) {
		$log_uri = substr( strrchr( $uri, '/' ), 1 );
		if( $log_uri == '' )
			$log_uri = 'index.php';

		if( $auth_success )
			fwrite( $fp, sprintf( "%s %s(%s) from %s - %s\n", strftime("%Y/%m/%d %H:%M:%S"), $auth_user, $auth_email, $ip_from, $log_uri ) );
		else
			fwrite( $fp, sprintf( "%s someone from %s - %s\n", strftime("%Y/%m/%d %H:%M:%S"), $ip_from, $log_uri ) );
	}
}

#############################################################################
# Functions

function form_login_dialog( $is_expire ) {
	global $CFG;
	global $strNeedLogin, $strLoginName, $strPassWord, $strUseYourAccountAt, $strLogin, $strAuthExpired;
?>
<!-- Authentication with FORM style - - - - - - - - - - - - - -->
<center>
<font size=3><b><? echo $CFG['title']; ?></b></font><p>
<table border=1 cellspacing=0 cellpadding=10 width=80%>
<!--
<tr><td align=center bgcolor=#FFEEA0>
</td></tr>
-->
<tr><td align=center bgcolor=#DDFFDD>
<?
if( $is_expire )
	echo "<font size=2 color=black>$strAuthExpired</font>";
else
	echo "<font size=2 color=black>$strNeedLogin</font>";
echo "</td></tr><tr><td align=center bgcolor=#C0C0FF>";
echo "<font size=2>";
printf( $strUseYourAccountAt, $CFG['auth_organization'] );
?>
</font><p>
<form name=login action="login.php" method="post">
<table>
<tr>
<td align=right><? echo $strLoginName; ?></td>
<td align=left><input class=login name=loginName size=25></td>
</tr>
<tr>
<td align=right><? echo $strPassWord; ?></td>
<td align=left><input class=login name=passWord type=password size=25></td>
</tr>
<tr>
<td>&nbsp;<input name=target type=hidden value="<? echo $_SERVER['REQUEST_URI']; ?>"></td>
<td align=left><input class=b type=submit value="<? echo $strLogin; ?>"></td>
</tr>
<?
if( $CFG['auth_registration_info'] != '' ) {
	echo "<tr><td colspan=2 align=center><font color=black size=2><i>";
	echo '<br>' . $CFG['auth_registration_info'];
	echo "</i></font></td></tr>\n";
}
?>
</table>
</form>
<script language="javascript">
	document.login.loginName.focus();
</script>
</td>
</tr>
<?
	$referal = $_SERVER['REFERAL'];
	if( !$is_expire && $referal != '' ) {
		echo "<tr>\n<td bgcolor=#EEFFEE align=center>\n";
		echo "<a href=\"$referal\">$strGoBack</a>";
		echo "</td>\n</tr>\n";
	}
?>
</table>
</center>
<!-- Authentication with FORM style - - - - - - - - - - - - - -->
<?

}

function http_login_error() {
	global $CFG;
	global $strPasswordRetryFail, $strReLogin;
	session_destroy();
	html_head( $CFG['title'] );
?>
<center><table border=1 cellspacing=0 cellpadding=10>
<tr><td align=center bgcolor=#EEDDA0>
<font size=2><? echo $CFG['title']; ?></font>
</td></tr>
<tr><td align=center bgcolor=#C0A0FF height=100>
<font size=2>
<? echo $strPasswordRetryFail; ?>
</font><p>
<form><input class=b type=button value="<? echo $strReLogin; ?>" onClick='reload();'></form>
</td>
</tr>
<?
if( $CFG['auth_registration_info'] != '' ) {
	echo "<tr><td align=center bgcolor=#DDFFDD><font color=black size=2><i>";
	echo $CFG['auth_registration_info'];
	echo "</i></font></td></tr>\n";
}
?>
</table>
</center>
<?
	html_foot();
	html_tail();
	exit;
}

function http_login_auth() {
	global $CFG;
	header( "WWW-Authenticate: Basic realm=\"${CFG['auth_http_realm']}\"");
	header( "HTTP/1.0 401 Unauthorized");
	http_login_error();
}

function http_logout() {
	global $title, $strLogout, $strHTTPlogoutInfo, $strCloseWindow;

	html_head( "$title - $strLogout" );
	echo "<font size=2><b>$title - $strLogout</b></font><hr>";
	echo "<font size=3 color=black>$strHTTPlogoutInfo</font><p>\n";
	echo "<form><input type=button class=b value=\"$strCloseWindow\" onClick='close_window();'></form>\n";
	html_foot();
	html_tail();
	exit;
}

function session_error( $server, $group ) {
	global $strSessionError, $strSessionErrorReason, $strGroupList, $strSessionErrorReturn;
	html_head($strSessionError);
	echo "<font size=2>\n";
	printf( $strSessionErrorReason . "<p>\n", "$server/$group" );
	printf( $strSessionErrorReturn, "<a href=index.php>$strGroupList</a>" );
	echo "</font>";
	html_tail();
	exit;
}

function readonly_error( $server, $group ) {
	global $strNoPostPermission, $strNewsServer, $strGroup, $strCloseWindow;

	html_head($strNoPostPermission);
?>
<center>
<table border=1 cellspacing=0 cellpadding=10>
<tr><td align=center bgcolor=#A0FF30>
<? echo "<font size=3>$strNoPostPermission</font>"; ?>
</td></tr>
<tr><td height=80 bgcolor=#A0EEFF align=center>
<table>
<?
	echo "<tr><td align=right>$strNewsServer:</td><td>$server</td></tr>\n";
	echo "<tr><td align=right>$strGroup:</td><td>$group</td></tr>\n";
?>
</table>
</td></tr>
</table>
<form><input class=b type=button value="<? echo $strCloseWindow; ?>" onClick='close_window();'></form>
</center>
<?
	html_foot();
	html_tail();
	exit;
}

function vars_convert( $instr ) {
	$info = $_SESSION['auth_info'];
	if( is_array($info) )
		foreach( $info as $var => $value )
			$instr = str_replace( $var, $value, $instr );
	return($instr);
}


function need_postperm( $script_name ) {
	$post_action = array( 'post-art.php', 'reply-art.php', 'forward-art.php', 'xpost.php', 'delete-art.php' );
	if( in_array( $script_name, $post_action ) )
		return(true);
	else
		return(false);
}

function verifying( $server, $group ) {

	global $news_groups, $news_server, $catalog_num;

	if( $server == '' || $group == '' )
		return(false);

	for( $i = 0 ; $i < $catalog_num ; $i++ ) {
		if( $server == $news_server[$i]
			&& match_group( $group, $news_groups[$i] ) )
			return(true);
	}

	return(false);
}

function match_group( $group, $pattern ) {

	if( $pattern == '*' ) {
		return(true);
		break;
	}

	$groups = split( ',', $pattern );

	foreach ( $groups as $reg ) {
		$reg = ereg_replace( '\.', '\\.', $reg );
		$reg = ereg_replace( '\*', '.+', $reg );
		if( eregi( "^$reg\$", $group ) )
			return(true);
	}
	return(false);
}

/*
function allow_testing( $server, $group ) {
	global $testing_groups;
	$ok = false;
	$test_num = count( $testing_groups );
	for( $i = 0 ; $i < $test_num ; $i++ ) {
		list( $s, $g ) = split( '/', $testing_groups[$i] );
		echo "<!-- $s / $g -->\n";
		if( $s == $server && $g == $group ) {
			$ok = true ;
			break;
		}
	}
	return($ok);
}
*/


?>
