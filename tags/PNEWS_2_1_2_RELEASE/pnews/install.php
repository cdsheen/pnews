<?

require_once('version.inc.php');

$dname = 'pnews-' . str_replace( 'v', '', $pnews_version ) . '.tgz' ;

echo '<html>
<head>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
<LINK REL=STYLESHEET TYPE="text/css" HREF="style.css">
<title>PHP News Reader - Installation and Configuration</title>
</head>
<body style="background-color: #EEFFFF">
<table width=100% cellpadding=0 cellspacing=0><tr><td>
';
echo "<font face=\"Georgia\"><h3>$pnews_name $pnews_version</h3></font>\n";
echo "</td><td align=right valign=bottum>";
echo "<font face=\"Georgia\" size=1>Release Date: $pnews_release</font>\n";
echo '
</td></tr></table>
<font size=3 color=black face="Georgia">
PHP News Reader - Installation and Configuration
</font>
<hr>
';
?>
<font size=3 color=black face="Georgia">
<strong><em><font color="#0000FF">Installation</font></em></strong> 
<blockquote> 
  <p> The installation of PHP News Reader is quite simple.<br>
    You can download the latest source of PHP News Reader from:</p>
  <blockquote> 
    <p> <a href="http://pnews.sourceforge.net/" target="_blank">http://pnews.sourceforge.net/</a></p>
  </blockquote>
  <p> The source is packaged in tar+gzip (tgz) format, and the filename looks 
    like:</p>
  <blockquote> 
    <p> <strong>pnews-x.y.z.tgz</strong></p>
  </blockquote>
  <p> where x is the major version number, y is the minor version number, and z is 
    the patch version number</p>
  <p> After downloaded the source, extract the source tarball in the temporary 
    directory:</p>
  <blockquote> 
    <p> <strong># tar &nbsp;zxvf &nbsp;pnews-x.y.z.tgz</strong></p>
  </blockquote>
  <p> All source will be extracted into the directory &quot;pnews-x.y.z/&quot;</p>
  <p> Now, you can copy all the source from pnews-x.y.z/ to the directory you 
    want to<br>
    provide web service. Supposed your web document directory is in /home/www/htdocs/,</p>
  <blockquote> 
    <p> <strong># mkdir &nbsp;/home/www/htdocs/news/<br>
      # cd &nbsp;pnews-x.y.z/<br>
      # cp &nbsp;-a &nbsp;* &nbsp;/home/www/htdocs/news/</strong></p>
  </blockquote>
  <p> The configuration of PHP News Reader contains two files, &quot;config.inc.php&quot; 
    and &quot;newsgroups.lst&quot;<br>
    You can refer the template of these two files in the subdirectory &quot;examples/&quot;.</p>
  <p> The config.inc.php should be put in the root directory of PHP News Reader, 
    (such as /home/www/htdocs/news/)</p>
  <p> You can copy the file in the examples/ directory as a reference.</p>
  <blockquote> 
    <p> <strong># cd &nbsp;/home/www/htdocs/news/<br>
      # cp &nbsp;examples/config.inc.php&nbsp;&nbsp; .</strong></p>
  </blockquote>
  <p> The default location of &quot;newsgroups.lst&quot; is the same as config.inc.php,<br>
    but this can be changed by modifying the setting in &quot;config.inc.php&quot;.</p>
  <p> If you use the default setting, you can also copy the file from examples/ 
    subdirectory.</p>
  <blockquote> 
    <p> <strong># cp &nbsp;examples/newsgroups.lst&nbsp;&nbsp; .</strong></p>
  </blockquote>
  <p> Now, you can begin editing these two files.</p>
  <p>If both files are finished configuration, you can then use your favorite 
    browser to access this Web News,<br>
    for example:</p>
  <blockquote> 
    <p>http://your.web.server/news/</p>
  </blockquote>
  <p>Note you should have started your web server and make sure that PHP module is enabled.</p>
  <p>If you have any problems, or find any errors in this document, please send 
    your comments to cdsheen@users.sourceforge.net, any suggestion is appreciated.</p>
