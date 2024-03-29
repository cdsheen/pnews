<?

require_once('../version.inc.php');

$dname = 'pnews-' . str_replace( 'v', '', $pnews_version ) . '.tgz' ;

echo '<html>
<head>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
<LINK REL=STYLESHEET TYPE="text/css" HREF="style.css">
<title>PHP News Reader - A Web-based USENET News Client</title>
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
PHP News Reader
</font>
<hr>
<font face="Georgia" size="3" color="black">
PHP News Reader is a web based News Reader. It supports the 

standard NNTP protocol (<a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=977" target=_blank>RFC 977</a>) for reading, posting, deleting,
forwarding and replying news articles.
<p>
PHP News Reader does not need your PHP installation to be compiled with \'--with-imap\'.
PHP News Reader use a self-written NNRP Library to access news server via NNTP.
And it works just fine without any database installation.
<p>
PHP News Reader does not support threading of News articles and per-user newsgroup subscription.
<p>
Conformed Standards:
<ul>
<li><a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=977" target=_blank>RFC 977 - Network News Transfer Protocol</a>
<li><a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=2980" target=_blank>RFC 2980 - Common NNTP Extensions</a>
<li><a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=2822" target=_blank>RFC 2822 - Internet Message Format</a>
<li><a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=1036" target=_blank>RFC 1036 - Standard for Interchange of USENET Messages</a>
</ul>
<p>
To install PHP News Reader, please check the <a href=requirement.php>requirement</a>, and then read the <a href=guide.php>Installation Guide</a>.
<p>
PHP News Reader has nothing to do with the user registration.
It is designed to work with the EXISTING authentication system.
PHP News Reader supports several flexible authentication interface modules,
which let administrator easy to control how to authenticate users.
There are various handy authentication modules availabled.
<ul>
<li>POP3 - authenticate user using the existing POP3 server.
<li>Mail - authenticate user using multiple POP3 servers.
<!--
<li>Firebird BBS - authenticate user with the existing Firebird Bulletin Board System.
-->
<li>LDAP - authenticate user using LDAP server.
<li>FTP - authenticate user with the existing FTP server
<li>MySQL - authenticate user existing in MySQL database.
<li>PostgreSQL - authenticate user existing in PostgreSQL database.
<li>NNTP - authenticate user using NNTP server. (version >= 2.2.0)
</ul>
<p>
The support for MySQL and PostgreSQL also makes it easy to integrate with the popular <a href="http://www.phpbb.com/" target=_blank>phpBB</a> system.
<p>
The login prompt can be configured as HTTP authentication or FORM style.
<p>
PHP News Reader supports three interface languages (English, Traditional Chinese and Simplified Chinese).
The preferred language can be switched any time and any where.
<p>
Since the PHP iconv() function may not work on some systems and it also has problems of handling Chinese words.
PHP News Reader use a self-written conversion system.
The conversion between Chinese charsets (BIG5, GB2312 and Unicode/UTF-8) is natively supported.
The charset of article is auto-converted to the preferred one
while the selected charset of interface is different from the charset of the news articles.
This conversion also effectives while posting, replying, forwarding and cross-posting articles.
The posted articles will also be converted to the original charset of the server.
<p>
PHP News Reader\'s development started around 2001/08.
I wrote this software in my leisure time. Although PHP News Reader still
lacks many fancy features, it works fine to meet the most requirements.
<p>
PHP News Reader v2.2.0 (2003/05/25)
<ul>
<li>Support news server which requires authorization.
<li>The ANSI coloring codes are filtered by default (<a href=guide.php#filter_ansi_color>$CFG["filter_ansi_color"]</a>).
<li>Support links to Next and Previous articles.
<li>Support URL rewrite function (<a href=guide.php#url_rewrite>$CFG["url_rewrite"]</a> and <a href=guide.php#url_base>$CFG["url_base"]</a>)
<li>Deleted articles are skipped and exactly 20 articles are displayed in one page
<li>Add option (<a href=guide.php#article_order_reverse>$CFG["article_order_reverse"]</a>) to config the article numbering order.
<li>Add option (<a href=guide.php#show_article_popup>$CFG["show_article_popup"]</a>) to config the use of popup window.
<li>Support NNTP authentication module for authenticate with News server.
<li>Number of articles per page is configurable by <a href=guide.php#articles_per_page>$CFG["articles_per_page"]</a> (default is 20).
<li>Fix a bug when post after timeout with "form" style login.
</ul>
You can view the history since 2002/06 <a href=history.php>here</a>.<p>
PHP News Reader applies <a href=copying.php>GPL</a> license, please view the detail <a href=copying.php>here</a><br>
<p>
Since 2003/01, PHP News Reader hosts on SourceForge:
<blockquote>
<a href="http://sourceforge.net/" alt="http://sourceforge.net/" target=_blank>
<img src="http://sourceforge.net/sflogo.php?group_id=71412&amp;type=1" border="0" alt="SourceForge.net Logo">
</a>
</blockquote>
Project Home:
<blockquote>
<a href="http://sourceforge.net/projects/pnews/" title="SourceForge Project: PHP News Reader" target=_blank>
http://sourceforge.net/projects/pnews/
</a>
</blockquote>
<p>
Sample running system:
<blockquote>
<a href="http://www.csie.nctu.edu.tw/~cdsheen/enews/" target=_blank>http://www.csie.nctu.edu.tw/~cdsheen/enews/</a> (in English)<p>
<a href="http://www.csie.nctu.edu.tw/~cdsheen/news/" target=_blank>http://www.csie.nctu.edu.tw/~cdsheen/news/</a>&nbsp; (tw.bbs.*, in Chinese/BIG5)<p>
<a href="http://www.csie.nctu.edu.tw/~cdsheen/cnbbs/" target=_blank>http://www.csie.nctu.edu.tw/~cdsheen/cnbbs/</a>&nbsp; (cn.bbs.*, in Chinese/BIG5, converted from GB2312)<p>
<a href="http://webnews.giga.net.tw/" target=_blank>http://webnews.giga.net.tw/</a>&nbsp; (tw.bbs.*, in Chinese/BIG5, with url_rewrite turning on)
</blockquote>
<p>
Download the latest version from SourceForge:
<blockquote>
';
echo "<a href=\"http://sourceforge.net/project/showfiles.php?group_id=71412\"
target=_blank>Source downloads</a>";
echo '
</blockquote>
<p>
Installation Guide:
<blockquote>
';
echo "<a href=\"guide.php\"
target=_blank>PHP News Reader - Installation and Configuration Guide</a>";
echo '
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
ICQ: <a href="http://wwp.icq.com/scripts/search.dll?to=73013633" title="Add ME">73013633</a><br>
<a href="mailto:cdsheen@users.sourceforge.net">cdsheen@users.sourceforge.net</a><br>
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
