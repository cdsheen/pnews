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

require_once('../version.inc.php');

$dname = 'pnews-' . str_replace( 'v', '', $pnews_version ) . '.tgz' ;

$pname = 'pnews' . preg_replace( '/\D/', '', $pnews_version ) ;

if( $_SERVER['HTTPS'] )
	$sflogo = 'https://sourceforge.net/sflogo.php?group_id=71412&amp;type=1';
else
	$sflogo = 'http://sourceforge.net/sflogo.php?group_id=71412&amp;type=1';

echo <<<EOH
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
<LINK REL=STYLESHEET TYPE="text/css" HREF="style.css">
<title>PHP News Reader - Installation and Configuration Guide</title>
</head>
<body style="background-color: #EEFFFF">
<table width=100% cellpadding=0 cellspacing=0><tr><td>
<font face="Georgia"><h3>$pnews_name $pnews_version</h3></font>
</td><td align=right valign=bottum>
<font face="Georgia" size=1>Release Date: $pnews_release</font>
</td></tr>
</table>
<font size=3 color=black face="Georgia">
PHP News Reader - Installation and Configuration
</font>
<hr />
EOH;

?>
<font size=3 color=black face="Georgia">
<strong><em><font color="#0000FF">Upgrading</font></em></strong> 
<blockquote>
To upgrade PHP News Reader from the previous version, please follow
the following procedure:
<ol>
<li>Backup <b>config.inc.php</b> and <b>newsgroups.lst</b>
<li><a href="http://sourceforge.net/project/showfiles.php?group_id=71412" target=_blank>Download</a> the latest version of PHP News Reader
<li>Unpack the latest version of PHP News Reader and over-write the existing installation
<li>Restore <b>config.inc.php</b> and <b>newsgroups.lst</b>
<li>Reading the <a href=history.php>release notes</a>, and modify <b>config.inc.php</b> or <b>newsgroups.lst</b> if appliable
</ol>
</blockquote>
<hr />
<strong><em><font color="#0000FF">New Installation</font></em></strong> 
<blockquote>
  <p> The installation of PHP News Reader is quite simple.<br />
    You can download the latest source of PHP News Reader from:</p>
  <blockquote> 
    <p> <a href="http://pnews.sourceforge.net/" target="_blank">http://pnews.sourceforge.net/</a></p>
  </blockquote>
  <p> The source is packaged in two different format, one is tar+gzip (tgz),
and the other is zip.<br />Please download your preferred format from SourceForge.</p>
  <p> After downloaded the source, extract the source tarball in the temporary 
    directory:</p>
  <blockquote> 
    <p> <strong># tar &nbsp;zxvf &nbsp;<? echo $pname; ?>.tgz</strong></p>
  </blockquote>
  or if you prefer the zip format,
  <blockquote> 
    <p> <strong># unzip &nbsp;<? echo $pname; ?>.zip</strong></p>
  </blockquote>

  <p> All source will be extracted into the directory &quot;<? echo $pname; ?>/&quot;</p>
  <p> Now, you can copy all the source (including sub-directory) from pnews224/ to the directory where you 
    want to<br />
    provide web service. Supposed your web document directory is in /home/www/htdocs/,</p>
  <blockquote> 
    <p> <strong># mkdir &nbsp;/home/www/htdocs/news/<br />
      # cd &nbsp;<? echo $pname; ?>/<br />
      # cp &nbsp;-a &nbsp;* &nbsp;/home/www/htdocs/news/</strong></p>
  </blockquote>
  <p> The configuration of PHP News Reader contains two files, &quot;config.inc.php&quot; 
    and &quot;newsgroups.lst&quot;<br />
    You can refer the template of these two files in the subdirectory &quot;examples/&quot;.</p>
  <p> The config.inc.php should be put in the root directory of PHP News Reader, 
    (such as /home/www/htdocs/news/)</p>
  <p> You can copy the file in the examples/ directory as a reference.</p>
  <blockquote> 
    <p> <strong># cd &nbsp;/home/www/htdocs/news/<br />
      # cp &nbsp;examples/config.inc.php&nbsp;&nbsp; .</strong></p>
  </blockquote>
  <p> The default location of &quot;newsgroups.lst&quot; is the same as config.inc.php,<br />
    but this can be changed by modifying the setting in &quot;config.inc.php&quot;.</p>
  <p> If you use the default setting, you can also copy the file from examples/ 
    subdirectory.</p>
  <blockquote> 
    <p> <strong># cp &nbsp;examples/newsgroups.lst&nbsp;&nbsp; .</strong></p>
  </blockquote>
  <p> Now, you can begin editing these two files.</p>
  <p>If the configuration of both files is finished, you can then use your favorite 
    browser to access this Web News,<br />
    for example:</p>
  <blockquote> 
    <p>http://your.web.server/news/</p>
  </blockquote>
  <p>Note that you should have started your web server and make sure that PHP module is enabled.</p>
  <p>If you have any problems, or find any errors in this document, please send 
    your comments to cdsheen@users.sourceforge.net, any suggestion is appreciated.</p>
