<?

# PHP News Reader
# Copyright (C) 2001-2007 Shen Cheng-Da
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

require_once('../version.inc.php');

$dname = 'pnews-' . str_replace( 'v', '', $pnews_version ) . '.tgz' ;

$pname = 'pnews' . preg_replace( '/\D/', '', $pnews_version ) ;

echo <<<EOH
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5" />
<LINK REL=STYLESHEET TYPE="text/css" HREF="style.css" />
<title>PHP News Reader - Installation and Configuration Guide</title>
</head>
<body style="background-color: #EEFFFF">
EOH;

include('header.php');

?>
<div>
<?
if( file_exists($adfile) )
	@include($adfile);
?>
<ol>
<li><a href=#upgrade>Upgrade from previous version</a>
<li><a href=#newinstall>New installation</a>
<li><a href=#config_inc_php>Configuration of <b>config.inc.php</b></a>
<li><a href=#newsgroups_lst>Configuration of <b>newsgroups.lst</b></a>
</ol>
</div>
<div class=hr></div>
<a name=upgrade></a>
<div>
<strong><em>1. Upgrade from previous version</em></strong> 
<blockquote>
To upgrade PHP News Reader from the previous version, please follow
the following procedure:
<ol>
<li>Backup <b>config.inc.php</b> and <b>newsgroups.lst</b>
<li><a href="http://sourceforge.net/project/showfiles.php?group_id=71412" target=_blank>Download</a> the latest version of PHP News Reader
<li>Unpack the latest version of PHP News Reader and over-write the existing installation
<li>Restore <b>config.inc.php</b> and <b>newsgroups.lst</b>
<li>Reading the <a href=history.php>release notes</a> in latest version, and modify <b>config.inc.php</b> or <b>newsgroups.lst</b> if appliable
</ol>
And if your previous installation was pulled from our <a href=http://subversion.tigris.org/ target=_blank>Subversion</a> repository, the upgrading process is even more simple:
<ol>
<li>Run "<b>svn update</b>" in the directory which PHP News Reader was installed.
<li>Reading the <a href=history.php>release notes</a> in latest version, and modify <b>config.inc.php</b> or <b>newsgroups.lst</b> if appliable
</ol>
</blockquote>
<div class=hr></div>
<a name=newinstall></a>
<strong><em>2. New Installation</em></strong> 
<blockquote>
  <p> The installation of PHP News Reader is quite simple.<br />
    You can download the latest source of PHP News Reader from:</p>
  <blockquote> 
    <p> <a href="http://pnews.sourceforge.net/" target="_blank">http://pnews.sourceforge.net/</a></p>
  </blockquote>
  <p> The source is packaged in two different format, one is tar+gzip (tgz),
and the other is zip.<br />Please download your preferred format from SourceForge.</p>
  <p> After downloaded the source, extract the source tarball in the directory where your server want to provide News service.</p>
      Supposed the DocumentRoot of your web is <b>/usr/local/apache/htdocs/</b>,</p>
  <blockquote>
    <p> <strong># cd &nbsp;/usr/local/apache/htdocs/<br />
    # tar &nbsp;zxvf &nbsp;<? echo $pname; ?>.tgz</strong></p>
  </blockquote>
  or if you prefer the zip format,
  <blockquote> 
    <p> <strong># cd &nbsp;/usr/local/apache/htdocs/<br />
    # unzip &nbsp;<? echo $pname; ?>.zip</strong></p>
  </blockquote>
  <p> You may want to change the directory name, for example:</p>
  <blockquote> 
    <p> <strong># mv &nbsp;<? echo $pname; ?> &nbsp;pnews</strong>
  </blockquote>
  <p> Alternatively, you can pull the source from repository directly if you have <a href=http://subversion.tigris.org/ target=_blank>Subversion</a> client:</p>
  <blockquote> 
    <p> <strong># svn co https://pnews.svn.sourceforge.net/svnroot/pnews/trunk/pnews</strong>
  </blockquote>
  Using <a href=http://subversion.tigris.org/ target=_blank>Subversion</a> to check out the latest sources is recommended, and it makes the future upgrading easy.
  <p> The configuration of PHP News Reader contains two files, "config.inc.php" 
    and "newsgroups.lst"<br />
    You can refer the template of both files in the subdirectory "examples/".</p>
  <p> The config.inc.php should be put in the root directory of PHP News Reader, 
    (such as /usr/local/apache/htdocs/pnews/)</p>
  <p> You can copy the file from the examples/ directory as a reference.</p>
  <blockquote> 
    <p> <strong># cd &nbsp;/usr/local/apache/htdocs/pnews/<br />
      # cp &nbsp;examples/config.inc.php&nbsp;&nbsp; .</strong></p>
  </blockquote>
  <p> The default location of "newsgroups.lst" is the same as config.inc.php,<br />
    but this can be changed by modifying the setting in "config.inc.php".</p>
  <p> If you use the default setting, you can also copy the file from examples/ 
    subdirectory.</p>
  <blockquote> 
    <p> <strong># cp &nbsp;examples/newsgroups.lst&nbsp;&nbsp; .</strong></p>
  </blockquote>
  <p> Now, you can begin the editing of these two files.</p>
  <p>If you finished the editing of both files, you can then use your favorite 
    browser to access your new born Web News,<br />
    for example:</p>
  <blockquote> 
    <p><b>http://your.web.server/pnews/</b></p>
  </blockquote>
  <p>Note that you should have started your web server and make sure that PHP module is enabled.</p>
  <p>If you have any problems, or find any errors in this document,<br />please send 
    your comments to cdsheen@users.sourceforge.net, any suggestion is appreciated.</p>
</blockquote>
<div class=hr></div>
<a name=config_inc_php></a>
<strong><em>3. Configuration for CONFIG.INC.PHP</em></strong> 
<blockquote> 
  <p> config.inc.php controls how PHP News Reader works.</p>
  <p> You can find the sample of config.inc.php in the "example/" directory.</p>
  <p> All configuration applies PHP syntax and should be in the form of:</p>
  <blockquote> 
    <p> $CFG["foo"] = "bar";</p>
  </blockquote>
  <p> Each setting is well-documented in the example file.</p>
  <p> The configuration contains 3 sections:</p>
  <blockquote> 
    <p> <a href=#section1>Section 1</a> - Authentication<br />
      <a href=#section2>Section 2</a> - Contents<br />
      <a href=#section3>Section 3</a> - Languages</p>
  </blockquote>
<a name=section1></a>
  <p> <strong>Section 1 - Authetication</strong></p>
  <p> This section controls how you authenticate your users.</p>
<a name=auth_type></a>
  <p><strong>$CFG["auth_type"]</strong></p>
  <blockquote> 
    <p>Specify the authentication type (REQUIRED)</p>
    <p> "required" - authentication is required to access the while 
      system<br />
      "optional" - authentication is only required for posting and forwarding<br />
      "open" - authentication is not needed</p>
  </blockquote>
<a name=auth_prompt></a>
  <p> <strong>$CFG["auth_prompt"]</strong></p>
  <blockquote> 
    <p>Specify the style of authentication prompt (default: <i>"form"</i>) 
      (used only if <a href="#auth_type">$CFG["auth_type"]</a> is not <i>'open'</i>)</p>
    <p> "http" - authenticated user via HTTP login window<br />
      "form" - authenticated user via HTML login form (default and is recommended)<br />
      "cas" - authenticated user via <a href=http://www.yale.edu/tp/auth/ target=_blank>CAS</a> (with version >= 2.3.0)<br />
      "other" - authenticated user via third party system (ex: <a href="#phpbb_auth">phpBB</a>), (with version >= 2.5.6)</p>
  </blockquote>