</blockquote>
<hr size="1">
<strong><em><font color="#0000FF"> Configuration for CONFIG.INC.PHP</font></em></strong> 
<blockquote> 
  <p> config.inc.php controls how PHP News Reader works.</p>
  <p> You can find the sample config.inc.php in the "example/" directory.</p>
  <p> All configuration applies PHP syntax and match the form of:</p>
  <blockquote> 
    <p> $CFG[&quot;foo&quot;] = &quot;bar&quot;;</p>
  </blockquote>
  <p> Each settings is well-documented in the example file.</p>
  <p> The configuration contains 3 sections:</p>
  <blockquote> 
    <p> Section 1 - Authentication<br>
      Section 2 - Contents<br>
      Section 3 - Languages</p>
  </blockquote>
  <p> <strong>Section 1 - Authetication</strong></p>
  <p> This section controls how you authenticate your users.</p>
  <p><strong>$CFG[&quot;auth_type&quot;]</strong></p>
  <blockquote> 
    <p>Specify the authentication type (REQUIRED)</p>
    <p> &quot;required&quot; - authentication is required to access the while 
      system<br>
      &quot;optional&quot; - authentication is only required for posting and forwarding<br>
      &quot;open&quot; - authentication is not needed</p>
  </blockquote>
  <p> <strong>$CFG[&quot;auth_prompt&quot;]</strong></p>
  <blockquote> 
    <p>Specify the style of authentication prompt (default: &quot;http&quot;) 
      (used only if auth_type != 'open')</p>
    <p> &quot;http&quot; - authenticated user via HTTP login window (default)<br>
      &quot;form&quot; - authenticated user via HTML login form</p>
  </blockquote>
  <p> <strong>$CFG[&quot;auth_http_realm&quot;]</strong></p>
  <blockquote> 
    <p>Specify the realm used in http authentication prompt (REQUIRED if auth_prompt 
      == 'http')</p>
    <p>This realm string will be displayed in the HTTP login window.</p>
  </blockquote>
  <p> <strong>$CFG[&quot;auth_method&quot;]</strong></p>
  <blockquote> 
    <p>Specify the authentication method (REQUIRED if auth_type != &quot;open&quot; 
      ) </p>
    <p>&quot;ldap&quot; - authenticated via LDAP server,<br>
      &quot;pop3&quot; - authenticated via POP3 server,<br>
      &quot;ftp&quot; - authenticated via FTP server,<br>
      &quot;mail&quot; - authenticated via multiple POP3 server,<br>
      &quot;mysql&quot; - authenticated via MySQL database,<br>
      &quot;pgsql&quot; - authenticated via PostgreSQL database,<br>
      &quot;user&quot; - authenticated via your self-defined method,</p>
  </blockquote>
  <p><strong>$CFG[&quot;auth_organization&quot;]</strong></p>
  <blockquote> 
    <p>Specify the organization of authentication source (REQUIRED if auth_type 
      != 'open')</p>
    <p>This is the organization name of your authentication source.</p>
  </blockquote>
  <p><strong>$CFG[&quot;auth_registration_info&quot;]</strong></p>
  <blockquote> 
    <p>Prompt users about how to get an account (default: &quot;&quot;)<br>
      This message will be displayed on login (FORM) or logout (HTTP) windows</p>
  </blockquote>
  <p> <strong>$CFG[&quot;auth_user_module&quot;]</strong></p>
  <blockquote> 
    <p>Specify user-defined authentication module location (REQUIRED if auth_method 
      == 'user')<br>
      You should implement a function with this prototype in your module:<br>
      <br>
      function check_user_password( $username, $password )</p>
      <p>This function should verify the correctness of $username and $password,
      <br>and then return true for granting access, false otherwise.
  </blockquote>
  <p> <strong>LDAP authentication parameters</strong> (REQUIRED if auth_method == 'ldap')</p>
  <blockquote> 
    <p> <strong>$CFG[&quot;ldap_server&quot;]</strong></p>
    <blockquote> 
      <p>LDAP server address ( address:port )</p>
      <p>$CFG[&quot;ldap_server&quot;] = &quot;ldap.domain.org:189&quot;;
    </blockquote>
    <p> <strong>$CFG[&quot;ldap_dn&quot;]</strong></p>
    <blockquote> 
      <p>LDAP distinguish name</p>
      <p>$CFG["ldap_dn"] = "ou=members, o=root";</p>
    </blockquote>
    <p> <strong>$CFG[&quot;ldap_bind_rdn&quot;]</strong></p>
    <blockquote> 
      <p>LDAP bind RDN, %u replaced by username (default: &quot;%u&quot;)</p>
      <p>$CFG["ldap_bind_rdn"] = "cn=%u,ou=members,o=root";</p>
    </blockquote>
    <p> <strong>$CFG[&quot;ldap_bind_pwd&quot;]</strong></p>
    <blockquote> 
      <p>LDAP bind password, %p replaced by password (default: &quot;%p&quot;)</p>
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
        null)<br>
        ( %u can not be used here )</p>
      <p>$CFG["ldap_variable"] = array( "%e" => "Email", "%n" => "Fullname" );</p>
      <p>In the above setting, the ldap attribure "Email" will be extracted from this search,<br>
         and be putted in variable %e for later use</p>
    </blockquote>
  </blockquote>
  <p><br>
    <strong>FTP authentication parameters</strong> (REQUIRED if auth_method == 'ftp')</p>
  <blockquote> 
    <p> <strong>$CFG[&quot;ftp_server&quot;]</strong></p>
    <blockquote> 
      <p>FTP server address ( address:port )</p>
      <p>$CFG["ftp_server"] = "ftp.domain.org";</p>
    </blockquote>
    <p> <strong>$CFG[&quot;ftp_deny&quot;]</strong></p>
    <blockquote> 
      <p> The user list which is denied for FTP authentication<br>
        (default:<em><strong> array( 'anonymous', 'guest', 'ftp' )</strong></em> 
        )</p>
      <p>$CFG["ftp_deny"] = array( 'anonymous', 'guest', 'ftp', 'root' );</p>
    </blockquote>
  </blockquote>
  <p> <strong>POP3 authentication parameters</strong> (REQUIRED if auth_method == 'pop3')</p>
  <blockquote> 
    <p> <strong>$CFG[&quot;pop3_server&quot;]</strong></p>
    <blockquote> 
      <p>POP3 server address ( address:port )</p>
      <p>$CFG["pop3_server"] = "pop3.domain.org";</p>
    </blockquote>
    <p> <strong>$CFG[&quot;pop3_user_modify&quot;]</strong></p>
    <blockquote> 
      <p>fix the username used for POP3, %u replaced by username (default: &quot;%u&quot;)</p>
      <p>Normally, you did not need this parameter. But on some BBS systems, a little modification is needed, such as:</p>
      <p>$CFG["pop3_user_modify"] = "%u.bbs";</p>
    </blockquote>
  </blockquote>
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
        =&gt; &quot;pop3.csie.nctu.edu.tw&quot;, &quot;@mail.domain.org&quot; 
        =&gt; &quot;pop3.domain.org&quot; );</p>
    </blockquote>
  </blockquote>
  <p><strong>MySQL/PostgreSQL Database authentication parameters</strong><p>
  <blockquote> 
    <p>(REQUIRED if auth_method == 'mysql' || auth_method == 'pgsql' )</p>
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
      <p>The password hashing method (default: &quot;&quot; - cleartext)<br>
        current supported hashing are: &quot;md5&quot; and &quot;crypt&quot;</p>
      <p>If your password does not saved as cleartext in the database,
      <br>this parameter defined the hashing method used to hash password.</p>
    </blockquote>
    <p>The support for database authentication module make it easy to integrate with the existing phpBB system.</p>
    <p>For example, to enable PHP News Reader to authenticate with the existing 
      users of your phpBB 2.0, use the following settings:</p>
    <blockquote> 
      <p>$CFG[&quot;db_server&quot;] = &quot;database.domain.org&quot;;<br>
        $CFG[&quot;db_name&quot;] = &quot;phpbb&quot;;<br>
        $CFG[&quot;db_username&quot;] = &quot;db_user&quot;;<br>
        $CFG[&quot;db_password&quot;] = &quot;db_pass&quot;;<br>
        $CFG[&quot;db_table&quot;] = &quot;phpbb_users&quot;;<br>
        $CFG[&quot;db_field_username&quot;] = &quot;username&quot;;<br>
        $CFG[&quot;db_field_password&quot;] = &quot;user_password&quot;;<br>
        $CFG[&quot;db_password_crypt&quot;] = &quot;md5&quot;;<br>
        $CFG[&quot;db_variable&quot;] = array( &quot;%e&quot; =&gt; &quot;user_email&quot; 
        );</p>
    </blockquote>
  </blockquote>
  <p><strong>$CFG[&quot;auth_expire_time&quot;]</strong></p>
  <blockquote> 
    <p>After this time in seconds, authentication is expired<br>
      And login again is required. Zero means never expire (default: 3600 seconds)</p>
  </blockquote>
  <p><strong>$CFG[&quot;post_restriction&quot;]</strong></p>
  <blockquote> 
    <p>Disallow the posting and forwarding of articles (default: off)</p>
  </blockquote>
  <p><strong>$CFG[&quot;auth_user_fullname]</strong></p>
  <blockquote> 
    <p>The full name of authenticated user (default: &quot;%u&quot;)</p>
    <p>The default is the username supplied for authentication</p>
    <p>You can customize this based on the variable defined in the [db_variable] or [ldap_variable]</p>
    <p>For example,</p>
    <p>$CFG["auth_user_fullname"] = "%n";</p>
  </blockquote>
  <p> <strong>$CFG[&quot;auth_user_email&quot;]</strong></p>
  <blockquote> 
    <p>The E-Mail of authenticated user (REQUIRED)<br>
      <br>
      The %u will be replaced by the username enter by authentication session<br>
      <br>
      Other variables are defined in the [db_variable] or [ldap_variable]<br>
      <br>
      An exception is, if you use 'mail' auth-method, %e will be replaced with the user's E-Mail,<br>
      and %u will be replaced with the user name part of the E-Mail (the strings before '@')</p>
  </blockquote>
  <p><strong>$CFG[&quot;log&quot;]</strong></p>
  <blockquote> 
    <p>Enable access log (default: &quot;&quot; - no log)</p>
    <p>$CFG["log"] = "/var/log/pnews.log";</p>
    <p>You need to create this file with write permission granted to the user running httpd</p>
  </blockquote>
  <p>&nbsp;</p>
  <p><strong>Section 2 - Contents</strong></p>
  <p>This section configure the contents appeared in this reader<br>
  </p>
  <p> <strong>$CFG[&quot;title&quot;]</strong></p>
  <blockquote> 
    <p>The title for this webnews (default: &quot;Webnews&quot;)</p>
  </blockquote>
  <p> <strong>$CFG[&quot;banner&quot;]</strong></p>
  <blockquote> 
    <p>The banner text or images (default: &quot;&quot;)</p>
    <p>$CFG["banner"] = "&lt;img src=banner.jpg&gt;";
  </blockquote>
  <p><strong>$CFG[&quot;group_list&quot;]</strong></p>
  <blockquote> 
    <p>The group definition that show on this Web News<br>
      <br>
      You should get a copy from examples/newsgroups.lst (default: &quot;newsgroups.lst&quot;)</p>
  </blockquote>
  <p><strong>$CFG[&quot;group_sorting&quot;]</strong></p>
  <blockquote> 
    <p>sort newsgroups in each catalog (default: off)</p>
  </blockquote>
  <p> <strong>$CFG[&quot;organization&quot;]</strong></p>
  <blockquote> 
    <p>The organization name of this site (default: &quot;News Server&quot;)</p>
  </blockquote>
  <p> <strong>$CFG[&quot;post_signature&quot;]</strong></p>
  <blockquote> 
    <p>The signature to appended at each posted article (default: &quot;&quot;)</p>
  </blockquote>
  <p> <strong>$CFG[&quot;show_sourceforge_logo&quot;]</strong></p>
  <blockquote> 
    <p>Show sourceforge logo (default: on)</p>
  </blockquote>
  <p> <strong>$CFG[&quot;time_format&quot;]</strong></p>
  <blockquote> 
    <p>The format used to displaying time (default: "%Y/%m/%d %H:%M:%S")</p>
    <p>$CFG["time_format"] = "%Y/%m/%d %H:%M:%S";</p>
    <p>The conversion specifiers used in the format string is the same as PHP strftime().<br>
    You can refer <a href="http://www.php.net/manual/en/function.strftime.php" target=_blank>http://www.php.net/manual/en/function.strftime.php</a> for details.
  </blockquote>
  <p><strong>$CFG[&quot;links&quot;]</strong></p>
  <blockquote> 
    <p>The links referring to other pages, (default: null)<br>
      <br>
      $CFG[&quot;links&quot;] = array( &quot;Back Home&quot; =&gt; &quot;../index.php&quot;, 
      &quot;Tech News&quot; =&gt; &quot;http://foo.bar/technews/&quot; );</p>
  </blockquote>
  <p><strong><br>
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
	</table>
  </blockquote>
  <p> <strong>$CFG[&quot;charset&quot;][&quot;config&quot;]</strong></p>
  <blockquote> 
    <p>The charset setting used in this config.inc.php</p>
  </blockquote>
  <p> <strong>$CFG[&quot;charset&quot;][&quot;grouplst&quot;]</strong></p>
  <blockquote> 
    <p>The charset setting used in newsgroups.lst</p>
  </blockquote>
  <p> <strong>$CFG[&quot;charset&quot;][&quot;database&quot;]</strong></p>
  <blockquote> 
    <p>The charset setting used in database or LDAP</p>
  </blockquote>
  <p> <strong>$CFG[&quot;charset&quot;][&quot;interface&quot;]</strong></p>
  <blockquote> 
    <p>The initial charset setting of interface</p>
  </blockquote>
  <strong>Notice: the original $CFG["language"] section is deprecated since v2.1.0,<br>please use $CFG["charset"] section instead</strong>
</blockquote>
<hr size="1">
<strong><em><font color="#0000FF">Configuration for NEWSGROUPS.LST</font></em></strong> 
<blockquote> 
  <p>newsgroups.lst list the news server / news groups to access</p>
  <p>The syntax of this file is different from that of config.inc.php</p>
  <p>All lines begin with the # is consider as comments</p>
  <p>The newsgroups of PHP News Reader can be grouped by several catalogs.</p>
  <p>Each catalogs is identified by a single line like this:</p>
  <blockquote> 
    <p><strong>[Computer Science]</strong></p>
  </blockquote>
  <p>This defined a catalog named as &quot;Computer Science&quot;</p>
  <p>The newsgroups defined in the same catalog should comes from the same news 
    server, and the encoding should be the same too. Also note that at least one 
    catalog should be defined in newsgroups.lst.</p>
  <p>The settings consist of two parts, key and value, and are separated by tab 
    or spaces<br>
    And the value part of setting ends at end of line</p>
  <p>For example, the following line sets "foobar" as &quot;value1 value2&quot;</p>
  <blockquote> 
    <p>foobar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;value1 value2</p>
  </blockquote>
  <p>The setting before any catalogs are global settings. Two global settings 
    are valid now:</p>
  <blockquote> 
    <p><strong># default charset for all catalog<br>
      charset&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;big5</strong></p>
    <p><strong># default news server for all catalog<br>
      server&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;news1.domain.org</strong></p>
  </blockquote>
  <p>After these global settings, each catalogs are defined. For example, the 
    following settings defined three catalogs,</p>
  <blockquote> 
    <p><strong>[catalog1]<br>
      server&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;news1.domain.org<br>
      group&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;nopy.*,nopy.comp.network<br>
      option&nbsp;&nbsp;&nbsp;&nbsp;default</strong></p>
    <p><strong>[catalog2]<br>
      # use the default news server<br>
      group&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;nopy.test,nopy.talk.*</strong></p>
    <p><strong>[catalog3]<br>
      server&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;news2.domain.org<br>
      group&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;nopy.comp.*,nopy.rec.*<br>
      charset&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;gb2312</strong></p>
  </blockquote>
  <p>The first catalog &quot;catalog1&quot; contains all newsgroups matching &quot;nopy.*&quot; 
    or &quot;nopy.comp.network&quot; from the news server &quot;news1.domain.org&quot;, 
    and the default charset is used in these groups.</p>
  <p>The second catalog &quot;catalog2&quot; contains all newsgroups matching 
    &quot;nopy.test&quot; or &quot;nopy.talk.*&quot; from the default news server 
    defined in the above global settings, and the default charset is used in 
    these groups. </p>
  <p>The third catalog &quot;catalog3&quot; contains all newsgroups matching &quot;nopy.comp.*&quot; 
    or &quot;nopy.rec.*&quot; from the news server &quot;news2.domain.org&quot;, 
    and the &quot;gb2312&quot; charset is used in these groups.</p>
  <p>In each section, an optional &quot;option&quot; setting can be defined.</p>
  <p>Two possible settings of &quot;option&quot; are currently supported.</p>
  <p>default</p>
  <blockquote> 
    <p>This catalog will become the default catalog when user first come in.</p>
    <p>Only one catalog can be marked as "default".<br>If multiple catalogs are marked as "default", the last catalog will become the default.</p>
  </blockquote>
  <p>private</p>
  <blockquote> 
    <p>The reading access for this catalog is restricted by authentication,<br>
      only used when the $CFG[&quot;auth_type&quot;] is &quot;optional&quot;</p>
  </blockquote>
  <p>Multiple options can be separated by comma, for example:</p>
  <blockquote> 
    <p><strong>option&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;default,private</strong></p>
  </blockquote>
  <strong>Notice: the original 'lang' setting is deprecated since v2.1.0,<br>please use 'charset' instead.</strong>
</blockquote>
<hr size="1">
<table width=100% cellspacing=0 cellpadding=0><tr><td>
<font size=3><? echo $pnews_claim; ?></font><br>
<a href=http://sourceforge.net/projects/pnews/ target=_blank>http://sourceforge.net/projects/pnews/</a>
</td><td align=right>
<a href="http://sourceforge.net/" alt="http://sourceforge.net/" target=_blank>
<img src="http://sourceforge.net/sflogo.php?group_id=71412&amp;type=1" border="0" alt="SourceForge.net Logo">
</a>
</td></tr></table>
</font>
</body>
</html>