</blockquote>
<hr size="1">
<strong><em><font color="#0000FF"> Configuration for CONFIG.INC.PHP</font></em></strong> 
<blockquote> 
  <p> config.inc.php controls how PHP News Reader works.</p>
  <p> You can find the sample config.inc.php in the "example/" directory.</p>
  <p> All configuration applies PHP syntax and should be in the form of:</p>
  <blockquote> 
    <p> $CFG[&quot;foo&quot;] = &quot;bar&quot;;</p>
  </blockquote>
  <p> Each settings is well-documented in the example file.</p>
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
  <p><strong>$CFG[&quot;auth_type&quot;]</strong></p>
  <blockquote> 
    <p>Specify the authentication type (REQUIRED)</p>
    <p> &quot;required&quot; - authentication is required to access the while 
      system<br />
      &quot;optional&quot; - authentication is only required for posting and forwarding<br />
      &quot;open&quot; - authentication is not needed</p>
  </blockquote>
<a name=auth_prompt></a>
  <p> <strong>$CFG[&quot;auth_prompt&quot;]</strong></p>
  <blockquote> 
    <p>Specify the style of authentication prompt (default: &quot;form&quot;) 
      (used only if auth_type != 'open')</p>
    <p> &quot;http&quot; - authenticated user via HTTP login window<br />
      &quot;form&quot; - authenticated user via HTML login form (default and is recommanded)<br />
      &quot;cas&quot; - authenticated user via <a href=http://www.yale.edu/tp/auth/ target=_blank>CAS</a> (with version >= 2.3.0)</p>
  </blockquote>
<a name=auth_http_realm></a>
  <p> <strong>$CFG[&quot;auth_http_realm&quot;]</strong></p>
  <blockquote> 
    <p>Specify the realm used in http authentication prompt (REQUIRED if auth_prompt 
      == 'http')</p>
    <p>This realm string will be displayed in the HTTP login window.</p>
  </blockquote>
<a name=auth_method></a>
  <p> <strong>$CFG[&quot;auth_method&quot;]</strong></p>
  <blockquote> 
    <p>Specify the authentication method (REQUIRED if auth_type != &quot;open&quot; 
      ) </p>
    <p>&quot;ldap&quot; - authenticated via LDAP server,<br />
      &quot;pop3&quot; - authenticated via POP3 server,<br />
      &quot;pop3s&quot; - authenticated via POP3S server (with version >= 2.4.1),<br />
      &quot;ftp&quot; - authenticated via FTP server,<br />
      &quot;mail&quot; - authenticated via multiple POP3 server,<br />
      &quot;mysql&quot; - authenticated via MySQL database,<br />
      &quot;pgsql&quot; - authenticated via PostgreSQL database,<br />
      &quot;nntp&quot; - authenticated via NNTP News Server (with version >= 2.2.1),<br />
      &quot;nntps&quot; - authenticated via NNTPS News Server (with version >= 2.4.0),<br />
      &quot;cas&quot; - authenticated via <a href=http://www.yale.edu/tp/auth/ target=_blank>CAS</a> (with version >= 2.3.0),<br />
      &quot;user&quot; - authenticated via your self-defined method,</p>
  </blockquote>
<p>
<b>Notice for <a href="http://www.yale.edu/tp/auth/" target="_blank">CAS</a> users:</b>
<blockquote>If you use <a href="http://www.yale.edu/tp/auth/" target="_blank">CAS</a> to authenticate your users,<br />
both $CFG["auth_prompt"] and $CFG["auth_method"] should set to 'cas'.<br />
Besides this, you should first install <a href="http://esup-phpcas.sourceforge.net" target="_blank">phpCAS</a> in your PHP include_path or CAS/ for this method to run well.<br />
You can download phpCAS from the following place:<br />
&nbsp;&nbsp;&nbsp; <a href="http://esup-phpcas.sourceforge.net" target="_blank">http://esup-phpcas.sourceforge.net</a>
</blockquote>
</p>
<a name=auth_organization></a>
  <p><strong>$CFG[&quot;auth_organization&quot;]</strong></p>
  <blockquote> 
    <p>Specify the organization of authentication source (REQUIRED if auth_type 
      != 'open')</p>
    <p>This is the organization name of your authentication source.</p>
  </blockquote>
<a name=auth_registration_info></a>
  <p><strong>$CFG[&quot;auth_registration_info&quot;]</strong></p>
  <blockquote> 
    <p>Prompt users about how to get an account (default: &quot;&quot;)<br />
      This message will be displayed on login (FORM) or logout (HTTP) windows</p>
  </blockquote>
<a name=auth_user_module></a>
  <p> <strong>$CFG[&quot;auth_user_module&quot;]</strong></p>
  <blockquote> 
    <p>Specify user-defined authentication module location (REQUIRED if auth_method 
      == 'user')<br />
      You should implement a function with this prototype in your module:<br />
      <br />
      function check_user_password( $username, $password )</p>
      <p>This function should verify the correctness of $username and $password,
      <br />and then return true for granting access, false otherwise.
  </blockquote>
