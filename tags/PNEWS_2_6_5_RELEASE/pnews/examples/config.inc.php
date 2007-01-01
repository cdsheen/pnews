<?

# Do not put anything (including blanks and line breaks) before '<' and '?'

#
# PHP News Reader Configuration File
#
# Copyright (C) 2001-2007 - All rights reserved
# Shen Cheng-Da (cdsheen at users.sourceforge.net)

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


# [auth_type] Specify the authentication type (REQUIRED)
#  "required" - authentication is required to access the while system
#  "optional" - authentication is only required for posting and forwarding
#      "open" - authentication is not needed

$CFG["auth_type"] = "optional";


# [auth_prompt] Specify the style of login prompt (default: "form")
#               (used when auth_type != 'open')
#      "http" - authentication via HTTP login window    (default)
#      "form" - authentication with login form
#     "other" - authentication with third party system (>=2.5.6)

$CFG["auth_prompt"] = "form";


# [global_readonly] Prohibits the posting and forwarding of articles,
#                   even if user performs login. (default: false)

// $CFG["global_readonly"] = true;


# [auth_http_realm] Specify the realm used in http authentication prompt
#                   (REQUIRED if auth_prompt == 'http')

$CFG["auth_http_realm"] = "XXX";


# [auth_method] Specify the authentication method
#               REQUIRED if auth_type != "open"
#      "ldap" - authenticated via LDAP server,
#      "pop3" - authenticated via POP3 server,
#     "pop3s" - authenticated via POP3 over SSL server, (>=v2.4.1)
#       "ftp" - authenticated via FTP server,
#      "ftps" - authenticated via FTP over SSL server, (>=v2.4.1)
#      "mail" - authenticated via multiple POP3 server,
#     "mysql" - authenticated via MySQL database,
#     "pgsql" - authenticated via PostgreSQL database,
#      "nntp" - authenticated via NNTP server,
#     "nntps" - authenticated via NNTP over SSL server, (>=v2.4.0)
#       "cas" - authenticated via CAS (>=v2.3.0)
#     "phpbb" - authenticated via phpBB (>=v2.5.6)
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


# FTPS authentication parameters (REQUIRED if auth_method == 'ftp')
#      [$ftps_server] FTP over SSL server address ( address:port )
#        [$ftps_deny] The user list which is denied for FTPS authentication
#                    (default: array( 'anonymous', 'guest', 'ftp' ) )

// $CFG["ftps_server"]      = "ftps.domain.org";
// $CFG["ftps_deny"]        = array( 'anonymous', 'guest', 'ftp', 'root' );


# POP3 authentication parameters (REQUIRED if auth_method == 'pop3')
#      [pop3_server] POP3 server address ( address:port )

// $CFG["pop3_server"]      = "pop3.domain.org";


# POP3S authentication parameters (REQUIRED if auth_method == 'pop3s')
#      [pop3_server] POP3S server address ( address:port )

// $CFG["pop3s_server"]      = "pop3s.domain.org";


# Mail authentication parameters (REQUIRED if auth_method == 'mail')
#   [pop3_mapping] the mapping from E-Mail to POP3 server address
#  [domain_select] Show domain-list selector in login dialog (default: true)

// $CFG["pop3_mapping"] = array( "@foobar.com"       => "pop3.foobar.com",
//				 ".bbs@bbs.haha.com" => "bbs.haha.com",
//				 "@mail.domain.org"  => "pop3.domain.org" );

// $CFG["domain_select"] = false;

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


# NNTPS authentication parameters (REQUIRED if auth_method == 'nntps')
#      [$auth_nntps_server] NNTPS server address ( address:port )

// $CFG["auth_nntps_server"]      = "nntps.domain.org";


# CAS authetication parameters (REQUIRED if auth_method == 'cas')
#   [$auth_cas_server] CAS server FQDN and port ( address:port )
#   [$auth_cas_base_uri] CAS server base URI
#   [$auth_cas_debug] a file to debug phpCAS or FALSE to turn debugging off
#                     (default: false)