<a name=post_restriction></a><a name=global_readonly></a>
  <p><strong>$CFG["global_readonly"]</strong></p>
  <blockquote> 
    <p>Prohibits the posting and forwarding of articles, even if user performs login. (default: <i>false</i>)</p>
    <p>This is global setting for all categories,
       if you want only one or two categories to be readonly,
       set it in <a href="#grouplst_option">newsgroups.lst</a></p>
    <p>This setting first appeared in the <a href="history.php#v250">v2.5.0</a> of PHP News Reader</p>
    <p>It is to replace $CFG["post_restriction"] in the previous version.</p>
  </blockquote>
<a name=auth_http_realm></a>
  <p> <strong>$CFG["auth_http_realm"]</strong></p>
  <blockquote> 
    <p>Specify the realm used in http authentication prompt (REQUIRED if <a href="#auth_prompt">$CFG["auth_prompt"]</a> is <i>'http'</i>)</p>
    <p>This realm string will be displayed in the HTTP login window.</p>
  </blockquote>
<a name=auth_method></a>
  <p> <strong>$CFG["auth_method"]</strong></p>
  <blockquote> 
    <p>Specify the authentication method (REQUIRED if <a href="#auth_type">$CFG["auth_type"]</a> != <i>"open"</i> ) </p>
    <p>"<a href=#ldap_auth>ldap</a>" - authenticated via LDAP server,<br />
      "<a href=#pop3_auth>pop3</a>" - authenticated via POP3 server,<br />
      "<a href=#pop3s_auth>pop3s</a>" - authenticated via POP3S (POP3 over SSL) server (with version >= 2.4.1),<br />
      "<a href=#ftp_auth>ftp</a>" - authenticated via FTP server,<br />
      "<a href=#ftps_auth>ftps</a>" - authenticated via FTPS (FTP over SSL) server (with version >= 2.4.1),<br />
      "<a href=#mail_auth>mail</a>" - authenticated via multiple POP3/POP3S server,<br />
      "<a href=#db_auth>mysql</a>" - authenticated via MySQL database,<br />
      "<a href=#db_auth>pgsql</a>" - authenticated via PostgreSQL database,<br />
      "<a href=#nntp_auth>nntp</a>" - authenticated via NNTP News Server (with version >= 2.2.1),<br />
      "<a href=#nntps_auth>nntps</a>" - authenticated via NNTPS (NNTP over SSL) News Server (with version >= 2.4.0),<br />
      "<a href=#cas_auth>cas</a>" - authenticated via <a href=http://www.yale.edu/tp/auth/ target=_blank>CAS</a> (with version >= 2.3.0),<br />
      "<a href=#phpbb_auth>phpbb</a>" - authenticated via <a href=http://www.phpbb.com/ target=_blank>phpBB</a> (with version >= 2.5.6),<br />
      "user" - authenticated via your self-defined method,</p>
  </blockquote>
<p>
<b>Notice for <a href="http://www.yale.edu/tp/auth/" target="_blank">CAS</a> users:</b>
<blockquote>If you use <a href="http://www.yale.edu/tp/auth/" target="_blank">CAS</a> to authenticate your users,<br />
both <a href="#auth_prompt">$CFG["auth_prompt"]</a> and <a href="#auth_method">$CFG["auth_method"]</a> should set to 'cas'.<br />
Besides this, you should first install <a href="http://esup-phpcas.sourceforge.net" target="_blank">phpCAS</a> in your PHP include_path or CAS/ for this method to run well.<br />
You can download phpCAS from the following place:<br />
&nbsp;&nbsp;&nbsp; <a href="http://esup-phpcas.sourceforge.net" target="_blank">http://esup-phpcas.sourceforge.net</a>
</blockquote>
</p>
<a name=auth_organization></a>
  <p><strong>$CFG["auth_organization"]</strong></p>
  <blockquote> 
    <p>Specify the organization of authentication source (REQUIRED if <a href="#auth_type">$CFG["auth_type"]</a> is not <i>'open'</i>)</p>
    <p>This is the organization name of your authentication source.</p>
  </blockquote>
<a name=auth_registration_info></a>
  <p><strong>$CFG["auth_registration_info"]</strong></p>
  <blockquote> 
    <p>Prompt users about how to get an account (default: "")<br />
      This message will be displayed on login (FORM) or logout (HTTP) windows</p>
  </blockquote>
<a name=auth_user_module></a>
  <p> <strong>$CFG["auth_user_module"]</strong></p>
  <blockquote> 
    <p>Specify user-defined authentication module location (REQUIRED if <a href="#auth_method">$CFG["auth_method"]</a> is <i>'user'</i>)<br />
      You should implement a function with this prototype in your module:<br />
      <br />
      function check_user_password( $username, $password )</p>
      <p>This function should verify the correctness of $username and $password,
      <br />and then return something for granting access, <i>null</i> otherwise.</p>
      <p>There's a sample authentication module in <i>auth/sample.inc.php</i>. You can begin the work from here.</p>
  </blockquote>
<a name=auth_deny_users></a>
  <p> <strong>$CFG["auth_deny_users"]</strong></p>
  <blockquote> 
    <p>Specify users (as an array) to be denied from login (defaults: empty)<br />
      <br />
    <p>$CFG["auth_deny_users"] = array( 'guest', 'baduser' );</p>
    <p>This setting first appeared in the <a href="history.php#v262">v2.6.2</a> of PHP News Reader</p>
  </blockquote>
<a name=ldap_auth></a>
  <p> <strong>LDAP authentication parameters</strong> (REQUIRED if <a href="#auth_method">$CFG["auth_method"]</a> is <i>'ldap'</i>)</p>
  <blockquote> 
    <p> <strong>$CFG["ldap_server"]</strong></p>
    <blockquote> 
      <p>LDAP server address ( address:port ), default port is 189</p>
      <p>$CFG["ldap_server"] = "ldap.domain.org:189";
    </blockquote>
    <p> <strong>$CFG["ldap_dn"]</strong></p>
    <blockquote> 
      <p>LDAP distinguish name</p>
      <p>$CFG["ldap_dn"] = "ou=members, o=root";</p>
    </blockquote>
    <p> <strong>$CFG["ldap_bind_rdn"]</strong></p>
    <blockquote> 
      <p>LDAP bind RDN, %u is substituted by username (default: <i>"%u"</i>)</p>
      <p>$CFG["ldap_bind_rdn"] = "cn=%u,ou=members,o=root";</p>
    </blockquote>
    <p> <strong>$CFG["ldap_bind_pwd"]</strong></p>
    <blockquote> 
      <p>LDAP bind password, %p is substituted by password (default: <i>"%p"</i>)</p>
      <p>$CFG["ldap_bind_pwd"] = "%p";</p>
    </blockquote>
    <p> <strong>$CFG["ldap_filter"]</strong></p>
    <blockquote> 
      <p>LDAP search filter (default: <i>"(cn=%u)"</i>)</p>
      <p>$CFG["ldap_filter"]   = "(&(cn=%u)(accountStatus=1)(MailStatus=1))";</p>
    </blockquote>
    <p> <strong>$CFG["ldap_variable"]</strong></p>
    <blockquote> 
      <p>The attributes extract from this LDAP search for later use (default: <i>null</i>)<br />
        ( %u can not be used here )</p>
      <p>$CFG["ldap_variable"] = array( "%e" => "Email", "%n" => "Fullname" );</p>
      <p>In the above setting, the ldap attribure "Email" will be extracted from this search,<br />
         and be putted in variable %e for later use</p>
    </blockquote>
  </blockquote>