<a name=ldap_auth></a>
  <p> <strong>LDAP authentication parameters</strong> (REQUIRED if auth_method == 'ldap')</p>
  <blockquote> 
    <p> <strong>$CFG[&quot;ldap_server&quot;]</strong></p>
    <blockquote> 
      <p>LDAP server address ( address:port ), default port is 189</p>
      <p>$CFG[&quot;ldap_server&quot;] = &quot;ldap.domain.org:189&quot;;
    </blockquote>
    <p> <strong>$CFG[&quot;ldap_dn&quot;]</strong></p>
    <blockquote> 
      <p>LDAP distinguish name</p>
      <p>$CFG["ldap_dn"] = "ou=members, o=root";</p>
    </blockquote>
    <p> <strong>$CFG[&quot;ldap_bind_rdn&quot;]</strong></p>
    <blockquote> 
      <p>LDAP bind RDN, %u is substituted by username (default: &quot;%u&quot;)</p>
      <p>$CFG["ldap_bind_rdn"] = "cn=%u,ou=members,o=root";</p>
    </blockquote>
    <p> <strong>$CFG[&quot;ldap_bind_pwd&quot;]</strong></p>
    <blockquote> 
      <p>LDAP bind password, %p is substituted by password (default: &quot;%p&quot;)</p>
      <p>$CFG["ldap_bind_pwd"] = "%p";</p>
    </blockquote>
    <p> <strong>$CFG[&quot;ldap_filter&quot;]</strong></p>
    <blockquote> 
      <p>LDAP search filter (default: &quot;(cn=%u)&quot;)</p>
      <p>$CFG["ldap_filter"]   = "(&(cn=%u)(accountStatus=1)(MailStatus=1))";</p>
    </blockquote>
    <p> <strong>$CFG[&quot;ldap_variable&quot;]</strong></p>
    <blockquote> 
      <p>The attributes extract from this LDAP search for later use (default: 
        null)<br />
        ( %u can not be used here )</p>
      <p>$CFG["ldap_variable"] = array( "%e" => "Email", "%n" => "Fullname" );</p>
      <p>In the above setting, the ldap attribure "Email" will be extracted from this search,<br />
         and be putted in variable %e for later use</p>
    </blockquote>
  </blockquote>
<a name=ftp_auth></a>
  <p><br />
    <strong>FTP authentication parameters</strong> (REQUIRED if auth_method == 'ftp')</p>
  <blockquote> 
    <p> <strong>$CFG[&quot;ftp_server&quot;]</strong></p>
    <blockquote> 
      <p>FTP server address ( address:port ), default port is 21</p>
      <p>$CFG["ftp_server"] = "ftp.domain.org";</p>
    </blockquote>
    <p> <strong>$CFG[&quot;ftp_deny&quot;]</strong></p>
    <blockquote> 
      <p> The user list which is denied for FTP authentication<br />
        (default:<em><strong> array( 'anonymous', 'guest', 'ftp' )</strong></em> 
        )</p>
      <p>$CFG["ftp_deny"] = array( 'anonymous', 'guest', 'ftp', 'root' );</p>
    </blockquote>
  </blockquote>
<a name=pop3_auth></a>
  <p> <strong>POP3 authentication parameters</strong> (REQUIRED if auth_method == 'pop3')</p>
  <blockquote> 
    <p> <strong>$CFG[&quot;pop3_server&quot;]</strong></p>
    <blockquote> 
      <p>POP3 server address ( address:port ), default port is 110</p>
      <p>$CFG["pop3_server"] = "pop3.domain.org";</p>
    </blockquote>
    <p> <strong>$CFG[&quot;pop3_user_modify&quot;]</strong></p>
    <blockquote> 
      <p>fix the username used for POP3, %u is substituted by username (default: &quot;%u&quot;)</p>
      <p>Normally, you did not need this parameter. But on some BBS systems, a little modification is needed, such as:</p>
      <p>$CFG["pop3_user_modify"] = "%u.bbs";</p>
    </blockquote>
  </blockquote>
<a name=pop3s_auth></a>
  <p> <strong>POP3S authentication parameters</strong> (REQUIRED if auth_method == 'pop3')</p>
  <blockquote>
    <p> <strong>$CFG[&quot;pop3s_server&quot;]</strong></p>
    <blockquote> 
      <p>POP3S server address ( address:port ), default port is 995</p>
      <p>$CFG["pop3s_server"] = "pop3s.domain.org";</p>
    </blockquote>
    <p>This setting first appeared in the <b>v2.4.1</b> of PHP News Reader</p>
    <p>OpenSSL support for PHP module should be enabled, and the PHP should be v4.3.0 or greater</p>
  </blockquote>
