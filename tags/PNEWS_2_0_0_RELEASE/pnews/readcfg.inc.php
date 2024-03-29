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

/* Read and check the configuration (config.inc.php) */

if( !file_exists('config.inc.php') )
	show_error( "You should edit your 'config.inc.php'. Copy examples/config.inc.php as a template.");

require_once('config.inc.php');

$valid_auth_type   = array( 'required', 'optional', 'open' );
$valid_auth_prompt = array( 'http', 'form' );
$valid_auth_method = array( 'ldap', 'pop3', 'mail', 'ftp', 'mysql', 'pgsql', 'user' );

if( !in_array( $CFG['auth_type'], $valid_auth_type ) ) {
	config_error( '$CFG["auth_type"]' );
}
if( $CFG['auth_type'] != 'open' ) {

	if( !isset($CFG['auth_prompt']) )
		$CFG['auth_prompt'] = 'form';
	elseif( !in_array( $CFG['auth_prompt'], $valid_auth_prompt ) )
		config_error( '$CFG["auth_prompt"]' );

	if( $CFG['auth_prompt'] == 'http' && !isset($CFG['auth_http_realm']) )
		config_error( '$CFG["auth_http_realm"]' );

	if( !isset($CFG['auth_method']) || !in_array( $CFG['auth_method'], $valid_auth_method ) )
		config_error( '$CFG["auth_method"]' );

	switch( $CFG['auth_method'] ) {
	case 'pop3':
		if( !isset( $CFG['pop3_server'] ) )
			config_error( '$CFG["pop3_server"]' );
		if( !isset( $CFG['pop3_user_modify'] ) )
			$CFG['pop3_user_modify'] = '%u';
		if( file_exists('auth/pop3.inc.php') )
			include('auth/pop3.inc.php');
		else
			show_error( 'POP3 authentication module missed' );
		if( !function_exists( 'check_user_password' ) )
			show_error( 'POP3 authentication module is invalid' );
		break;
	case 'mail':
		if( !isset( $CFG['pop3_mapping'] ) )
			config_error( '$CFG["pop3_mapping"]' );
		if( file_exists('auth/mail.inc.php') )
			include('auth/mail.inc.php');
		else
			show_error( 'Mail authentication module missed' );
		if( !function_exists( 'check_user_password' ) )
			show_error( 'Mail authentication module is invalid' );
		break;
	case 'ldap':
		if( !function_exists( 'ldap_connect' ) )
			show_error( 'Your PHP does support LDAP' );
		if( !isset( $CFG['ldap_server'], $CFG['ldap_dn'], $CFG['ldap_bind_rdn'], $CFG['ldap_bind_pwd'] ) )
			config_error( '$CFG["ldap_*"]' );
		if( !isset( $CFG['ldap_filter'] ) )
			$CFG['ldap_filter'] = '(cn=%u)';
		if( file_exists('auth/ldap.inc.php') )
			include('auth/ldap.inc.php');
		else
			show_error( 'LDAP authentication module missed' );
		if( !function_exists( 'check_user_password' ) )
			show_error( 'LDAP authentication module is invalid' );
		break;
	case 'ftp':
		if( !isset( $CFG['ftp_server'] ) )
			config_error( '$CFG["ftp_server"]' );
		if( !isset( $CFG['ftp_deny'] ) )
			$CFG['ftp_deny'] = array( 'anonymous', 'guest', 'ftp' );
		if( file_exists('auth/ftp.inc.php') )
			include('auth/ftp.inc.php');
		else
			show_error( 'FTP authentication module missed' );
		if( !function_exists( 'check_user_password' ) )
			show_error( 'FTP authentication module is invalid' );
		break;
	case 'mysql':
		if( !function_exists( 'mysql_connect' ) )
			show_error( 'Your PHP does support MySQL' );
		if( file_exists('auth/mysql.inc.php') )
			include('auth/mysql.inc.php');
		else
			show_error( 'MySQL authentication module missed' );
		if( !function_exists( 'check_user_password' ) )
			show_error( 'MySQL authentication module is invalid' );
		check_db_settings();
		break;
	case 'pgsql':
		if( !function_exists( 'pg_connect' ) )
			show_error( 'Your PHP does support PostgreSQL' );
		if( file_exists('auth/pgsql.inc.php') )
			include('auth/pgsql.inc.php');
		else
			show_error( 'PostgreSQL authentication module missed' );
		if( !function_exists( 'check_user_password' ) )
			show_error( 'PostgreSQL authentication module is invalid' );
		check_db_settings();
		break;
	case 'user':
		if( file_exists( $CFG['auth_user_module'] ) )
			include( $CFG['auth_user_module'] );
		else
			show_error( 'User-defined authentication module missed' );

		if( !function_exists( 'check_user_password' ) )
			show_error( 'User-defined authentication module is invalid' );
	}

	if( !isset($CFG['auth_organization']) )
		config_error( '$CFG["auth_organization"]' );

	if( !isset($CFG['auth_expire_time']) )
		$CFG['auth_expire_time'] = 3600;

}