<a name=ftp_auth></a>
  <p><br />
    <strong>FTP authentication parameters</strong> (REQUIRED if <a href="#auth_method">$CFG["auth_method"]</a> is <i>'ftp'</i>)</p>
  <blockquote> 
    <p> <strong>$CFG["ftp_server"]</strong></p>
    <blockquote> 
      <p>FTP server address ( address:port ), default port is 21</p>
      <p>$CFG["ftp_server"] = "ftp.domain.org";</p>
    </blockquote>
    <p> <strong>$CFG["ftp_deny"]</strong></p>
    <blockquote> 
      <p> The user list which is denied for FTP authentication<br />
        (default:<em><i><strong> array( 'anonymous', 'guest', 'ftp' )</strong></i></em> 
        )</p>
      <p>$CFG["ftp_deny"] = array( 'anonymous', 'guest', 'ftp', 'root' );</p>
    </blockquote>
  </blockquote>
<a name=ftps_auth></a>
  <p><br />
    <strong>FTPS (FTP over SSL) authentication parameters</strong> (REQUIRED if <a href="#auth_method">$CFG["auth_method"]</a> is <i>'ftps'</i>)</p>
  <blockquote> 
    <p> <strong>$CFG["ftps_server"]</strong></p>
    <blockquote> 
      <p>FTPS server address ( address:port ), default port is 990</p>
      <p>$CFG["ftps_server"] = "ftps.domain.org";</p>
    </blockquote>
    <p> <strong>$CFG["ftps_deny"]</strong></p>
    <blockquote>
      <p> The user list which is denied for FTPS authentication<br />
        (default:<em><i><strong> array( 'anonymous', 'guest', 'ftp' )</strong></i></em> 
        )</p>
      <p>$CFG["ftps_deny"] = array( 'anonymous', 'guest', 'ftp', 'root' );</p>
    </blockquote>
  </blockquote>
<a name=pop3_auth></a>
  <p> <strong>POP3 authentication parameters</strong> (REQUIRED if <a href="#auth_method">$CFG["auth_method"]</a> is <i>'pop3'</i>)</p>
  <blockquote> 
    <p> <strong>$CFG["pop3_server"]</strong></p>
    <blockquote> 
      <p>POP3 server address ( address:port ), default port is 110</p>
      <p>$CFG["pop3_server"] = "pop3.domain.org";</p>
    </blockquote>
    <p>This module first appeared in the <a href="history.php#v241">v2.4.1</a> of PHP News Reader</p>
    <p>You must enable <a href=http://www.php.net/manual/en/ref.openssl.php target=_blank>OpenSSL extension</a> in PHP, and the PHP should be <b>v4.3.0</b> or greater</p>
  </blockquote>
<a name=pop3s_auth></a>
  <p> <strong>POP3S (POP3 over SSL) authentication parameters</strong> (REQUIRED if <a href="#auth_method">$CFG["auth_method"]</a> is <i>'pop3s'</i>)</p>
  <blockquote>
    <p> <strong>$CFG["pop3s_server"]</strong></p>
    <blockquote> 
      <p>POP3S server address ( address:port ), default port is 995</p>
      <p>$CFG["pop3s_server"] = "pop3s.domain.org";</p>
    </blockquote>
    <p>This module first appeared in the <a href="history.php#v241">v2.4.1</a> of PHP News Reader</p>
    <p>You must enable <a href=http://www.php.net/manual/en/ref.openssl.php target=_blank>OpenSSL extension</a> in PHP, and the PHP should be <b>v4.3.0</b> or greater</p>
  </blockquote>
<a name=mail_auth></a>
  <p> <strong>Mail authentication parameters</strong> (REQUIRED if <a href="#auth_method">$CFG["auth_method"]</a> is <i>'mail'</i>)</p>
  <blockquote> 
    <p> <strong>$CFG["pop3_mapping"]</strong></p>
    <blockquote> 
      <p>the mapping from E-Mail to POP3/POP3S server address</p>
      <p>User should login with full E-Mail address, and this module will
         use different POP3 server to authenticated user based on the domain part of supplied E-Mail</p>
      <p>The following example will use "pop3.foobar.com" to authenticate "xxx@foobar.com",
         and use "pop3.domain.org" to authenticate "yyy@mail.domain.org".</p>
      <p>$CFG["pop3_mapping"] = array( "@foobar.com" =&gt; "pop3.foobar.com",<br />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        "@mail.domain.org" =&gt; "pop3.domain.org" );</p>
    <p>Since <a href="history.php#v250">v2.5.0</a>, this module support both <b>pop3</b> and <b>pop3s</b>.<br /> You can assign the server in URI syntax as follows:</p>
      <p>$CFG["pop3_mapping"] = array( "@foobar.com" =&gt; "pop3.foobar.com",<br />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        "@pop3s.domain.org" =&gt; "pop3s://pop3s.domain.org/",
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        ".bbs@pop3s.bbs.org" =&gt; "pop3s://pop3s.bbs.org/",
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        "@pop3.domain.org" =&gt; "pop3://pop3.domain.org:110/" );</p>
    <p>You must enable <a href=http://www.php.net/manual/en/ref.openssl.php target=_blank>OpenSSL extension</a> in PHP, and the PHP should be <b>v4.3.0</b> or greater</p>
    </blockquote>
    <p> <strong>$CFG["domain_select"]</strong></p>
    <blockquote> 
      <p>Show domain-list selector in login dialog (default: <i>true</i>)</p>
      <p>Note that domain-list selector only appear if <a href="#auth_prompt">$CFG["auth_prompt"]</a> is <i>'form'</i></p>
      <p>This setting first appeared in the <a href="history.php#v250">v2.5.0</a> of PHP News Reader</p>
    </blockquote>
  </blockquote>
<a name=nntp_auth></a>
  <p><strong>NNTP authentication parameters</strong> (REQUIRED if <a href="#auth_method">$CFG["auth_method"]</a> is <i>'nntp'</i>)</p>
  <blockquote> 
    <p> <strong>$CFG["auth_nntp_server"]</strong></p>
    <blockquote> 
      <p>News (NNTP) server address and port ( address:port ), default port is 119</p>
      <p>$CFG["auth_nntp_server"] = "news.domain.org";</p>
    </blockquote>
    <p>Note that this option only deal with the authentication of PHP News Reader,
       it has nothing to do with the authentication perform by news server.
       Add <a href=#grouplst_auth><b>auth</b> directive</a> to your newsgroups.lst if your news server requires authentication.</p>
    <p>This setting first appeared in the <a href="history.php#v220">v2.2.0</a> of PHP News Reader</p>
  </blockquote>
<a name=nntps_auth></a>
  <p><strong>NNTPS (NNTP over SSL) authentication parameters</strong> (REQUIRED if <a href="#auth_method">$CFG["auth_method"]</a> is <i>'nntps'</i>)</p>
  <blockquote> 
    <p> <strong>$CFG["auth_nntps_server"]</strong></p>
    <blockquote> 
      <p>News (NNTPS) server address and port ( address:port ), default port is 563</p>
      <p>$CFG["auth_nntps_server"] = "nntps.domain.org";</p>
    </blockquote>
    <p>Note that this option only deal with the authentication of PHP News Reader,
       it has nothing to do with the authentication perform by news server.
       Add <a href=#grouplst_auth><b>auth</b> directive</a> to your newsgroups.lst if your news server requires authentication.</p>
    <p>This module first appeared in the <a href="history.php#v240">v2.4.0</a> of PHP News Reader</p>
    <p>You must enable <a href=http://www.php.net/manual/en/ref.openssl.php target=_blank>OpenSSL extension</a> in PHP, and the PHP should be <b>v4.3.0</b> or greater</p>
  </blockquote>