<a name=mail_auth></a>
  <p> <strong>Mail authentication parameters</strong> (REQUIRED if auth_method == 'mail')</p>
  <blockquote> 
    <p> <strong>$CFG[&quot;pop3_mapping&quot;]</strong></p>
    <blockquote> 
      <p>the mapping from E-Mail to POP3 server address</p>
      <p>User should login with full E-Mail address, and this module will
         use different POP3 server to authenticated user based on the domain part of supplied E-Mail</p>
      <p>The following example will use "pop3.csie.nctu.edu.tw" to authenticate "xxx@csie.nctu.edu.tw",
         and use "pop3.domain.org" to authenticate "yyy@mail.domain.org".</p>
      <p>$CFG[&quot;pop3_mapping&quot;] = array( &quot;@csie.nctu.edu.tw&quot; 
        =&gt; &quot;pop3.csie.nctu.edu.tw&quot;,<br />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &quot;@mail.domain.org&quot; =&gt; &quot;pop3.domain.org&quot; );</p>
    </blockquote>
  </blockquote>
<a name=nntp_auth></a>
  <p><strong>NNTP authentication parameters</strong> (REQUIRED if auth_method == 'nntp')</p>
  <blockquote> 
    <p> <strong>$CFG[&quot;auth_nntp_server&quot;]</strong></p>
    <blockquote> 
      <p>News (NNTP) server address and port ( address:port ), default port is 119</p>
      <p>$CFG["auth_nntp_server"] = "news.domain.org";</p>
    </blockquote>
    <p>Note that this option only deal with the authentication of PHP News Reader,
       it has nothing to do with the authentication perform by news server.
       Add <b>auth</b> option to your newsgroups.lst if your news server requires authentication.</p>
    <p>This setting first appeared in the <b>v2.2.0</b> of PHP News Reader</p>
  </blockquote>
<a name=nntps_auth></a>
  <p><strong>NNTPS authentication parameters</strong> (REQUIRED if auth_method == 'nntps')</p>
  <blockquote> 
    <p> <strong>$CFG[&quot;auth_nntps_server&quot;]</strong></p>
    <blockquote> 
      <p>News (NNTPS) server address and port ( address:port ), default port is 563</p>
      <p>$CFG["auth_nntps_server"] = "nntps.domain.org";</p>
    </blockquote>
    <p>Note that this option only deal with the authentication of PHP News Reader,
       it has nothing to do with the authentication perform by news server.
       Add <b>auth</b> option to your newsgroups.lst if your news server requires authentication.</p>
    <p>This setting first appeared in the <b>v2.4.0</b> of PHP News Reader</p>
    <p>OpenSSL support for PHP module should be enabled, and the PHP should be v4.3.0 or greater</p>
  </blockquote>
<a name=cas_auth></a>
  <p><strong>CAS authentication parameters</strong> (REQUIRED if auth_method == 'cas')</p>
  <blockquote> 
    <p> <strong>$CFG[&quot;auth_cas_server&quot;]</strong></p>
    <blockquote> 
      <p>CAS server address and port ( address:port ), the port part should <b>not</b> be omitted.</p>
      <p>$CFG["auth_cas_server"] = "cas.domain.org:1234";</p>
    </blockquote>
    <p> <strong>$CFG[&quot;auth_cas_base_uri&quot;]</strong></p>
    <blockquote> 
      <p>CAS Server Base URI</p>
      <p>$CFG["auth_cas_base_uri"] = "cas";</p>
    </blockquote>
    <p> <strong>$CFG[&quot;auth_cas_debug&quot;]</strong></p>
    <blockquote> 
      <p>The filename for outputing debug log, or <b>false</b> to turn off debug mode (default: false)</p>
      <p>$CFG["auth_cas_debug"] = "/tmp/phpcas.log";</p>
    </blockquote>
    <p>The above settings first appeared in the <b>v2.3.0</b> of PHP News Reader</p>
  </blockquote>
<a name=db_auth></a>
  <p><strong>MySQL/PostgreSQL Database authentication parameters</strong><p>
  <blockquote>
    <p>(REQUIRED if auth_method == 'mysql' or auth_method == 'pgsql' )</p>
    <p>These parameters are used in both MySQL and PostgreSQL database authentication module.</p>
    <p><strong>$CFG[&quot;db_server&quot;]</strong></p>
    <blockquote> 
      <p>The database server address (address:port)</p>
    </blockquote>
    <p> <strong>$CFG[&quot;db_name&quot;]</strong></p>
    <blockquote> 
      <p>The database name</p>
    </blockquote>
    <p> <strong>$CFG[&quot;db_username&quot;]</strong></p>
    <blockquote> 
      <p>The username used to connect database</p>
    </blockquote>
    <p> <strong>$CFG[&quot;db_password&quot;]</strong></p>
    <blockquote> 
      <p>The password used to connect database</p>
    </blockquote>
    <p> <strong>$CFG[&quot;db_table&quot;]</strong></p>
    <blockquote> 
      <p>The table name for user information</p>
    </blockquote>
    <p> <strong>$CFG[&quot;db_field_username&quot;]</strong></p>
    <blockquote> 
      <p>The field name for username</p>
    </blockquote>
    <p> <strong>$CFG[&quot;db_field_password&quot;]</strong></p>
    <blockquote> 
      <p>The field name for password</p>
    </blockquote>
    <p> <strong>$CFG[&quot;db_password_crypt&quot;]</strong></p>
    <blockquote> 
      <p>The password hashing method (default: &quot;&quot; - cleartext)<br />
        current supported hashing are: &quot;md5&quot; and &quot;crypt&quot;</p>
      <p>If your password does not saved as cleartext in the database,
      <br />this parameter defined the hashing method used to hash password.</p>
    </blockquote>
    <p>The support for database authentication module make it easy to integrate with the existing phpBB system.</p>
    <p>For example, to enable PHP News Reader to authenticate with the existing 
      users of your phpBB 2.0, use the following settings:</p>
    <blockquote> 
      <p>$CFG[&quot;db_server&quot;] = &quot;database.domain.org&quot;;<br />
        $CFG[&quot;db_name&quot;] = &quot;phpbb&quot;;<br />
        $CFG[&quot;db_username&quot;] = &quot;db_user&quot;;<br />
        $CFG[&quot;db_password&quot;] = &quot;db_pass&quot;;<br />
        $CFG[&quot;db_table&quot;] = &quot;phpbb_users&quot;;<br />
        $CFG[&quot;db_field_username&quot;] = &quot;username&quot;;<br />
        $CFG[&quot;db_field_password&quot;] = &quot;user_password&quot;;<br />
        $CFG[&quot;db_password_crypt&quot;] = &quot;md5&quot;;<br />
        $CFG[&quot;db_variable&quot;] = array( &quot;%e&quot; =&gt; &quot;user_email&quot; 
        );</p>
    </blockquote>
  </blockquote>
