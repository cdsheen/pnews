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
<title>PHP News Reader</title>
</head>
<body style="background-color: #EEFFFF">
<table width=100% cellpadding=0 cellspacing=0><tr><td>
<font face="Georgia"><h3>$pnews_name $pnews_version</h3></font>
</td><td align=right valign=bottum>
<font face="Georgia" size=1>Release Date: $pnews_release</font>
</td></tr></table>
<font size=3 color=black face="Georgia">
Requirement
<hr>
The requirement for PHP News Reader on the server side (news server) :
<ul>
<li>Web Server with PHP modules installed ( PHP 4.1.0 or greater )
<li>News Server with full <a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=977" target=_blank>RFC 977</a> Implementation
<li>News Server with <a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=2980" target=_blank>RFC 2980</a> Extension
</ul>
The requirement for using NNTPS (SNEWS) News Server or using NNTPS as authentication method:
<ul>
<li>PHP 4.3.0 or greater
<li>PHP module with OpenSSL support
</ul>
<p>The standard PHP module works just fine, IMAP or ICONV support is not necessary.<br />
The MySQL/PostgreSQL/LDAP support for PHP module is only required if you use the corresponding authentication module.
<p>As for <a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=2980" target=_blank>RFC 2980</a>, PHP News Reader requires the News Server to implement the following extensions:<br />
<ul>
<li>LIST &nbsp;ACTIVE &nbsp;[wildmat]
<li>LIST &nbsp;NEWSGROUPS  &nbsp;[wildmat]
<li>XOVER &nbsp;[range]
</ul>
The <a href="http://www.isc.org/products/INN/" target=_blank>INN News Server</a> package
 (with the version greater than <b><a href="http://www.isc.org/products/INN/">INN 1.5</a></b>) includes a NNRPD daemon to support this.
<p>
If your server is operated by INN, you should check about the NNRP access permission.
For example, when you telnet to an INN News server at port 119, you must get response like this: (note the <b>NNRP</b> in bold)
<ul>
200 nopy.com InterNetNews <b>NNRP</b> server INN 2.3.2 ready (posting ok).
</ul>
instead of,
<ul>
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
<hr><table width=100% cellspacing=0 cellpadding=0><tr><td><font size=2>$pnews_claim</font><br />
<a href=http://sourceforge.net/projects/pnews/ target=_blank>http://sourceforge.net/projects/pnews/</a>
</td><td align=right>
<a href="http://sourceforge.net/" alt="http://sourceforge.net/" target=_blank>
<img src="$sflogo" border="0" alt="SourceForge.net Logo">
</a>
</td></tr></table>
</font>
</body>
</html>

EOH;
?>
