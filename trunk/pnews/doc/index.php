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

preg_match( '/^v(\d+)\.(\d+)\.(\d+)$/', $pnews_version, $ver );

$dname = 'pnews-' . $ver[1] . $ver[2] . $ver[3] . '.tgz' ;

if( isset($_SERVER['HTTPS']) )
	$sflogo = 'https://sourceforge.net/sflogo.php?group_id=71412&amp;type=1';
else
	$sflogo = 'http://sourceforge.net/sflogo.php?group_id=71412&amp;type=1';

echo <<<EOR
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
 <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5" />
 <LINK REL=STYLESHEET TYPE="text/css" HREF="style.css" />
 <title>PHP News Reader - A Web-based USENET News Client</title>
</head>
<body style="background-color: #EEFFFF">

EOR;

include('header.php');

echo "<div><div>";
if( file_exists($adfile) )
	@include($adfile);
echo <<<EOR
<h4>PHP News Reader is a web-based News Reader.</h4>
It supports the standard NNTP protocol (<a href="http://rfc.giga.net.tw/rfc977" target=_blank>RFC 977</a>) for reading, posting, deleting,<br />
forwarding and replying news articles.
<p>
<b>Features:</b>
<ul>
<li>Read/Post/Reply/Crosspost/Forward/Delete articles to/from News server(s).
<li>Multiple News server and multiple categories of news groups.
<li>Support NNTP over SSL (NNTPS) and NNTP authentication.
<li>Posting and downloading for uuencoded attachment.
<li>Easy to install, neither database access nor IMAP is required.
<li>Authentication is easily configured to work with your existing system.
<li>Multiple language interface and Traditional/Simplified Chinese coding conversion.
</ul>
<p>
</div>
<b>Technical Standards:</b>
<ul>
<li><a href="http://rfc.giga.net.tw/rfc977" target=_blank>RFC 977 - Network News Transfer Protocol</a>
<li><a href="http://rfc.giga.net.tw/rfc2980" target=_blank>RFC 2980 - Common NNTP Extensions</a>
<li><a href="http://rfc.giga.net.tw/rfc2822" target=_blank>RFC 2822 - Internet Message Format</a>
<li><a href="http://rfc.giga.net.tw/rfc1036" target=_blank>RFC 1036 - Standard for Interchange of USENET Messages</a>
</ul>
<p>
To install PHP News Reader, please check the <a href=requirement.php>Requirements</a>, and then read the <a href=guide.php>Installation Guide</a>.
<p>
PHP News Reader has nothing to do with the user registration.
It is designed to work with the EXISTING authentication system.
PHP News Reader supports several flexible authentication modules,
which let administrator easy to control how to authenticate users.
There are various built-in authentication modules.
<ul>
<li>POP3 - authenticate user using the existing POP3 server.
<li>Mail - authenticate user using multiple POP3/POP3S servers.
<!--
<li>Firebird BBS - authenticate user with the existing Firebird Bulletin Board System.
-->
<li>LDAP - authenticate user using LDAP server.
<li>FTP - authenticate user with the existing FTP server
<li>MySQL - authenticate user existing in MySQL database.
<li>PostgreSQL - authenticate user existing in PostgreSQL database.
<li>NNTP - authenticate user using NNTP server. (version >= 2.2.1)
<li>NNTPS - authenticate user using NNTP over SSL server. (version >= 2.4.0)
<li>POP3S - authenticate user using POP3 over SSL server. (version >= 2.4.1)
<li>FTPS - authenticate user using FTP over SSL server. (version >= 2.4.1)
<li>CAS - authenticate user using <a href=http://www.yale.edu/tp/auth target=_blank>CAS</a>. (version >= 2.3.0)
<li>phpBB - authenticate user with the existing phpBB bulletin board system (version >= 2.5.6)
</ul>
<p>
And it is easy to write your authentication module to be used by PHP News Reader.
<p>
The login prompt can be configured as <u>HTTP</u> (as a popup window) or <u>FORM</u> (preferred) style.<br />
Besides, if you already login on <u>phpBB</u>, one can also utilize the session without login again.
<p>
PHP News Reader supports multiple interface languages, including English, Traditional Chinese, Simplified Chinese, Fran&ccedil;ais, Finnish, German, Italiano and Slovak.
The preferred language of client's browser will be considered as the default language.
And this can be switched any time and any where.
<p>
PHP News Reader also supports the on-the-fly coding conversion between Traditional Chinese (used in Taiwan) and Simplified Chinese (used in Mainland China).
<p>
The PHP iconv() function may not work on some old systems
and it can not handle the conversion between Traditional and Simplified Chinese.
So PHP News Reader use a self-implemented conversion system.
The conversion between Chinese charsets (BIG5, GB2312 and Unicode/UTF-8) is natively supported.
The charset of article is auto-converted to the preferred one
while the selected charset of interface is different from the charset of the news articles.
This conversion also effectives while posting, replying, forwarding and cross-posting articles.
The posted articles will also be converted to the original charset of the news group.
<p>
PHP News Reader's development started around August 2001.
And since 2005, I can only afford time to support the limited bug fixes and minor improvements.
It needs a completely rewrite to support more advanced features.
<p>
The latest version of PHP News Reader is <b>$pnews_version</b>.
EOR;