<a name=auth_expire_time></a>
  <p><strong>$CFG[&quot;auth_expire_time&quot;]</strong></p>
  <blockquote> 
    <p>After this time in seconds, authentication is expired<br />
      And login again is required. Zero means never expire (default: 3600 seconds)</p>
  </blockquote>
<a name=post_restriction></a>
  <p><strong>$CFG[&quot;post_restriction&quot;]</strong></p>
  <blockquote> 
    <p>Disallow the posting and forwarding of articles (default: false)</p>
  </blockquote>
<a name=auth_user_fullname></a>
  <p><strong>$CFG[&quot;auth_user_fullname]</strong></p>
  <blockquote> 
    <p>The full name of authenticated user (default: &quot;%u&quot;)</p>
    <p>The default is the username supplied for authentication</p>
    <p>You can customize this based on the variable defined in the [db_variable] or [ldap_variable]</p>
    <p>For example,</p>
    <p>$CFG["auth_user_fullname"] = "%n";</p>
  </blockquote>
<a name=auth_user_email></a>
  <p> <strong>$CFG[&quot;auth_user_email&quot;]</strong></p>
  <blockquote> 
    <p>The E-Mail of authenticated user (REQUIRED)<br />
      <br />
      The %u will be substituted by the username enter by authentication session<br />
      <br />
      Other variables are defined in the [db_variable] or [ldap_variable]<br />
      <br />
      An exception is, if you use 'mail' auth-method, %e will be substituted with the user's E-Mail,<br />
      and %u will be substituted with the user name part of the E-Mail (the strings before '@')</p>
  </blockquote>
<a name=log></a>
  <p><strong>$CFG[&quot;log&quot;]</strong></p>
  <blockquote> 
    <p>Enable access log (default: &quot;&quot; - no log)</p>
    <p>$CFG["log"] = "/var/log/pnews.log";</p>
    <p>You need to create this file with write permission granted to the user running httpd</p>
  </blockquote>
  <p>&nbsp;</p>
<a name=section2></a>
  <p><strong>Section 2 - Contents</strong></p>
  <p>This section configure the contents appeared in this reader<br />
  </p>
<a name=url_base></a>
  <p> <strong>$CFG[&quot;url_base&quot;]</strong></p>
  <blockquote> 
    <p>Specify the base URL of your PHP News Reader installation (REQUIRED)</p>
    <p>Prior to <b>v2.2.1</b>, this setting is only REQUIRED if $CFG[&quot;url_rewrite&quot;] = true</p>
    <p>After <b>v2.2.2</b>, this setting is always REQUIRED</p>
    <p>This setting first appeared in the <b>v2.2.0</b> of PHP News Reader</p>
  </blockquote>
<a name=url_rewrite></a>
  <p> <strong>$CFG[&quot;url_rewrite&quot;]</strong></p>
  <blockquote> 
    <p>Enable or disable the URL rewrite function (default: &quot;false&quot;)</p>
    <p>Read more about <a href=url_rewrite.php>URL rewriting</a> by <a href=url_rewrite.php>clicking here</a>.
    <p>You should enable Apache <b>mod_rewrite</b> module and <b>AllowOverride</b> for per-directory access (.htaccess)<br />
    For more information about <b>mod_rewrite</b>, visit <a href="http://httpd.apache.org/docs/misc/rewriteguide.html" target=_blank>http://httpd.apache.org/docs/misc/rewriteguide.html</a> for details.</p>
    <p>This setting first appeared in the <b>v2.2.0</b> of PHP News Reader</p>
  </blockquote>
