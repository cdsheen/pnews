<?

require_once('version.inc.php');

echo '<html>
<head>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
<LINK REL=STYLESHEET TYPE="text/css" HREF="style.css">
<title>PHP News Reader</title>
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
PHP News Reader / NNRP Library
</font>
<hr>
<font face="Georgia" size="3" color="black">
PHP News Reader is a Web-based News Reader. It supports the 
standard NNTP protocol (<a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=971" target=_blank>RFC 971</a>) for reading, posting, deleting,
forwarding and replying news articles.
In fact, PHP News Reader contains various useful functions to communicate
with News Server, which are collected as a NNRP Library.
NNRP Library provides a friendly API for accessing news server via NNTP.
<p>
PHP News Reader does not support threading of News articles and newsgroup subscription.
<p>
<a name=server_requirement></a>
The requirement for PHP News Reader on the server side:
<ul>
<li>Web Server with PHP modules installed ( PHP 4.1.0 or greater )
<li>News Server with <a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=2980" target=_blank>RFC 2980</a> Extensions
</ul>
<p>As for <a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=2980" target=_blank>RFC 2980</a>, PHP News Reader only requires the News Server to have the following extensions:<br>
<ul>
<li>LIST &nbsp;ACTIVE &nbsp;[wildmat]
<li>LIST &nbsp;NEWSGROUPS  &nbsp;[wildmat]
</ul>
The <a href="http://www.isc.org/products/INN/" target=_blank>INN News Server</a> package
 (with the version greater than <b><a href="http://www.isc.org/products/INN/">INN 1.5</a></b>) includes a NNRPD daemon to support this.
<p>
If your server is operated by INN, you should check about the NNRP access permission.
For example, when you telnet to an INN News server at port 119, you must get response like this: (the <b>NNRP</b> in bold)
<ul>
200 nopy.com InterNetNews <b>NNRP</b> server INN 2.3.2 ready (posting ok).
</ul>
instead of,
<ul>
200 nopy.com InterNetNews server INN 2.3.2 ready<br>
-or-<br>
502 You have no permission to talk.  Goodbye.
</ul>
<p>
The requirement for PHP News Reader on the client side (web browser) :
<ul>
<li>Support Cascade Style Sheet 1.0
<li>Support Javascript >= 1.2
<li>Support Document Object Model
<li>Accept Cookies (not necessary)
</ul>
The Mozilla 1.2 and Internet Explorer 5.5 works fine for me.
<p>
PHP News Reader has nothing to do with user registration.
It is designed to work with the EXISTING authentication system.
PHP News Reader supports flexible authentication interface modules,
which let administrator easy to control how to authenticate users.
There are various handy authentication modules availabled.
<ul>
<li>POP3 - authenticate user using the existing POP3 server.
<li>Mail - authenticate user using multiple POP3 server.
<!--
<li>Firebird BBS - authenticate user with the existing Firebird Bulletin Board System.
-->
<li>LDAP - authenticate user using LDAP server.
<li>FTP - authenticate user with the existing FTP server
<li>MySQL - authenticate user existing in MySQL database.
<li>PostgreSQL - authenticate user existing in PostgreSQL database.
</ul>
<p>
The support for MySQL and PostgreSQL also makes it easy to integrate with the popular <a href="http://www.phpbb.com/" target=_blank>phpBB</a> system.
<p>
The login prompt can be configured as HTTP authentication or FORM style.
<p>
PHP News Reader contains three interface languages (English, Traditional Chinese and Simplified Chinese).
The preferred encoding can be switched any time and any where.
<p>
Since the PHP iconv() function may not work on some systems and it also has problems of handling Chinese words.
PHP News Reader use a self-written conversion system.
The conversion between Chinese (BIG5, GB2312 and UTF-8) encodings is supported.
The encoding of article is auto-converted to the preferred one
while the selected encoding of interface is different from the encoding of the news articles.
This conversion also effectives while posting, replying, forwarding and cross-posting articles.
The posted articles will also be converted to the original encoding of the server.
<p>
PHP News Reader\'s development started around 2001/08.
I wrote this software in my leisure time. Although PHP News Reader still
lacks many fancy features, it works fine to meet the most requirements.
<p>
PHP News Reader v2.0 (2003/01/19)
<ul>
<li>First public release and hosts on SourceForge
<li>Rewrite the authentication codes and the use of the ticket system
<li>Logging out support
<li>Support \'optional\' authentication model for Read-Only access without authentication
<li>Rewrite the LDAP and POP3 authentication modules
<li>Support Mail authentication module for multiple POP3 server environment
<li>Support MySQL and PostgreSQL authentication modules, easy to integrate with <a href="http://www.phpbb.com/" target=_blank>phpBB</a>
<li>FTP authentication module support
<li>User information can be extracted from LDAP attributes or Database table fields along with authentication
<li>Standardize the requirements for writing an authentication module
<li>Support FORM style login process other than the original HTTP authentication
<li>More flexible configuration based on a new config.inc.php syntax
<li>Enable interface language to switch without cookie support
<li>Different encodings can be used in different catalogs
<li>Support private groups which only accessible by authenticated user
<li>Fix the E-Mail parsing bug in XOVER command of NNRP library
</ul>
You can view the history since 2002/06 <a href=history.php>here</a>.<p>
PHP News Reader applies <a href=copying.php>GPL</a> license, please view the detail <a href=copying.php>here</a><br>
<p>
Since 2003/01, PHP News Reader hosts on SourceForge:
<blockquote>
<a href="http://sourceforge.net/projects/pnews/" alt="http://sourceforge.net/projects/pnews/" target=_blank>
<img src="http://sourceforge.net/sflogo.php?group_id=71412&amp;type=1" border="0" alt="SourceForge.net Logo"><br>
http://sourceforge.net/projects/pnews/
</a>
</blockquote>
<p>
Sample running system:
<blockquote>
<a href="http://www.csie.nctu.edu.tw/~cdsheen/news/" target=_blank>http://www.csie.nctu.edu.tw/~cdsheen/news/</a>
</blockquote>
<p>
Download from SourceForge:
<blockquote>
<a href="http://prdownloads.sourceforge.net/pnews/pnews-2.0.tgz?download" target=_blank>pnews-2.0.tgz</a>
</blockquote>
<p>
Discussion Forum:
<blockquote>
<a href="https://sourceforge.net/forum/index.php?group_id=71412" target=_blank>https://sourceforge.net/forum/index.php?group_id=71412</a>
</blockquote>
<p>
Author:
<blockquote>
Shen Cheng-Da<br>
Taipei, Taiwan<br>
+886-926-356815<br>
ICQ: <a href="http://wwp.icq.com/scripts/search.dll?to=73013633" title="Add ME">73013633</a><br>
<a href="mailto:cdsheen@csie.nctu.edu.tw">cdsheen@csie.nctu.edu.tw</a><br>
<a target="_blank" href="http://www.csie.nctu.edu.tw/~cdsheen/">
http://www.csie.nctu.edu.tw/~cdsheen/</a>
</blockquote>
<hr>
';
echo "<font size=2>$pnews_claim</font>\n";
echo '
</font>
</body>
</html>
';
?>
