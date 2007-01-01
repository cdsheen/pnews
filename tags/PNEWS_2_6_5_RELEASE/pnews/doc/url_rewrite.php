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
<title>PHP News Reader - URL Rewrite</title>
</head>
<body style="background-color: #EEFFFF">

EOH;

include('header.php');

echo <<<EOH
<div>
<b>URL Rewrite Function</b> (first appeared in PHP News Reader v2.2.0)
<br /> 
<br /> 
URL Rewrite is a cool function that make the link more readable, and this is also known as a search-engine friendly feature.<br />
<br />
For example, the link to read an article is:
<ul><font color=blue>
http://webnews.domain.net/pnews/read.php?server=news.nopy.com&group=nopy.test&artnum=21012
</font>
</ul>
If you enable the URL rewriting, the link will become:
<ul><font color=blue>
http://webnews.domain.net/pnews/article/news.nopy.com/nopy.test/21012
</font>
</ul>
With pnews version greater than v2.2.1 and if <i><b>news.nopy.com</b></i> is your <u>default</u> news server, the link even becomes shorter:
<ul><font color=blue>
http://webnews.domain.net/pnews/article//nopy.test/21012
</font>
</ul>
<p>PHP News Reader includes an access control file ".htaccess" to do the transformation for you.</p>
To enable URL Rewrite, you need:
<ul>
<li>Apache web server with "mod_rewrite" enabled
<li>Valid setting for AllowOverride at the program's directory in httpd.conf
<li>Setting <a href=guide.php#url_rewrite>\$CFG["url_rewrite"]</a> to <b>true</b> in <b>config.inc.php</b>
</ul>
For more information about Apache's mod_rewrite, visit <a href="http://httpd.apache.org/docs/misc/rewriteguide.html" target=_blank>http://httpd.apache.org/docs/misc/rewriteguide.html</a> for details.
<p>
Check the <a href=guide.php>Installation Manual</a> for other configuration parameters.
</div>
EOH;
	include('tailer.php');
?>
</body>
</html>