<a name=https_login></a>
  <p> <strong>$CFG[&quot;https_login&quot;]</strong></p>
  <blockquote> 
    <p>Whether to use SSL(HTTPS) after authentication (default: off)</p>
    <p>This setting first appeared in the <b>v2.2.2</b> of PHP News Reader</p>
  </blockquote>
<a name=style_sheet></a>
  <p><strong>$CFG[&quot;style_sheet&quot;]</strong></p>
  <blockquote>
    <p>Setting the style sheet used for all html (default: &quot;standard.css&quot;)</p>
    <p>This setting first appeared in the <b>v2.2.1</b> of PHP News Reader</p>
    <p>After v2.2.4, the configured style sheet should be placed in the css/ subdirectory.</p>
  </blockquote>
<a name=title></a>
  <p> <strong>$CFG[&quot;title&quot;]</strong></p>
  <blockquote> 
    <p>The title for this webnews (default: &quot;Webnews&quot;)</p>
  </blockquote>
<a name=banner></a>
  <p><strong>$CFG[&quot;banner&quot;]</strong></p>
  <blockquote> 
    <p>The banner text or images (default: &quot;&quot;)</p>
    <p>$CFG["banner"] = "&lt;img src=banner.jpg&gt;";
  </blockquote>
<a name=group_list></a>
  <p><strong>$CFG[&quot;group_list&quot;]</strong></p>
  <blockquote> 
    <p>The group definition that show on this Web News<br />
      <br />
      You should get a copy from examples/newsgroups.lst (default: &quot;newsgroups.lst&quot;)</p>
  </blockquote>