<a name=cas_auth></a>
  <p><strong>CAS authentication parameters</strong> (REQUIRED if <a href="#auth_method">$CFG["auth_method"]</a> is <i>'cas'</i>)</p>
  <blockquote> 
    <p> <strong>$CFG["auth_cas_server"]</strong></p>
    <blockquote> 
      <p>CAS server address and port ( address:port ), the port part should <b>not</b> be omitted.</p>
      <p>$CFG["auth_cas_server"] = "cas.domain.org:1234";</p>
    </blockquote>
    <p> <strong>$CFG["auth_cas_base_uri"]</strong></p>
    <blockquote> 
      <p>CAS Server Base URI</p>
      <p>$CFG["auth_cas_base_uri"] = "cas";</p>
    </blockquote>
    <p> <strong>$CFG["auth_cas_debug"]</strong></p>
    <blockquote> 
      <p>The filename for outputing debug log, or <i>false</i> to turn off debug mode (default: <i>false</i>)</p>
      <p>$CFG["auth_cas_debug"] = "/tmp/phpcas.log";</p>
    </blockquote>
    <p>The above settings first appeared in the <a href="history.php#v230">v2.3.0</a> of PHP News Reader</p>
  </blockquote>
<a name=phpbb_auth></a>
  <p><strong>phpBB authentication module paramenters</strong> (REQUIRED if <a href="#auth_method">$CFG["auth_method"]</a> is <i>'phpbb'</i>)</p></p>
  <blockquote>
    <p><strong>$CFG["auth_phpbb_url_base"]</strong></p>
    <blockquote>
      <p>The base URL of your phpBB installation</p>
      <p>$CFG["auth_phpbb_url_base"] = "http://phpbb.foobar.com/";</p>
    </blockquote>
    <p><strong>$CFG["auth_phpbb_path"]</strong></p>
    <blockquote>
      <p>The relative path of absolute path of your phpBB installation</p>
      <p>$CFG["auth_phpbb_path"] = "../phpbb/";</p>
    </blockquote>
    <p>With 'phpbb' as authentication module, you can utilize the session if you already login phpBB.</p>
    <p>Notice: Your <a href="#auth_prompt">$CFG["auth_prompt"]</a> must be <i>'other'</i> if you want to use phpBB authtication module.
    <p>The above settings first appeared in the <a href="history.php#v256">v2.5.6</a> of PHP News Reader</p>
  </blockquote>
<a name=db_auth></a>
  <p><strong>MySQL/PostgreSQL Database authentication parameters</strong></p>
  <blockquote>
    <p>(REQUIRED if <a href="#auth_method">$CFG["auth_method"]</a> is <i>'mysql'</i> or <i>'pgsql'</i> )</p>
    <p>These parameters are used in both MySQL and PostgreSQL database authentication module.</p>
    <p><strong>$CFG["db_server"]</strong></p>
    <blockquote> 
      <p>The database server address (address:port)</p>
    </blockquote>
    <p> <strong>$CFG["db_name"]</strong></p>
    <blockquote> 
      <p>The database name</p>
    </blockquote>
    <p> <strong>$CFG["db_username"]</strong></p>
    <blockquote> 
      <p>The username used to connect database</p>
    </blockquote>
    <p> <strong>$CFG["db_password"]</strong></p>
    <blockquote> 
      <p>The password used to connect database</p>
    </blockquote>
    <p> <strong>$CFG["db_table"]</strong></p>
    <blockquote> 
      <p>The table name for user information</p>
    </blockquote>
    <p> <strong>$CFG["db_field_username"]</strong></p>
    <blockquote> 
      <p>The field name for username</p>
    </blockquote>
    <p> <strong>$CFG["db_field_password"]</strong></p>
    <blockquote> 
      <p>The field name for password</p>
    </blockquote>
    <p> <strong>$CFG["db_password_crypt"]</strong></p>
    <blockquote> 
      <p>The password hashing method (default: <i>false</i> - cleartext)<br />
        current supported hashing are: <i>"md5"</i>, <i>"crypt"</i>, or <i>false</i></p>
      <p>If your password does not saved as cleartext in the database,
      <br />this parameter defined the hashing method used to hash password.</p>
    </blockquote>
    <a name=phpbb></a>
    <p>The support for database authentication module make it easy to integrate with many PHP products.</p>
    <p>For example, to enable PHP News Reader authenticating with the existing 
      users of your <a href=http://www.phpbb.com/ target=_blank>phpBB</a> 2.0, use the following settings:</p>
    <blockquote> 
      <p>$CFG["db_server"] = "database.domain.org";<br />
        $CFG["db_name"] = "phpbb";<br />
        $CFG["db_username"] = "db_user";<br />
        $CFG["db_password"] = "db_pass";<br />
        $CFG["db_table"] = "phpbb_users";<br />
        $CFG["db_field_username"] = "username";<br />
        $CFG["db_field_password"] = "user_password";<br />
        $CFG["db_password_crypt"] = "md5";<br />
        $CFG["db_variable"] = array( "%e" =&gt; "user_email" 
        );</p>
      <p>Since <a href="history.php#v256">v2.5.6</a>, you can use 'phpbb' as <a href=#phpbb_auth>$CFG["auth_method"]</a> for seamless integration with sessions of phpBB.
      With 'phpbb' as authentication module, you can utilize the session if you already login phpBB.</p>
    </blockquote>
  </blockquote>
<a name=auth_expire_time></a>
  <p><strong>$CFG["auth_expire_time"]</strong></p>
  <blockquote> 
    <p>After this time in seconds, authentication is expired<br />
      And login again is required. Zero means never expire (default: <i>3600</i> seconds)</p>
  </blockquote>
