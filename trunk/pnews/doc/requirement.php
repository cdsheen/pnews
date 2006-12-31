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

echo <<<EOH
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5" />
<LINK REL=STYLESHEET TYPE="text/css" HREF="style.css" />
<title>PHP News Reader</title>
</head>
<body style="background-color: #EEFFFF">

EOH;

include('header.php');

echo <<<EOH
<div>
The requirement for PHP News Reader on the server side (news server) :
<ul>
<li>Web Server with PHP support installed ( PHP 4.1.0 or greater )
<li>News Server with full <a href="http://rfc.giga.net.tw/rfc977" target=_blank>RFC 977</a> Implementation
<li>News Server with <a href="http://rfc.giga.net.tw/rfc2980" target=_blank>RFC 2980</a> Extension
</ul>
The requirement for using NNTPS (SNEWS) News Server or using NNTP/FTP/POP3 over SSL as your authentication method:
<ul>
<li>PHP 4.3.0 or greater
<li>PHP with <a href=http://www.php.net/manual/en/ref.openssl.php target=_blank>OpenSSL extension</a> enabled
</ul>
The requirement for enabling the threading support:
<ul>
<li>PHP with <a href=http://www.php.net/manual/en/ref.dba.php target=_blank>DBA extension</a> enabled
</ul>
The MySQL/PostgreSQL/LDAP extension for PHP module is only required if you use the corresponding authentication module.
<p>As for <a href="http://rfc.giga.net.tw/rfc2980" target=_blank>RFC 2980</a>,
PHP News Reader requires the News Server to implement the following extensions:<br />
<ul>
<li>LIST &nbsp;ACTIVE &nbsp;[wildmat] &nbsp;&nbsp;<i>(required)</i>
<li>XOVER &nbsp;[range] &nbsp;&nbsp;<i>(required)</i>
<li>LIST &nbsp;OVERVIEW.FMT  &nbsp;&nbsp;<i>(optional but recommended)</i>
<li>LIST &nbsp;NEWSGROUPS  &nbsp;[wildmat] &nbsp;&nbsp;<i>(optional)</i>
</ul>
The <a href="http://www.isc.org/products/INN/" target=_blank>INN News Server</a> package
 (with the version greater than <b><a href="http://www.isc.org/products/INN/">INN 1.5</a></b>) includes a NNRPD daemon to support this.
<p>
If your server is operated by INN, you can verify the NNRP access permission by hand.<br />
For example, when you telnet to an INN News server at port 119, you must get response like this:
<ul>
200 nopy.com InterNetNews NNRP server INN 2.3.2 ready (posting ok).
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
</div>
EOH;
	include('tailer.php');
?>
</body>
</html>