<a name=magic_tag></a>
  <p><strong>$CFG[&quot;magic_tag&quot;]</strong></p>
  <blockquote> 
    <p>Use magic tag to indicate the visit state of newsgroup (default: on)</p>
      While magic_tag is on, the url for each newsgroup will become:
    <blockquote>http://webnews.host/indexing.php?server=*&group=news.test<font color=red>&magic=23479</font></blockquote>
      or while url_rewrite is on,
    <blockquote>http://webnews.host/group//news.help<font color=red>?23479</font></blockquote>
      PHP News Reader use the above magic number in <font color=red>red</font> to indicate that the group has been visited by user (with the help of browser's history).<br /><br />
      The default is on. If you do not like this feature, you can turn it off by:
    <blockquote>$CFG[&quot;magic_tag&quot;] = off;</blockquote>
    <p>This setting first appeared in the <b>v2.2.4</b> of PHP News Reader</p>
  </blockquote>
<a name=cache_dir></a>
  <p><strong>$CFG[&quot;cache_dir&quot;]</strong></p>
  <blockquote>
    <p>Enable caching of indexing data (default: false)</p>
    <p>Turning on the caching will relief the loading of news server,
       and speed up the indexing process for large groups.<p>
    <p>To enable caching, set this to the directory of cache data.<br>
       You should grant write permission on this directory to the user running PHP.<p>
    <p>This setting first appeared in the <b>v2.4.0</b> of PHP News Reader</p>
  </blockquote>
<a name=group_sorting></a>
  <p><strong>$CFG[&quot;group_sorting&quot;]</strong></p>
  <blockquote> 
    <p>sort newsgroups in each catalog (default: false)</p>
  </blockquote>
<a name=email_editing></a>
  <p><strong>$CFG[&quot;email_editing&quot;]</strong></p>
  <blockquote> 
    <p>Allow E-Mail editing when posting article (default: true)</p>
    <p>This setting first appeared in the <b>v2.3.0</b> of PHP News Reader</p>
  </blockquote>
<a name=articles_per_page></a>
  <p><strong>$CFG[&quot;articles_per_page&quot;]</strong></p>
  <blockquote> 
    <p>Setting the number of articles shown per page (default: 20)</p>
    <p>This setting first appeared in the <b>v2.2.0</b> of PHP News Reader</p>
  </blockquote>
<a name=show_newest_top></a>
<a name=article_order_reverse></a>
  <p><strong>$CFG[&quot;show_newest_top&quot;]</strong></p>
  <blockquote> 
    <p>Show the newest article as the top item (default: true)</p>
    <p>This setting first appeared in the <b>v2.4.0</b> of PHP News Reader</p>
    <p>It is to replace $CFG[&quot;article_order_reverse&quot;] in <b>v2.2 - v2.3</b></p>
  </blockquote>
<a name=show_article_popup></a>
  <p><strong>$CFG[&quot;show_article_popup&quot;]</strong></p>
  <blockquote> 
    <p>Controlling the article to show in popup window or not (default: false)</p>
    <p>This setting first appeared in the <b>v2.2.0</b> of PHP News Reader</p>
  </blockquote>
<a name=filter_ansi_color></a>
  <p><strong>$CFG[&quot;filter_ansi_color&quot;]</strong></p>
  <blockquote> 
    <p>Setting this option to true will cause filtering of ANSI coloring code from article (default: true)</p>
    <p>This setting first appeared in the <b>v2.2.0</b> of PHP News Reader</p>
  </blockquote>
<a name=organization></a>
  <p> <strong>$CFG[&quot;organization&quot;]</strong></p>
  <blockquote> 
    <p>The organization name of this site (default: &quot;News Server&quot;)</p>
  </blockquote>
<a name=post_signature></a>
  <p> <strong>$CFG[&quot;post_signature&quot;]</strong></p>
  <blockquote> 
    <p>The signature to appended at each posted article (default: &quot;&quot;)</p>
    <p>Note: This is NOT a per-user setting!</p>
  </blockquote>
<a name=show_sourceforge_logo></a>
  <p> <strong>$CFG[&quot;show_sourceforge_logo&quot;]</strong></p>
  <blockquote> 
    <p>Show sourceforge logo (default: true)</p>
  </blockquote>
<a name=language_switch></a>
  <p> <strong>$CFG[&quot;language_switch&quot;]</strong></p>
  <blockquote> 
    <p>Show language switch or not (default: true)</p>
  </blockquote>
<a name=time_format></a>
  <p> <strong>$CFG[&quot;time_format&quot;]</strong></p>
  <blockquote> 
    <p>The format used to displaying time (default: "%Y/%m/%d %H:%M:%S")</p>
    <p>$CFG["time_format"] = "%Y/%m/%d %H:%M:%S";</p>
    <p>The conversion specifiers used in the format string is the same as PHP strftime().<br />
    You can refer <a href="http://www.php.net/manual/en/function.strftime.php" target=_blank>http://www.php.net/manual/en/function.strftime.php</a> for details.
  </blockquote>
<a name=links></a>
  <p><strong>$CFG[&quot;links&quot;]</strong></p>
  <blockquote> 
    <p>The links referring to other pages. (default: null)<br />
    <p>This is an associate array, the key is link text and the value is the url.<p>
      <br />
      $CFG[&quot;links&quot;] = array( &quot;Back Home&quot; =&gt; &quot;../index.php&quot;, 
      &quot;Tech News&quot; =&gt; &quot;http://foo.bar/technews/&quot; );</p>
  </blockquote>
<a name=section3></a>
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
        <tr><td>Fran&ccedil;ais</td><td>fr</td><td>ISO-8859-1</td></tr>
	</table>
  </blockquote>
<a name=interface_language></a>
  <p> <strong>$CFG[&quot;interface_language&quot;]</strong></p>
  <blockquote>
    <p>The language setting of interface ( "en", "zh-tw", "zh-cn", "unicode", "fr" )<br />
     (default: "en" )</p>
    <p>This setting first appeared in the <b>v2.3.0</b> of PHP News Reader<br />
       If not set in v2.3.0 or later, the default language used in interface is "en" - English</p>
    <p>Notice: the original $CFG["charset"]["interface"] is deprecated since v2.3.0,<br />please use $CFG["interface_language"] instead</p>
  </blockquote>
<a name=charset_config></a>
  <p> <strong>$CFG[&quot;charset&quot;][&quot;config&quot;]</strong></p>
  <blockquote> 
    <p>The charset setting used in this config.inc.php</p>
  </blockquote>
<a name=charset_grouplst></a>
  <p> <strong>$CFG[&quot;charset&quot;][&quot;grouplst&quot;]</strong></p>
  <blockquote> 
    <p>The charset setting used in newsgroups.lst</p>
  </blockquote>
<a name=charset_database></a>
  <p> <strong>$CFG[&quot;charset&quot;][&quot;database&quot;]</strong></p>
  <blockquote> 
    <p>The charset setting used in database or LDAP.<br />
       If you do not enable database or ldap authentication module, this setting will be ignored</p>
  </blockquote>
  <p>Notice: the original $CFG["language"] section is deprecated since v2.1.0,<br />please use $CFG["charset"] section instead</p>
</blockquote>
<a name=newsgroups_lst></a>
<hr size="1">
<strong><em><font color="#0000FF">Configuration for NEWSGROUPS.LST</font></em></strong> 
<blockquote> 
  <p>newsgroups.lst list the news server / news groups to access</p>
  <p>The syntax of this file is different from that of config.inc.php</p>
  <p>All lines begin with the # is considered as comments</p>
  <p>The newsgroups of PHP News Reader can be grouped by several catalogs.</p>
  <p>Each catalogs is identified by a single line like this:</p>
  <blockquote> 
    <p><strong>[Computer Science]</strong></p>
  </blockquote>
  <p>This defined a catalog named as &quot;Computer Science&quot;</p>
  <p>The newsgroups defined in the same catalog should comes from the same news 
    server, and the encoding should be the same too. Also note that at least one 
    catalog should be defined in newsgroups.lst.</p>
  <p>The settings consist of two parts, key and value, and are separated by tabs 
    or spaces<br />
    And the value part of setting ends at end of line</p>
  <p>For example, the following line sets "foobar" as &quot;value1 value2&quot;</p>
  <blockquote> 
    <p>foobar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;value1 value2</p>
  </blockquote>
  <p>The setting before any catalogs are global settings. Two global settings 
    are valid now:</p>
  <blockquote> 
    <p><strong># default charset for all catalog<br />
      charset&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;big5</strong></p>
    <p><strong># default news server for all catalog<br />
      server&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;news1.domain.org</strong></p>
  </blockquote>
  <p>After these global settings, each catalogs are defined. For example, the 
    following settings defined three catalogs,</p>
  <blockquote> 
    <p><strong>[catalog1]<br />
      server&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;news1.domain.org<br />
      group&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;nopy.*,nopy.comp.network<br />
      option&nbsp;&nbsp;&nbsp;&nbsp;default</strong></p>
    <p><strong>[catalog2]<br />
      # use the default news server<br />
      group&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;nopy.test,nopy.talk.*<br />
      auth&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;none</strong></p>
    <p><strong>[catalog3]<br />
      server&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;news2.domain.org<br />
      group&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;nopy.comp.*,nopy.rec.*<br />
      auth&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;username,password<br />
      charset&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;gb2312</strong></p>
  </blockquote>
  <p>The first catalog &quot;catalog1&quot; contains all newsgroups matching &quot;nopy.*&quot; 
    or &quot;nopy.comp.network&quot; from the news server &quot;news1.domain.org&quot;, 
    and the default charset is used in these groups. By the way, this catalog does not
    require authentication to the news server (by default)</p>
  <p>The second catalog &quot;catalog2&quot; contains all newsgroups matching 
    &quot;nopy.test&quot; or &quot;nopy.talk.*&quot; from the default news server 
    defined in the above global settings, and the default charset is used in 
    these groups.  By the way, this catalog does not require authentication to the news server</p>
  <p>The third catalog &quot;catalog3&quot; contains all newsgroups matching &quot;nopy.comp.*&quot; 
    or &quot;nopy.rec.*&quot; from the news server &quot;news2.domain.org&quot;, 
    and the &quot;gb2312&quot; charset is used in these groups.
    This catalog does require explicily authentication to the news server by the supplied username/password</p>
  <a name=group_match></a>
  <p><strong>Syntax of 'group'</strong></p>
  This setting defines the groups included in each catalog.<br />
  Multiple groups can be separated by a comma ","<br />
  Groups can be specified in the following syntax:
  <ol>
    <li>Full group name for inclusion, i.e: <b>tw.bbs.comp.hardware</b><br /><br />
    <li>Pattern match for sub-class groups inclusion, i.e: <b>tw.bbs.comp.*</b><br /><br />
        Only the final class of group name can be used in pattern match<br />
        &nbsp;&nbsp;&nbsp;For example, <b>tw.bbs.*.hardware</b> is INVALID.<br /><br />
        Before v2.2.4, only 'all-match' patttern (a single <b>*</b> ) is allowed in the final class pattern of group name<br />
        &nbsp;&nbsp;&nbsp;For example, <b>tw.bbs.comp.hard*</b> is INVALID in v2.2.4, while <b>tw.bbs.comp.*</b> is VALID.<br /><br />
        After v2.3.0, the final class pattern is not restricted to a single <b>*</b>,<br />
        &nbsp;&nbsp;&nbsp;For example, <b>tw.bbs.comp.hard*</b> is now VALID in v2.3.0 or later.<br /><br />
    <li>Full group name for exclusion, i.e: <b>!tw.bbs.comp.virus</b><br />
        Only full group name is allowed here, it is used to nagative the previously included group.
  </ol>
  <a name=option></a>
  <p><strong>Syntax of 'option'</strong></p>
  <p>In each section, an optional &quot;option&quot; setting can be defined.</p>
  <p>Three possible settings of &quot;option&quot; are currently supported.</p>
  <p>default</p>
  <blockquote> 
    <p>This catalog will become the default catalog when user first come in.</p>
    <p>Only one catalog can be marked as "default".<br />If multiple catalogs are marked as "default", the last catalog will become the default.</p>
  </blockquote>
  <p>private</p>
  <blockquote> 
    <p>The reading access for this catalog is restricted by authentication,<br />
      only used when the $CFG[&quot;auth_type&quot;] is &quot;optional&quot;</p>
  </blockquote>
  <p>nntps</p>
  <blockquote> 
    <p>With this option, the connection to news server will be NNTP over SSL,
       also known as NNTPS. This option first appeared in v2.4.0, and require
       PHP with v4.3.0 or greater and be compiled with OpenSSL support.</p>
  </blockquote>
  <p>Multiple options can be separated by comma, for example:</p>
  <blockquote> 
    <p><strong>option&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;default,private</strong></p>
  </blockquote>
  <p>Notice: the original 'lang' setting is deprecated since v2.1.0, please use 'charset' instead.</p>
</blockquote>
<hr size="1">
<table width=100% cellspacing=0 cellpadding=0><tr><td>
<font size=3><? echo $pnews_claim; ?></font><br />
<a href=http://sourceforge.net/projects/pnews/ target=_blank>http://sourceforge.net/projects/pnews/</a>
</td><td align=right>
<a href="http://sourceforge.net/" alt="http://sourceforge.net/" target=_blank>
<img src="<? echo $sflogo; ?>" border="0" alt="SourceForge.net Logo">
</a>
</td></tr></table>
</font>
</body>
</html>