if( $ver[3] != 0 )
	echo " Here is the release notes since <b>v{$ver[1]}.{$ver[2]}.0</b>:\n";

echo "<p>\n";

$fp = fopen('history.php', 'r');
while( $buf = fgets( $fp, 255 ) ) {
	if( preg_match( "/^<b>PHP News Reader $pnews_version/", $buf ) )
		break;
}
echo $buf;
while( $buf = fgets( $fp, 255 ) ) {
	if( preg_match( "/^<b>PHP News Reader v/", $buf ) ) {
		if( !strstr( $buf, 'v' . $ver[1] . '.' . $ver[2] . '.' ) )
			break;
	}
	echo str_replace('\\$', '$', $buf);
}
fclose($fp);

echo <<<EOR
The whole change log is available <a href=history.php>here</a>.
<p>
PHP News Reader applies <a href=copying.php>GPL</a> license, <a href=copying.php>click here</a> for a reference.<br />
<p>
You are free to use and/or modify PHP News Reader under the <a href=copying.php>GPL</a> license.<br />
And I am very appreciated if you share your comments and modification with me.
<p>
<b>Acknowledgement:</b>
<blockquote>
Many people help the development of PHP News Reader, <a href=acknowlege.php>click here</a> to know their contributions.
</blockquote>
<b>Project Home:</b>
<blockquote>
<a href="http://sourceforge.net/projects/pnews/" title="SourceForge Project: PHP News Reader" target=_blank>
http://sourceforge.net/projects/pnews/
</a>
<br />
<br />
Project Hosts on SourceForge:
<blockquote>
<a href="http://sourceforge.net/" alt="http://sourceforge.net/" target=_blank>
<img src="$sflogo" border="0" alt="SourceForge.net">
</a>
</blockquote>
</blockquote>
<p>
<b>Demonstration:</b>
<blockquote>
<a href="http://enews.urdada.net/" target=_blank>http://enews.urdada.net/</a>&nbsp; (in English)<br />
<a href="http://webnews.urdada.net/" target=_blank>http://webnews.urdada.net/</a>&nbsp; (tw.bbs.*, in Chinese/BIG5, with <a href="guide.php#url_rewrite"><b>url_rewrite</b></a> turning on)<br />
</blockquote>
<p>
<b>Downloads:</b>
<blockquote>
Download the latest version from SourceForge:
<a href="http://sourceforge.net/project/showfiles.php?group_id=71412" target=_blank>Source downloads</a>
<br />
<br />
The latest version is also available from <a href=http://subversion.tigris.org target=_blank>Subversion</a>:<br /><br />
# svn co https://pnews.svn.sourceforge.net/svnroot/pnews/trunk/pnews
</blockquote>
<p>
<b>Installation Guide:</b>
<blockquote>
<a href="guide.php">PHP News Reader - Installation and Configuration Guide</a>
</blockquote>
<p>
<b>Forum:</b>
<blockquote>
<a href="https://sourceforge.net/forum/index.php?group_id=71412" target=_blank>https://sourceforge.net/forum/index.php?group_id=71412</a>
</blockquote>
<p>
<b>Donation:</b>
<blockquote>
If you like PHP News Reader, and think it useful for your work, or even making profit from PHP News Reader, you can support me by donating money. But this is *NOT* required. Even without any donation, you can still use PHP News Reader under GPL license.<br />
<br />
<table>
<tr><td>
<a href="http://sourceforge.net/donate/index.php?group_id=71412" target=_blank><img src=https://sourceforge.net/images/project-support.jpg alt="Support This Project" /></a>
</td><td valign=middle> &nbsp;
Make a donation: <a href="http://sourceforge.net/donate/index.php?group_id=71412" target=_blank>http://sourceforge.net/donate/index.php?group_id=71412</a>
</td></tr></table>
</blockquote>
<p>
<b>Author:</b>
<blockquote>
Shen Cheng-Da<br />
Taipei, Taiwan<br />
<script type="text/javascript">
	document.write("cdsheen" + "&#64;" + "users&#46;sourceforge&#46;net");
</script>
<br />
<a target="_blank" href="http://www.csie.nctu.edu.tw/~cdsheen/">
http://www.csie.nctu.edu.tw/~cdsheen/</a>
</blockquote>
</div>
EOR;
	include('tailer.php');
?>
</body>
</html>
