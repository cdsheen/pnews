<?

#
# PHP News Reader Configuration File
#
# Copyright (C) 2001-2003 - All rights reserved
# Shen Cheng-Da (cdsheen@users.sourceforge.net)

# This is a sample configuration.
# You should make a copy of this file at the directory of pnews.

# After making a copy, you can begin editing this file,

# In this configuration, you can use on as true, and off as false
# Also note that no quote is needed, for example:  $foobar = on;

define( 'on',  true,  true );
define( 'off', false, true );

# The configuration contains 3 sections:
#
#     Section 1 - Authentication
#     Section 2 - Contents
#     Section 3 - Languages

# The setting with 'REQUIRED' mark and do not come with a default value
# and should be explicitly configured

############################################################################
# Section 1 - Authentication - Configure how users login your system
############################################################################

# [url_rewrite] Turn on URL rewrite (Need Apache's mod_rewrite support)
#               (default: false)

# $CFG['url_rewrite'] = true;

# [url_base] The base url of your PHP News Reader installation
#            (REQUIRED if url_rewrite == true )

# $CFG['url_base'] = 'http://webnews.foobar.com/news/';


# [auth_type] Specify the authentication type (REQUIRED)
#  "required" - authentication is required to access the while system
#  "optional" - authentication is only required for posting and forwarding
#      "open" - authentication is not needed

$CFG["auth_type"] = "optional";


# [auth_prompt] Specify the style of login prompt (default: "form")
#               (used when auth_type != 'open')
#      "http" - authentication via HTTP login window    (default)
#      "form" - authentication with login form

$CFG["auth_prompt"] = "form";


# [auth_http_realm] Specify the realm used in http authentication prompt
#                   (REQUIRED if auth_prompt == 'http')

$CFG["auth_http_realm"] = "XXX";


# [auth_method] Specify the authentication method
#               REQUIRED if auth_type != "open"
#      "ldap" - authenticated via LDAP server,
#      "pop3" - authenticated via POP3 server,
#       "ftp" - authenticated via FTP server,
#      "mail" - authenticated via multiple POP3 server,
#     "mysql" - authenticated via MySQL database,
#     "pgsql" - authenticated via PostgreSQL database,
#      "nntp" - authenticated via NNTP server,
#      "user" - authenticated via your self-defined method,

// $CFG["auth_method"] = "ftp";


# [auth_organization] Specify the organization of authentication source
#                     REQUIRED if auth_type != 'open'

$CFG["auth_organization"] = "XXX Club";

# [auth_registration_info] Tell users how to get an account (default: "")
# This message will be displayed on login (FORM) or logout (HTTP) windows

// $CFG["auth_registration_info"] = "You can register an account at <a hreg=http://foobar.com/>Nopy's Club</a>";


# [auth_user_module] Specify user-defined authentication module location
#                    REQUIRED if auth_method == 'user'

// $CFG["auth_user_module"] = "auth/my_auth.inc.php";

# You should implement a function with this prototype in your module:
#
#     function check_user_password( $username, $password )


# LDAP authentication parameters (REQUIRED if auth_method == 'ldap')
#    [ldap_server] LDAP server address ( address:port )
#        [ldap_dn] LDAP distinguish name
#  [ldap_bind_rdn] LDAP bind RDN, %u replaced by username (default: "%u")
#  [ldap_bind_pwd] LDAP bind password, %p replaced by password (default: "%p")
#    [ldap_filter] LDAP search filter (default: "(cn=%u)")
#  [ldap_variable] The attributes extract from this LDAP search for later use
#                  (default: null) ( %u can not be used here )

// $CFG["ldap_server"]   = "ldap.domain.org";
// $CFG["ldap_dn"]       = "ou=members, o=root";
// $CFG["ldap_bind_rdn"] = "cn=%u,ou=members,o=root";
// $CFG["ldap_bind_pwd"] = "%p";
// $CFG["ldap_variable"] = array( "%e" => "Email", "%f" => "Fullname" );
// $CFG["ldap_variable"] = array( "%e" => "Email" );
// $CFG["ldap_filter"]   = "(&(cn=%u)(accountStatus=1)(MailStatus=1))";
// $CFG["ldap_filter"]   = "(cn=%u)";


# FTP authentication parameters (REQUIRED if auth_method == 'ftp')
#      [$ftp_server] FTP server address ( address:port )
#        [$ftp_deny] The user list which is denied for FTP authentication
#                    (default: array( 'anonymous', 'guest', 'ftp' ) )

// $CFG["ftp_server"]      = "ftp.domain.org";
// $CFG["ftp_deny"]        = array( 'anonymous', 'guest', 'ftp', 'root' );


# POP3 authentication parameters (REQUIRED if auth_method == 'pop3')
#      [pop3_server] POP3 server address ( address:port )
# [pop3_user_modify] fix the username used for POP3, %u replaced by username
#                    (default: "%u")

// $CFG["pop3_server"]      = "pop3.domain.org";
// $CFG["pop3_user_modify"] = "%u";


# Mail authentication parameters (REQUIRED if auth_method == 'mail')
#     [pop3_mapping] the mapping from E-Mail to POP3 server address

// $CFG["pop3_mapping"] = array( "@csie.nctu.edu.tw" => "pop3.csie.nctu.edu.tw",
//				 "@mail.domain.org"  => "pop3.domain.org" );