if( !isset( $CFG['banner'] ) )
	$CFG['banner'] = '';

if( !isset( $CFG['title'] ) )
	$CFG['title'] = 'Webnews';

if( !isset( $CFG['group_sorting'] ) )
	$CFG['group_sorting'] = false;

if( !isset( $CFG['organization'] ) )
	$CFG['organization'] = 'News Server';

if( !isset( $CFG['auth_registration_info'] ) )
	$CFG['auth_registration_info'] = '';

if( !isset( $CFG['post_restriction'] ) )
	$CFG['post_restriction'] = false;

if( !isset( $CFG['post_signature'] ) )
	$CFG['post_signature'] = '';

if( !isset( $CFG['auth_user_fullname'] ) )
	$CFG['auth_user_fullname'] = '%u';

if( !isset( $CFG['auth_user_email'] ) )
	config_error( '$CFG["auth_user_email"]' );

$checks = array( 'config', 'grouplst', 'database', 'interface' );
$valid_language = array( 'en', 'zh-tw', 'zh-cn', 'unicode' );

foreach( $checks as $section ) {
	if( !isset( $CFG['language'][$section] ) )
		$CFG['language'][$section] = 'en';
	elseif( !in_array( $CFG['language'][$section], $valid_language ) )
		config_error( '$CFG["language"]["' . $section . '"]' );
}

$default_language = $CFG['language']['interface'];

if( !isset( $CFG['group_list'] ) )
	$CFG['group_list'] = 'newsgroups.lst';

if( !file_exists( $CFG['group_list'] ) )
	config_error( '$CFG["group_list"]' );

/* un-documented settings */

if( !isset($CFG['language_switch']) )
	$CFG['language_switch'] = true;

if( !isset($CFG['author_link']) )
	$CFG['author_link'] = true;

if( isset($CFG['log']) && !is_writable($CFG['log']) )
	show_error( '$CFG["log"]: You does not have write permission on ' . $CFG['log'] );

if( !isset($CFG["links"]) )
	$CFG["links"] = null;

/* ------------------------------------------------------------------------ */

function check_db_settings() {
	global $CFG;

	if( !isset( $CFG['db_name'] ) )
		config_error( '$CFG["db_name"]' );
	if( !isset( $CFG['db_username'] ) )
		config_error( '$CFG["db_username"]' );
	if( !isset( $CFG['db_password'] ) )
		config_error( '$CFG["db_password"]' );
	if( !isset( $CFG['db_table'] ) )
		config_error( '$CFG["db_table"]' );
	if( !isset( $CFG['db_field_username'] ) )
		config_error( '$CFG["db_field_username"]' );
	if( !isset( $CFG['db_field_password'] ) )
		config_error( '$CFG["db_field_password"]' );
	if( isset( $CFG['db_password_crypt'] ) && !function_exists( $CFG['db_password_crypt'] ) )
		config_error( '$CFG["db_password_crypt"]' );
}

function config_error( $error_var ) {
	show_error( "The <b>$error_var</b> setting is incorrect in your config.inc.php !");
}

function show_error( $err_string ) {
?>
<html>
<head>
<title>Configuration Error</title>
<style>
body { color: white ; background: black }
</style>
</head>
<body>
<?
	echo "<font size=2><b>Configuration Error</b></font><hr>";
	echo "<font size=3>$err_string</font>\n";
	echo "</body>\n</html>\n";
	exit;
}


?>