<a name=auth_user_fullname></a>
  <p><strong>$CFG["auth_user_fullname]</strong></p>
  <blockquote> 
    <p>The full name of authenticated user (default: <i>"%u"</i>)</p>
    <p>The default is the username supplied for authentication</p>
    <p>You can customize this based on the variable defined in the [db_variable] or [ldap_variable]</p>
    <p>For example,</p>
    <p>$CFG["auth_user_fullname"] = "%n";</p>
  </blockquote>
<a name=auth_user_email></a>
  <p> <strong>$CFG["auth_user_email"]</strong></p>
  <blockquote> 
    <p>The E-Mail of authenticated user (REQUIRED if <a href="#auth_method">$CFG["auth_method"]</a> is <b>not</b> <i>'mail'</i>)<br />
      <br />
      The %u will be substituted by the username enter by authentication session<br />
      <br />
      Other variables are defined in the [db_variable] or [ldap_variable]<br />
      <br />
      Note: if you use <i>'mail'</i> as your <b>auth_method</b>, 
      $CFG["auth_user_email"] will always been set to user's E-Mail.
  </blockquote>
<a name=log></a>
  <p><strong>$CFG["log"]</strong></p>
  <blockquote> 
    <p>Enable access log (default: <i>false</i> - no log)</p>
    <p>$CFG["log"] = "/var/log/pnews.log";</p>
    <p>You need to create this file with write permission granted to the user running httpd</p>
  </blockquote>
<a name=log_level></a>
  <p><strong>$CFG["log_level"]</strong></p>
  <blockquote> 
    <p>Log verbose level (default: <i>3</i>)</p>
	<blockquote>
	0 - no log<br />
	1 - log only post/reply/xpost/forward/delete actions.<br />
	2 - log all actions for authenticated users.<br />
	3 - log all actions for all users.
	</blockquote>
    <p>$CFG["log_level"] = 2;</p>
    <p>This setting first appeared in the <a href="history.php#v250">v2.5.0</a> of PHP News Reader</p>
  </blockquote>
<a name=debug_level></a>
  <p><strong>$CFG["debug_level"]</strong></p>
  <blockquote>
    <p>NNTP debug verbose level (default: <i>0</i>)</p>
	<blockquote>
        0 - Turn off NNTP debug information<br />
        1 - NNTP debug information will be embeded in HTML comments<br />
        2 - NNTP debug information will be shown inline with HTML page
	</blockquote>
    <p>$CFG["debug_level"] = 1;</p>
    <p>This setting first appeared in the <a href="history.php#v252">v2.5.2</a> of PHP News Reader</p>
  </blockquote>
  <p>&nbsp;</p>
<a name=section2></a>
<div class=hr></div>
  <p><strong>Section 2 - Contents</strong></p>
  <p>This section configure the contents appeared in this reader<br />
  </p>
<a name=url_base></a>
  <p> <strong>$CFG["url_base"]</strong></p>
  <blockquote> 
    <p>Specify the base URL of your PHP News Reader installation (REQUIRED)</p>
    <p>Prior to <a href="history.php#v221">v2.2.1</a>, this setting is only REQUIRED if <a href="#url_rewrite">$CFG["url_rewrite"]</a> is <i>true</i></p>
    <p>After <a href="history.php#v222">v2.2.2</a>, this setting is always REQUIRED</p>
    <p>This setting first appeared in the <a href="history.php#v220">v2.2.0</a> of PHP News Reader</p>
  </blockquote>
<a name=url_rewrite></a>
  <p> <strong>$CFG["url_rewrite"]</strong></p>
  <blockquote> 
    <p>Enable or disable the URL rewrite function (default: <i>false</i>)</p>
    <p>Read more about <a href=url_rewrite.php>URL rewriting</a> by <a href=url_rewrite.php>clicking here</a>.
    <p>You should enable Apache <b>mod_rewrite</b> module and <b>AllowOverride</b> for per-directory access control<br />
    <p>And the directive <b>AccessFileName</b> must be <b>.htaccess</b>,<br />
    otherwise you should change it, or rename <b>.htaccess</b> to match your setting.</p>
    For more information about <b>mod_rewrite</b>, visit <a href="http://httpd.apache.org/docs/mod/mod_rewrite.html" target=_blank>http://httpd.apache.org/docs/mod/mod_rewrite.html</a> for details.</p>
    <p>This setting first appeared in the <a href="history.php#v220">v2.2.0</a> of PHP News Reader</p>
  </blockquote>
<a name=https_login></a>
  <p> <strong>$CFG["https_login"]</strong></p>
  <blockquote> 
    <p>Whether to use SSL(HTTPS) after authentication (default: <i>false</i>)</p>
    <p>This setting first appeared in the <a href="history.php#v222">v2.2.2</a> of PHP News Reader</p>
  </blockquote>
<a name=style_sheet></a>
  <p><strong>$CFG["style_sheet"]</strong></p>
  <blockquote>
    <p>Setting the style sheet used for all html (default: <i>"standard.css"</i>)</p>
    <p>This setting first appeared in the <a href="history.php#v221">v2.2.1</a> of PHP News Reader</p>
    <p>After <a href="history.php#v224">v2.2.4</a>, the configured style sheet should be placed in the css/ subdirectory.</p>
  </blockquote>
<a name=title></a>
  <p> <strong>$CFG["title"]</strong></p>
  <blockquote> 
    <p>The title for this webnews, no HTML tag is allowed here. (default: <i>"Webnews"</i>)</p>
  </blockquote>
<a name=banner></a>
  <p><strong>$CFG["banner"]</strong></p>
  <blockquote> 
    <p>The banner text or images, HTML tags are allowed here. (default: <i>false</i>)</p>
    <p>$CFG["banner"] = "&lt;img src=banner.jpg&gt;";
  </blockquote>
<a name=html_header></a>
  <p><strong>$CFG["html_header"]</strong></p>
  <blockquote>
    <p>The file to be included as page header (default: <i>false</i>)</p>
    <p>$CFG["html_header"] = "header.htm";
    <p>If $CFG["html_header"] is a valid file which can be included,<br />
       then the $CFG["banner"] setting is ignored!</p>
    <p>This setting first appeared in the <a href="history.php#v257">v2.5.7</a> of PHP News Reader</p>
    <p>$CFG['html_header'] can be a PHP script since <a href="history.php#v261">v2.6.1</a> of PHP News Reader</p>
  </blockquote>
<a name=html_footer></a>
  <p><strong>$CFG["html_footer"]</strong></p>
  <blockquote>
    <p>The file to be included as page footer (default: <i>false</i>)</p>
    <p>$CFG["html_footer"] = "footer.htm";
    <p>This setting first appeared in the <a href="history.php#v257">v2.5.7</a> of PHP News Reader</p>
    <p>$CFG['html_footer'] can be a PHP script since <a href="history.php#v261">v2.6.1</a> of PHP News Reader</p>
  </blockquote>
<a name=group_list></a>
  <p><strong>$CFG["group_list"]</strong></p>
  <blockquote> 
    <p>The group definition that show on this Web News<br />
      <br />
      You should get a copy from examples/newsgroups.lst (default: <i>"newsgroups.lst"</i>)</p>
  </blockquote>
<a name=referrer_enforcement></a>
  <p><strong>$CFG["referrer_enforcement"]</strong></p>
  <blockquote>
    <p>This option will enforce the browsing to begin from (or redirect to) $CFG['url_base'] (default: <i>false</i>)</p>
    <p>This option is also useful to deny the robot access from search engine.</p>
    <p>$CFG["referrer_enforcement"] = true;
    <p>This setting first appeared in the <a href="history.php#v265">v2.6.5</a> of PHP News Reader</p>
  </blockquote>
<a name=article_numbering_reverse></a>
  <p><strong>$CFG["article_numbering_reverse"]</strong></p>
  <blockquote>
    <p>Since <a href="history.php#v265">v2.6.5</a>, the numbering of article in article-list page is now ascendent.</p>
    <p>If you preferred the old-fashioned numbering order, please set this option to <i>true</i> (default: <i>false</i>)</p>
    <p>This option only change the numbering, the article order is not affected</p>
    <p>$CFG["article_numbering_reverse"] = true;
    <p>This setting first appeared in the <a href="history.php#v265">v2.6.5</a> of PHP News Reader</p>
  </blockquote>
<a name=confirm_post></a>
  <p><strong>$CFG["confirm_post"]</strong></p>
  <blockquote>
    <p>The message prompted to confirm the posting (post/reply/crosspost) (default: <i>false</i>)</p>
    <p>$CFG["confirm_post"] = "Really post the message ?";
    <p>This setting first appeared in the <a href="history.php#v260">v2.6.0</a> of PHP News Reader</p>
  </blockquote>
<a name=confirm_forward></a>
  <p><strong>$CFG["confirm_forward"]</strong></p>
  <blockquote>
    <p>The message prompted to confirm the forwarding (default: <i>false</i>)</p>
    <p>$CFG["confirm_forward"] = "Really forward the message ?";
    <p>This setting first appeared in the <a href="history.php#v260">v2.6.0</a> of PHP News Reader</p>
  </blockquote>
<a name=magic_tag></a>
  <p><strong>$CFG["magic_tag"]</strong></p>
  <blockquote> 
    <p>Use magic tag to indicate the visit state of newsgroup (default: <i>false</i>)</p>
      While magic_tag is <i>true</i>, the url for each newsgroup will become:
    <blockquote>http://webnews.host/indexing.php?server=*&group=news.test<font color=red>&magic=23479</font></blockquote>
      or while <a href="#url_rewrite">$CFG["url_rewrite"]</a> is <i>true</i>,
    <blockquote>http://webnews.host/group//news.help<font color=red>?23479</font></blockquote>
      PHP News Reader use the above magic number in <font color=red>red</font> to indicate that the group has been visited by user (with the help of browser's history).<br /><br />
      The default is <i>false</i>. If you do like this feature, you can turn it on by:
    <blockquote>$CFG["magic_tag"] = <i>true</i>;</blockquote>
    <p>This setting first appeared in the <a href="history.php#v224">v2.2.4</a> of PHP News Reader</p>
    <p>The default value of $CFG["magic_tag"] has been changed to <i>false</i> since <a href="history.php#v241">v2.4.1</a> of PHP News Reader</p>
  </blockquote>
<a name=cache_dir></a>
  <p><strong>$CFG["cache_dir"]</strong></p>
  <blockquote>
    <p>Enable cache mechanism of indexing data (default: <i>false</i>)</p>
    <p>Turning on the cache mechanism will relief the loading of news server,
       and speed up the indexing process for large groups.<p>
    <p>To enable caching, set this to the directory of cache data.<br>
       You should grant write permission on this directory to the user running PHP.<p>
    <p>This function is used to cache indexing data (article number list) for each group,
       it does not cache the contents of articles.
    <p>Since <a href="history.php#v261">v2.6.1</a>, attachment is also cached if you enable $CFG["cache_dir"].
    <p>This setting first appeared in the <a href="history.php#v240">v2.4.0</a> of PHP News Reader</p>
  </blockquote>
<a name=thread_enable></a>
  <p><strong>$CFG["thread_enable"]</strong></p>
  <blockquote>
    <p>Enable threading (default: <i>false</i>)</p>
    <p>We only support the simplest type of threading now.<br />
       Articles in the same thread will be <u>listed</u> in the bottum of the page
       when you view one of the message in the thread.<p>
    <p>To enable threading, you must enable the cache mechanism by setting
       <a href=#cache_dir>$CFG["cache_dir"]</a> and specify the correct <a href=#thread_db_format>$CFG["thread_db_format"]</a>.
       The directroy specified by <a href=#cache_dir>$CFG["cache_dir"]</a> will be used for storing threading data.<p>
    <p>You must install the PHP <a href=http://www.php.net/manual/en/ref.dba.php target=_blank>DBA extension</a> in PHP to use this feature.<p>
    <p>If you enable threading, you may need to run 'clear-cache.php' regularly.<br /><br />
       This is because that PHP News Reader store information of all articles in cache and thread database.
       But, PHP News Reader does not clear the expired articles from these database,
       which may cause these database too large after a long time.
       So we suggest running 'clear-cache.php' script once a week or once a month, for example:
       <blockquote>
       # php clear-cache.php /usr/local/apache/htdocs/pnews/config.inc.php<br />
       </blockquote>
       Please specify the location of config.inc.php as the first command line argument.<br />
       And the process must have the permission to delete files under <a href=#cache_dir>$CFG["cache_dir"]</a>.<br /><br />
       You may want to put this script in your crontab for convenience.
    <p>This setting first appeared in the <a href="history.php#v260">v2.6.0</a> of PHP News Reader</p>
  </blockquote>
<a name=thread_db_format></a>
  <p><strong>$CFG["thread_db_format"]</strong></p>
  <blockquote>
    <p>Setting the dba handler used for storing threading data</p>
    <p>You must install the PHP <a href=http://www.php.net/manual/en/ref.dba.php target=_blank>DBA extension</a> in PHP to use this feature.<p>
    <p>The handler, depends on your DBA extension,
       may be <i>dbm</i>, <i>ndbm</i>, <i>gdbm</i>, <i>db2</i>, <i>db3</i> or <i>db4</i>.<br />
       Make sure that you already enable the handler in your DBA extension.<p>
    <p>The default value for $CFG["thread_db_format"] is :<br />
       <blockquote>
       '<i>db3</i>' &nbsp;if&nbsp; <b>PHP &lt; 4.3.2</b>,<br />
       '<i>db4</i>' &nbsp;if&nbsp; <b>PHP &gt;= 4.3.2</b> .
       </blockquote>
    <p>This setting first appeared in the <a href="history.php#v260">v2.6.0</a> of PHP News Reader</p>
  </blockquote>
<a name=image_inline></a>
  <p><strong>$CFG["image_inline"]</strong></p>
  <blockquote>
    <p>The uuencoded image attachment will be shown inline along with article (default: <i>true</i>)</p>
    <p>This setting first appeared in the <a href="history.php#v251">v2.5.1</a> of PHP News Reader</p>
  </blockquote>
<a name=allow_attach_file></a>
  <p><strong>$CFG["allow_attach_file"]</strong></p>
  <blockquote>
    <p>Setting the allowed attachment(s) when posting article (default: <i>2</i>)</p>
    <p>This setting first appeared in the <a href="history.php#v250">v2.5.0</a> of PHP News Reader</p>
  </blockquote>
<a name=group_sorting></a>
  <p><strong>$CFG["group_sorting"]</strong></p>
  <blockquote> 
    <p>sort newsgroups in each category (default: <i>false</i>)</p>
  </blockquote>
<a name=show_group_description></a>
  <p><strong>$CFG["show_group_description"]</strong></p>
  <blockquote> 
    <p>Show newsgroups description or not (default: <i>true</i>)</p>
  </blockquote>
<a name=hide_email></a>
  <p><strong>$CFG["hide_email"]</strong></p>
  <blockquote> 
    <p>Enable the Anti-Spam feature, the E-Mail will be encoded by JavaScript (default: <i>true</i>)</p>
    <p>For example, the E-Mail address "dada@pnews.com.tw" will be encoded as:</p>
    <blockquote><i>
    &lt;script type="text/javascript"&gt;<br />
    &nbsp;&nbsp;&nbsp;&nbsp;document.write( "dada" + "&amp;#64;" + "pnews&amp;#46;com&amp;#46;tw" );<br />
    &lt;/script&gt;</i>
    </blockquote>
    <p>This makes it difficult for Spamlist collector to automatically obtain email addresses from PHP News Reader</p>
    <p>This setting first appeared in the <a href="history.php#v251">v2.5.1</a> of PHP News Reader</p>
  </blockquote>
<a name=email_editing></a>
  <p><strong>$CFG["email_editing"]</strong></p>
  <blockquote> 
    <p>Allow editing of E-Mail address when posting article (default: <i>true</i>)</p>
    <p>This setting first appeared in the <a href="history.php#v230">v2.3.0</a> of PHP News Reader</p>
  </blockquote>
<a name=articles_per_page></a>
  <p><strong>$CFG["articles_per_page"]</strong></p>
  <blockquote> 
    <p>Setting the number of articles shown per page (default: <i>20</i>)</p>
    <p>This setting first appeared in the <a href="history.php#v220">v2.2.0</a> of PHP News Reader</p>
  </blockquote>
<a name=show_latest_top></a>
<a name=show_newest_top></a>
<a name=article_order_reverse></a>
  <p><strong>$CFG["show_latest_top"]</strong></p>
  <blockquote> 
    <p>Show the latest article as the top item (default: <i>true</i>)</p>
    <p>This setting first appeared in the <a href="history.php#v257">v2.5.7</a> of PHP News Reader<br />
    It is to replace $CFG["article_order_reverse"] in <a href="history.php#v220">v2.2.0</a> - <a href="history.php#v231">v2.3.1</a> and $CFG["show_newest_top"] in <a href="history.php#v240">v2.4.0</a> - <a href="history.php#v256">v2.5.6</a><br />
    <p>This setting is <b>deprecated</b> since <a href="history.php#v260">v2.6.0</a> of PHP News Reader.</p>
  </blockquote>
<a name=show_article_popup></a>
  <p><strong>$CFG["show_article_popup"]</strong></p>
  <blockquote> 
    <p>Controlling the article to show in popup window or not (default: <i>false</i>)</p>
    <p>This setting first appeared in the <a href="history.php#v220">v2.2.0</a> of PHP News Reader</p>
    <p>This setting is <b>deprecated</b> since <a href="history.php#v261">v2.6.1</a> of PHP News Reader.</p>
  </blockquote>
<a name=filter_ansi_color></a>
  <p><strong>$CFG["filter_ansi_color"]</strong></p>
  <blockquote> 
    <p>Setting this option to <i>true</i> will cause filtering of ANSI coloring code from article (default: <i>true</i>)</p>
    <p>This setting first appeared in the <a href="history.php#v220">v2.2.0</a> of PHP News Reader</p>
  </blockquote>
<a name=organization></a>
  <p> <strong>$CFG["organization"]</strong></p>
  <blockquote> 
    <p>The organization name of this site (default: <i>"News Server"</i>)</p>
  </blockquote>
<a name=post_signature></a>
  <p> <strong>$CFG["post_signature"]</strong></p>
  <blockquote> 
    <p>The signature which been appended at each posted article (default: <i>""</i>)</p>
    <p>Note: This is NOT a per-user setting!</p>
  </blockquote>
<a name=meta_description></a>
  <p> <strong>$CFG["meta_description"]</strong></p>
  <blockquote> 
    <p>The META description embeded in HTML header (default: <i>"PHP News Server"</i>)</p>
    <p>This setting first appeared in the <a href="history.php#v253">v2.5.3</a> of PHP News Reader</p>
  </blockquote>
<a name=meta_keywords></a>
  <p> <strong>$CFG["meta_keywords"]</strong></p>
  <blockquote> 
    <p>The META keywords embeded in HTML header (default: <i>"news,pnews,webnews,nntp"</i>)</p>
    <p>This setting first appeared in the <a href="history.php#v253">v2.5.3</a> of PHP News Reader</p>
  </blockquote>
<a name=show_sourceforge_logo></a>
  <p> <strong>$CFG["show_sourceforge_logo"]</strong></p>
  <blockquote> 
    <p>Show sourceforge logo (default: <i>false</i>)</p>
    <p>This setting is <b>deprecated</b> since <a href="history.php#v265">v2.6.5</a> of PHP News Reader</p>
  </blockquote>
<a name=advertise></a>
<a name=advertise_group_list></a>
<strong>$CFG["advertise_group_list"]</strong>
  <blockquote> 
  <p>Place advertisement in group-list page. (default: "")</p>
  </blockquote>
<a name=advertise_banner></a>
<strong>$CFG["advertise_banner"]</strong>
  <blockquote> 
  <p>Place advertisement in the banner for each article-list and article page. (default: "")</p>
  </blockquote>
<a name=advertise_article></a>
<strong>$CFG["advertise_article"]</strong>
  <blockquote> 
  <p>Place advertisement in the content for each article page. (default: "")</p>
  </blockquote>
<a name=language_switch></a>
  <p> <strong>$CFG["language_switch"]</strong></p>
  <blockquote> 
    <p>Show language switch or not (default: <i>true</i>)</p>
  </blockquote>
<a name=time_format></a>
  <p> <strong>$CFG["time_format"]</strong></p>
  <blockquote> 
    <p>The format used to displaying time (default: <i>"%Y/%m/%d %H:%M:%S"</i>)</p>
    <p>$CFG["time_format"] = "%Y/%m/%d %H:%M:%S";</p>
    <p>The conversion specifiers used in the format string is the same as PHP strftime().<br />
    You can refer <a href="http://www.php.net/manual/en/function.strftime.php" target=_blank>http://www.php.net/manual/en/function.strftime.php</a> for details.
  </blockquote>
<a name=links></a>
  <p><strong>$CFG["links"]</strong></p>
  <blockquote> 
    <p>The links referring to other pages. (default: <i>null</i>)<br />
    <p>This is an associate array, the key is the link text and the value is the url.<p>
      <br />
      $CFG["links"] = array( "Back Home" =&gt; "../index.php", 
      "Tech News" =&gt; "http://foo.bar/technews/" );</p>
  </blockquote>
<a name=section3></a>
<div class=hr></div>
  <p><strong><br />
    Section 3 - Languages</strong></p>
  <p> This section controls the setting about languages and charsets</p>
  <p>Natively supported languages and the charsets are:</p>
  <blockquote>
    <p>
	<table border=1 cellspacing=0 cellpadding=2>
	<tr><td width=150>Language</td><td width=80>Locale</td><td width=150>Charset</td></tr>
	<tr><td>English</td><td>en</td><td>US-ASCII</td></tr>
 	<tr><td>Tranditional Chinese</td><td>zh-tw</td><td>BIG5</td></tr>
	<tr><td>Simplified Chinese</td><td>zh-cn</td><td>GB2312</td></tr>
 	<tr><td>Unicode</td><td>Unicode</td><td>UTF-8 (default)</td></tr>
        <tr><td>Fran&ccedil;ais</td><td>fr</td><td>iso-8859-1</td></tr>
        <tr><td>Finnish</td><td>fi</td><td>iso-8859-1</td></tr>
        <tr><td>German</td><td>de</td><td>iso-8859-1</td></tr>
        <tr><td>Italiano</td><td>it</td><td>iso-8859-1</td></tr>
	<tr><td>Slovak</td><td>sk</td><td>iso-8859-2</td></tr>
	</table>
  </blockquote>
<a name=interface_language></a>
  <p> <strong>$CFG["interface_language"]</strong></p>
  <blockquote>
    <p>The language setting of interface ( "en", "zh-tw", "zh-cn", "unicode", "fr", "fi", "de", "it", "sk" )<br />
     (default: <i>"en"</i> )</p>
    <p>This setting first appeared in the <a href="history.php#v230">v2.3.0</a> of PHP News Reader<br />
       If not set in <a href="history.php#v230">v2.3.0</a> or later, the default language used in interface is "en" - English</p>
    <p>Notice: the original $CFG["charset"]["interface"] is deprecated since <a href="history.php#v230">v2.3.0</a>,<br />please use $CFG["interface_language"] instead</p>
  </blockquote>
<a name=charset_config></a>
  <p> <strong>$CFG["charset"]["config"]</strong></p>
  <blockquote> 
    <p>The charset setting used in this config.inc.php</p>
  </blockquote>
<a name=charset_grouplst></a>
  <p> <strong>$CFG["charset"]["grouplst"]</strong></p>
  <blockquote> 
    <p>The charset setting used in newsgroups.lst</p>
  </blockquote>
<a name=charset_database></a>
  <p> <strong>$CFG["charset"]["database"]</strong></p>
  <blockquote> 
    <p>The charset setting used in database or LDAP.<br />
       If you do not enable database or ldap authentication module, this setting will be ignored</p>
  </blockquote>
  <p>Notice: the original $CFG["language"] section is deprecated since <a href="history.php#v210">v2.1.0</a>,<br />please use $CFG["charset"] section instead</p>
</blockquote>
<a name=newsgroups_lst></a>
<div class=hr></div>
<strong><em>4. Configuration for NEWSGROUPS.LST</em></strong> 
<blockquote> 
  <p>newsgroups.lst list the news server / news groups to access</p>
  <p>The syntax of this file is different from that of config.inc.php</p>
  <p>All lines begin with the # is considered as comments</p>
  <p>The newsgroups of PHP News Reader can be grouped by several categories.</p>
  <p>Each category is identified by a single line like this:</p>
  <blockquote> 
    <p><strong>[Computer Science]</strong></p>
  </blockquote>
  <p>This defined a category named as "Computer Science"</p>
  <p>The newsgroups defined in the same category should be pulled from the same news 
    server, and should be with the same charset. Also note that at least one 
    category should be defined in newsgroups.lst.</p>
  <p>The setting consists of multiple directives where each directive is a pair of key and value,
    which separated by tabs or spaces. The value part ends at end of line</p>
  <p>For example, the following line sets directive "foobar" as "value1 value2"</p>
  <blockquote> 
    <p>foobar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;value1 value2</p>
  </blockquote>
  Valid directives recognized by PHP News Reader are:<br />
  <a name=grouplst_server></a>
  <p><strong>'server' directive</strong></p>
  <blockquote>
  This directive defines the news server used to retrieve newsgroups in this category.
  <p>
  Since <a href="history.php#v255">v2.5.5</a> of PHP News Reader, the address of the news server can be configured with port number.
  <p>
  For example: <i>news.pnews-test.com:12345</i>
  </blockquote>
  <a name=group_match></a>
  <p><strong>'group' directive</strong></p>
  <blockquote>
  This directive defines the groups included in each category.<br />
  Multiple groups can be separated by a comma ","<br />
  Groups can be specified in three kind of syntax:
  <ol>
    <li>Full group name for inclusion, i.e: <b>tw.bbs.comp.hardware</b><br /><br />
    <li>Pattern match for sub-class groups inclusion, i.e: <b>tw.bbs.comp.*</b><br /><br />
        Only the last class of group name can be used in pattern match<br />
        &nbsp;&nbsp;&nbsp;For example, <b>tw.bbs.*.hardware</b> is INVALID.<br /><br />
        Before <a href="history.php#v224">v2.2.4</a>, only 'all-match' patttern (a single <b>*</b> ) is allowed in the last class pattern of group name<br />
        &nbsp;&nbsp;&nbsp;For example, <b>tw.bbs.comp.hard*</b> is INVALID in <a href="history.php#v224">v2.2.4</a>, while <b>tw.bbs.comp.*</b> is VALID.<br /><br />
        After <a href="history.php#v230">v2.3.0</a>, the last class pattern is not restricted to a single <b>*</b>,<br />
        &nbsp;&nbsp;&nbsp;For example, <b>tw.bbs.comp.hard*</b> is now VALID in <a href="history.php#v230">v2.3.0</a> or later.<br /><br />
    <li>Full group name for exclusion, i.e: <b>!tw.bbs.comp.virus</b><br />
        Only full group name is allowed here, it is used to nagative the previously included group.<br /></br />
    <li>Since <a href="history.php#v260">v2.6.0</a> of PHP News Reader, multiple 'group' directives can be specified in one single category.
  </ol>
  </blockquote>
  <a name=grouplst_option></a>
  <p><strong>'option' directive</strong></p>
  <blockquote>
  <p>In each section, an optional "option" directive can be defined.</p>
  <p>The following values are now recognized:</p>
  <p>default</p>
  <blockquote> 
    <p>This category will become the default category when user first come in.</p>
    <p>Only one category can be marked as "default".<br />If multiple categories are marked as "default", the last category will become the default.</p>
  </blockquote>
  <p>private</p>
  <blockquote> 
    <p>The reading access for this category is restricted by authentication,<br />
      only used when the $CFG["auth_type"] is "optional"</p>
  </blockquote>
  <p>nntps</p>
  <blockquote> 
    <p>With this option, the connection to news server will be NNTP over SSL,
       also known as NNTPS. This option first appeared in <a href="history.php#v240">v2.4.0</a>, and require
       PHP with <b>v4.3.0</b> or greater and being compiled with OpenSSL extension.</p>
  </blockquote>
  <p>readonly</p>
  <blockquote> 
    <p>This category is <b>readonly</b> even if user performs a login.</p>
    <p>This option first appeared in <a href="history.php#v250">v2.5.0</a> of PHP News Reader.</p>
  </blockquote>
  <p>hidden</p>
  <blockquote> 
    <p>This category is <b>hidden</b> from index page.
    This category can still be accessed as normal category,
    if you know the corresponding category number.</p>
    <p>This option first appeared in <a href="history.php#v252">v2.5.2</a> of PHP News Reader.</p>
  </blockquote>
  <p>Multiple options can be separated by comma, for example:</p>
  <blockquote> 
    <p><strong>option&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;default,private</strong></p>
  </blockquote>
  </blockquote>

  <a name=grouplst_auth></a>
  <p><strong>'auth' directive</strong></p>
  <blockquote>
  <p>The access to the news server of this category require the authentication info
  (username/password) to be specified.</p>
  <p>The username/password are separated by comma, ",", for example:</p>
  <blockquote>
    <p><strong>auth&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;myname,mypasswd</strong></p>
  </blockquote>
  Since PHP News Reader <a href="history.php#v252">v2.5.2</a>, if you use '<i>http</i>' as <a href=#auth_prompt>$CFG['auth_prompt']</a>,
  you can use the username and password in the http authentication as the authentication info requested by Web server. For example:</p>
  <blockquote>
    <p><strong>auth&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%http_user,%http_pw</strong></p>
  </blockquote>
  <p>
  The %http_user and %http_pw will be replaced by the username/password provided in http authentication.
  This replacement does not work if you use '<i>form</i>' as <a href=#auth_prompt>$CFG['auth_prompt']</a>.
  This is because that the password is not available in session data for the security reason.</p>
  The replacement of %http_* is originally coded by Jochen Staerk.
  </blockquote>

  <a name=grouplst_charset></a>
  <p><strong>'charset' directive</strong></p>
  <blockquote>
  <p>The charset setting for this category. All newsgroups in this category should be with the same charset.</p>
  <p>Notice: the original 'lang' setting is deprecated since <a href="history.php#v210">v2.1.0</a>, please use 'charset' instead.</p>
  </blockquote>
<div class=hr></div>
  <b>An example for <i>newsgroups.lst</i>:</b>
  <p>The setting before any categories are <b>global</b> settings. Two global settings 
    are valid now: <b>charset</b> and <b>server</b></p>
  <blockquote> 
    <p><strong># default charset for all categories<br />
      charset&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;big5</strong></p>
    <p><strong># default news server for all categories<br />
      server&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;news1.domain.org</strong></p>
  </blockquote>
  <p>After these global settings, each categories are defined. For example, the 
    following settings defined three categories,</p>
  <blockquote> 
    <p><strong>[X1]<br />
      server&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;news1.domain.org<br />
      group&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;pnews.*,pnews.comp.network<br />
      option&nbsp;&nbsp;&nbsp;&nbsp;default</strong></p>
    <p><strong>[X2]<br />
      # use the default news server<br />
      group&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;pnews.test,pnews.talk.*<br />
      auth&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;none</strong></p>
    <p><strong>[X3]<br />
      server&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;news2.domain.org<br />
      group&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;pnews.comp.*,pnews.rec.*<br />
      auth&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;username,password<br />
      charset&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;gb2312</strong></p>
  </blockquote>
  <p>The first category "X1" contains all newsgroups matching "pnews.*" 
    or "pnews.comp.network" from the news server "news1.domain.org", 
    and the default charset is used in these groups. By the way, this category does not
    require authentication to the news server (by default)</p>
  <p>The second category "X2" contains all newsgroups matching 
    "pnews.test" or "pnews.talk.*" from the default news server 
    defined in the above global settings, and the default charset is used in 
    these groups.  By the way, this category does not require authentication to the news server</p>
  <p>The third category "X3" contains all newsgroups matching "pnews.comp.*" 
    or "pnews.rec.*" from the news server "news2.domain.org", 
    and the "gb2312" charset is used in these groups.
    This category does require explicily authentication to the news server by the supplied username/password</p>
</blockquote>
</div>
<? include('tailer.php'); ?>
</body>
</html>