# MySQL/PostgreSQL Database authentication parameters
# (REQUIRED if auth_method == 'mysql' || auth_method == 'pgsql' )

#          [db_server] The database server address (address:port)
#            [db_name] The database name
#        [db_username] The username used to connect database
#        [db_password] The password used to connect database
#           [db_table] The table name for user information
#  [db_field_username] The field name for username
#  [db_field_password] The field name for password
#  [db_password_crypt] The password encrypt method (default: "" - cleartext)
#                      current supported are: "md5", "crypt"

$CFG["db_server"]         = "database.domain.org";
$CFG["db_name"]           = "phpbb";
$CFG["db_username"]       = "db_user";
$CFG["db_password"]       = "db_pass";
$CFG["db_table"]          = "phpbb_users";
$CFG["db_field_username"] = "username";
$CFG["db_field_password"] = "user_password";
$CFG["db_password_crypt"] = "md5";

$CFG["db_variable"] = array( "%e" => "user_email" );

# NNTP authentication parameters (REQUIRED if auth_method == 'nntp')
#      [$auth_nntp_server] NNTP server address ( address:port )

// $CFG["auth_nntp_server"]      = "news.domain.org";


# [auth_expire_time] After this time in seconds, authentication is expired
#                    And login again is required. Zero means never expire
#                    (default: 3600 seconds)

$CFG["auth_expire_time"] = 1800;


# [post_restriction] Disallow the posting and forwarding of articles
#                    (default: off)

// $CFG["post_restriction"] = on;


# [auth_user_fullname]  The full name of authenticated user (default: "%u")

// $CFG["auth_user_fullname"] = "%f";


# [auth_user_email] The E-Mail of authenticated user (REQUIRED)
#   The %u will be replaced by the username enter by authentication session
#   Other variables are defined in the [db_variable] or [ldap_variable]
#   (If you use 'mail' auth-method, %e will be the user's E-Mail,
#    and %u will be the user name of the E-Mail - i.e: the string before '@')

// $CFG["auth_user_email"] = "%u@mail.domain.com";
// $CFG["auth_user_email"] = "%e";


# [log] Enable access log (default: "" - no log)
# You need to create this file w/ write permission to the user running httpd

// $CFG["log"] = "/var/log/webnews.log";


############################################################################
# Section 2 - Contents - Configure the contents
############################################################################


# [title] The title for this webnews (default: "Webnews")

$CFG["title"] = "XXX News Service";


# [banner] The banner text or images, ex: "<img src=banner.jpg>" (default: "")

// $CFG["banner"] = "<img src=banner.jpg>";


# [group_list] The group definition that show on this Web News
#              You should get a copy from examples/newsgroups.lst
#              (default: "newsgroups.lst")

// $CFG["group_list"] = "/somewhere/newsgroups.lst";


# [group_sorting] sort newsgroups in each catalog (default: off)

// $CFG["group_sorting"] = on;


# [articles_per_page] Number of articles shown in one single page (default: 20)

// $CFG['articles_per_page'] = 30;


# [article_order_reverse] reverse the order while showing articles list (default: off)

// $CFG["article_order_reverse"] = on;


# [show_article_popup] Show article in pupup window (default: off)

// $CFG["show_article_popup"] = on;


# [filter_ansi_color] Show article in pupup window (default: on)

// $CFG["filter_ansi_color"] = off;


# [time_format] The format used to displaying time (default: "%Y/%m/%d %H:%M:%S")

// $CFG["time_format"] = "%Y/%m/%d %H:%M:%S";


# [style_sheet] Alternative CSS (default: "style.css")

// $CFG["style_sheet"] = 'my_style.css';


# [language_switch] Show language switch or not (default: on)

// $CFG["language_switch"] = off;


# [organization] The organization name of this site (default: "News Server")

$CFG["organization"] = "XXX News Server";


# [post_signature] The signature to appended at each posted article
#                  (default: "")

// $CFG["post_signature"] = "\n-- \nPOST BY: PHP News Reader\n";


# [show_sourceforge_logo] Show SourceForge logo (default: true)

// $CFG["show_sourceforge_logo"] = false;

# [links] The links referring to other pages, (default: null)

// $CFG["links"] = array( "Back Home" => "../index.php",
//			  "Tech News" => "http://foo.bar/technews/" );


############################################################################
# Section 3 - Languages - Configure the setting about languages
############################################################################

# [language] The language setting (default: "en")
#             [config] The language setting used in this config.inc.php
#           [grouplst] The language setting used in newsgroups.lst
#           [database] The language setting used in database or LDAP
#          [interface] The initial language setting of interface
#
#    Natively supported languages and the codings are:
#        "en" - Englush                    US-ASCII
#     "zh-tw" - Traditional Chinese        BIG5
#     "zh-cn" - Simplified Chinese         GB2312
#   "unicode" - Unicode (Mainly Chinese)   UTF-8    (default)

$CFG["charset"]["config"]    = "big5";
$CFG["charset"]["grouplst"]  = "big5";
$CFG["charset"]["database"]  = "big5";
$CFG["charset"]["interface"] = "big5";


############################################################################
# PHP News Reader
# Copyright (C) 2001-2003 - All rights reserved
# Shen Cheng-Da (cdsheen@csie.nctu.edu.tw)
############################################################################

?>
