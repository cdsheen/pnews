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

if( $_SERVER['HTTPS'] )
	$sflogo = 'https://sourceforge.net/sflogo.php?group_id=71412&amp;type=1';
else
	$sflogo = 'http://sourceforge.net/sflogo.php?group_id=71412&amp;type=1';

echo <<<EOR
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
 <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
 <LINK REL=STYLESHEET TYPE="text/css" HREF="style.css">
 <title>PHP News Reader - A Web-based USENET News Client</title>
</head>
<body style="background-color: #EEFFFF">

<table width=100% cellpadding=0 cellspacing=0>
<tr>
 <td>
 <font face="Georgia"><h3>$pnews_name $pnews_version</h3></font>
 </td>
 <td align=right valign=bottum>
 <font face="Georgia" size=1>Release Date: $pnews_release</font>
 </td></tr>
</table>

<font size=3 color=black face="Georgia">
PHP News Reader
</font>
<hr>
<font face="Georgia" size="3" color="black">
PHP News Reader is a web based News Reader.
It supports the standard NNTP protocol (<a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=977" target=_blank>RFC 977</a>) for reading, posting, deleting,
forwarding and replying news articles.
<p>
PHP News Reader does NOT require your PHP installation to be compiled with IMAP support.
PHP News Reader use a self-written NNRP Library to access news server via NNTP.
And it works just fine without any database installation.
<p>
PHP News Reader does not support threading of News articles and per-user newsgroups subscription.
<p>
Conformed Standards:
<ul>
<li><a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=977" target=_blank>RFC 977 - Network News Transfer Protocol</a>
<li><a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=2980" target=_blank>RFC 2980 - Common NNTP Extensions</a>
<li><a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=2822" target=_blank>RFC 2822 - Internet Message Format</a>
<li><a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=1036" target=_blank>RFC 1036 - Standard for Interchange of USENET Messages</a>
</ul>
<p>
To install PHP News Reader, please check the <a href=requirement.php>Requirements</a>, and then read the <a href=guide.php>Installation Guide</a>.
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
<li>NNTP - authenticate user using NNTP server. (version >= 2.2.1)
</ul>
<p>
The support for MySQL and PostgreSQL also makes it easy to integrate with the popular <a href="http://www.phpbb.com/" target=_blank>phpBB</a> system.
<p>
And it is easy to write your authentication module to be used by PHP News Reader.
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
The posted articles will also be converted to the charset of the original article in the server.
<p>
PHP News Reader's development started around August 2001.
I wrote this software in my leisure time, mostly in the weekend. Although PHP News Reader still
lacks many fancy features, it works fine to meet the basic requirements.
<p>
The latest version of PHP News Reader is <b>$pnews_version</b>, here is the release notes:
<p>
EOR;

$fp = fopen('history.php', 'r');
while( $buf = fgets( $fp, 255 ) ) {
	if( preg_match( "/^PHP News Reader/", $buf ) )
		break;
}
echo $buf;
while( $buf = fgets( $fp, 255 ) ) {
	echo str_replace('\\$', '$', $buf);
	if( rtrim($buf) == "</ul>" )
		break;
}
fclose($fp);

echo <<<EOR
You can view the history since June 2002 by <a href=history.php>clicking here</a>.<p>
PHP News Reader applies <a href=copying.php>GPL</a> license, <a href=copying.php>clicking here</a> for a reference<br>
<p>
Since January 2003, PHP News Reader hosts on SourceForge:
<blockquote>
<a href="http://sourceforge.net/" alt="http://sourceforge.net/" target=_blank>
<img src="$sflogo" border="0" alt="SourceForge.net Logo">
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
<a href="http://sourceforge.net/project/showfiles.php?group_id=71412" target=_blank>Source downloads</a>
</blockquote>
<p>
Installation Guide:
<blockquote>
<a href="guide.php">PHP News Reader - Installation and Configuration Guide</a>
</blockquote>
<p>
Anonymous access to CVS Repository (read only):
<blockquote>
# <font color=green>cvs -d:pserver:anonymous@cvs.sourceforge.net:/cvsroot/pnews login</font><br>
Logging in to :pserver:anonymous@cvs.sourceforge.net:2401/cvsroot/pnews<br>
CVS password: <font color=orange>(Press Enter)</font><br>
# <font color=green>cvs -z3 -d:pserver:anonymous@cvs.sourceforge.net:/cvsroot/pnews co pnews</font><br>
cvs server: Updating pnews<br>
...
</blockquote>
<p>
CVS is also available online:
<blockquote>
<a href="http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/pnews/pnews/" target=_blank>View CVS Repository on the Web</a><br>
<a href="http://cvs.sourceforge.net/cvstarballs/pnews-cvsroot.tar.gz">Nightly CVS Tarball (pnews-cvsroot.tar.gz)</a>
</blockquote>
<p>
Forum:
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
<font size=2>$pnews_claim</font>
</font>
</body>
</html>

EOR;

?>