$CFG["auth_cas_server"] = 'cas.domain.name:8443';
$CFG["auth_cas_base_uri"] = 'cas';

// $CFG["auth_cas_debug"] = '/var/log/phpcas.log';

# phpBB authetication parameters (REQUIRED if auth_method == 'phpbb')
#   [$auth_phpbb_url_base] The base URL of your phpBB installation
#   [$auth_phpbb_path]     The relative of absolute path of
#                          your phpBB installation

// $CFG['auth_phpbb_url_base'] = 'http://phpbb.foobar.com/';
// $CFG['auth_phpbb_path'] = '../phpbb/';


# [auth_expire_time] After this time in seconds, authentication is expired
#                    And login again is required. Zero means never expire
#                    (default: 3600 seconds)

$CFG["auth_expire_time"] = 1800;


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


# [log_level] Log verbose level (default: 3)
#       0 - no log
#       1 - log only post/reply/xpost/forward/delete actions.
#       2 - log all actions for authenticated users.
#       3 - log all actions for all users.

// $CFG["log_level"] = 1;


# [debug_level] Debug verbose level (default: 0)
#       0 - Turn off NNTP debug information
#       1 - NNTP debug information will be embeded in HTML comments
#       2 - NNTP debug information will be shown inline with HTML

// $CFG["debug_level"] = 1;


############################################################################
# Section 2 - Contents - Configure the contents
############################################################################

# [url_base] The base url of your PHP News Reader installation (REQUIRED)

# $CFG['url_base'] = 'http://webnews.foobar.com/news/';


# [url_rewrite] Turn on URL rewrite (Need Apache's mod_rewrite support)
#               (default: false)

# $CFG['url_rewrite'] = true;


# [https_login] Whether to use SSL(HTTPS) after authentication (default: false)

# $CFG['https_login'] = true;


# [style_sheet] Configure the style sheet (default: "standard.css")
#               this style sheet must be reside in the css/ subdirectory.

# $CFG['style_sheet'] = "fancy.css";


# [title] The title for this webnews (default: "Webnews")

$CFG["title"] = "XXX News Service";


# [banner] The banner text or images, ex: "<img src=banner.jpg>" (default: false)

// $CFG["banner"] = "<img src=banner.jpg>";


# [html_header] The file to be included as page header (default: false)

// $CFG["html_header"] = "header.htm";


# [html_footer] The file to be included as page footer (default: false)

// $CFG["html_footer"] = "footer.htm";


# [group_list] The group definition that show on this Web News
#              You should get a copy from examples/newsgroups.lst
#              (default: "newsgroups.lst")

// $CFG["group_list"] = "/somewhere/newsgroups.lst";


# [referrer_enforcement] enforce the browsing to begin from $CFG["url_base"]
#                        (default: false)

// $CFG["referrer_enforcement"] = true;


# [confirm_post] The message prompted to confirm the posting
#                (post/reply/crosspost) (default: false)

// $CFG["confirm_post"] = "Really post the message ?"; 


# [confirm_forward] The message prompted to confirm the forwarding
#                   (default: false)

// $CFG["confirm_forward"] = "Really forward the message ?";


# [magic_tag] Use magic tag to indicate the unread state of newsgroup
#             (default: flase)
#

// $CFG["magic_tag"] = true;


# [cache_dir] Enable caching of indexing data   (default: false)
#             To enable caching, set this to the directory of cache data.
#             You should grant write permission on this directory to the
#             user running PHP.

// $CFG["cache_dir"] = "/tmp/pnews-cache";


# [thread_enable] Enable threading (default: false)
#   Articles in the same thread will be listed when you view one
#     of the message in the thread).

// $CFG["thread_enable"] = true;


# [thread_db_format] Setting the dba handler used for storing threading data
#    may be one of: dbm, ndbm, gdbm, db2, db3 or db4
#    default: 'db3' prior to PHP 4.3.2, and 'db4' after PHP 4.3.2

// $CFG["thread_db_format"] = 'db4';


# [group_sorting] sort newsgroups in each category (default: false)

// $CFG["group_sorting"] = true;


# [article_numbering_reverse] use the old-fashion article numbering
#                             in article-list page (default: false)

// $CFG["article_numbering_reverse] = true;


# [image_inline] The uuencoded image attachment will be shown
#                inline along with article (default: true)

// $CFG['image_inline'] = false;


# [allow_attach_file] Max. number of allowed attachment in posting (default: 2)

// $CFG["allow_attach_file"] = 0;


# [articles_per_page] Number of articles shown in one single page (default: 20)

// $CFG['articles_per_page'] = 30;


# [email_editing] Allow E-Mail editing when posting article (default: true)

// $CFG["email_editing"] = false;


# [hide_email] Hide E-Mail - makes it difficult for SpamBots
#              to automatically obtain email addresses
#              when they scan webnews pages (default: true)

// $CFG["hide_email"] = false;


# [filter_ansi_color] Show article in pupup window (default: true)

// $CFG["filter_ansi_color"] = false;


# [time_format] The format used to displaying time (default: "%Y/%m/%d %H:%M:%S")

// $CFG["time_format"] = "%Y/%m/%d %H:%M:%S";


# [style_sheet] Alternative CSS (default: "style.css")

// $CFG["style_sheet"] = 'my_style.css';


# [language_switch] Show language switch or not (default: true)

// $CFG["language_switch"] = false;


# [organization] The organization name of this site (default: "News Server")

$CFG["organization"] = "XXX News Server";


# [post_signature] The signature which been appended at each posted article
#                  (default: "")

// $CFG["post_signature"] = "\n-- \nPOST BY: PHP News Reader\n";


# [meta_description] The META description embeded in HTML header
#                    (default: "PHP News Reader")

// $CFG["meta_description"] = "Web-based News Reader";


# [meta_keywords] The META keywords embeded in HTML header
#                 (default: "news,pnews,webnews,nntp")

// $CFG["meta_keywords"] = "news,computer,network";


# [show_sourceforge_logo] Show SourceForge logo (default: false)

$CFG["show_sourceforge_logo"] = true;

# [links] The links referring to other pages, (default: null)

// $CFG["links"] = array( "Back Home" => "../index.php",
//			  "Tech News" => "http://foo.bar/technews/" );


############################################################################
# Section 3 - Languages - Configure the setting about languages
############################################################################
#
#    Natively supported languages and the codings are:
#        "en" - English                    US-ASCII
#     "zh-tw" - Traditional Chinese        BIG5
#     "zh-cn" - Simplified Chinese         GB2312
#   "unicode" - Unicode                    UTF-8       (default)
#        "fr" - French                     iso-8859-1
#        "fi" - Finnish                    iso-8859-1
#        "de" - German                     iso-8859-1
#        "it" - Italiano                   iso-8859-1
#        "sk" - Slovak                     iso-8859-2

# [interface_language] The language setting of interface (default: "en")

$CFG["interface_language"] = "en";

# [charset] The charset setting for various resource (default: "utf-8")
#             [config] The language setting used in this config.inc.php
#           [grouplst] The language setting used in newsgroups.lst
#           [database] The language setting used in database or LDAP

$CFG["charset"]["config"]    = "big5";
$CFG["charset"]["grouplst"]  = "big5";
$CFG["charset"]["database"]  = "big5";


############################################################################
# PHP News Reader
# Copyright (C) 2001-2005 - All rights reserved
# Shen Cheng-Da (cdsheen at users.sourceforge.net)
############################################################################

# Do not put anything (including blanks and line breaks) after '?' and '>'

?>
