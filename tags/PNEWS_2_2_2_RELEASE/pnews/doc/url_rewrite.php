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

echo '<html>
<head>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
<LINK REL=STYLESHEET TYPE="text/css" HREF="../style.css">
<title>PHP News Reader - URL Rewrite</title>
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
URL Rewrite Function
<hr>
URL Rewrite is a cool function that make the link more readable.
For example, the link to read an article is:
<ul><font color=blue>
http://webnews.domain.net/news/read-art.php?server=news.nopy.com&group=nopy.test&artnum=21012
</font>
</ul>
If you enable the URL rewriting, the link will become:
<ul><font color=blue>
http://webnews.domain.net/news/article/news.nopy.com/nopy.test/21012
</font>
</ul>
If <i><b>news.nopy.com</b></i> is your default news server, the link even becomes shorter (v2.2.1):
<ul><font color=blue>
http://webnews.domain.net/news/article//nopy.test/21012
</font>
</ul>
To enable URL Rewrite, you need:
<ul>
<li>Apache web server with "mod_rewrite" enabled
<li>Correct setting for AllowOverride at the program\'s directory in httpd.conf
</ul>
For more information about Apache\'s mod_rewrite, visit <a href="http://httpd.apache.org/docs/misc/rewriteguide.html" target=_blank>http://httpd.apache.org/docs/misc/rewriteguide.html</a> for details.
<p>
Check the <a href=guide.php>Installation Manual</a> for other configuration parameters.
';

echo "<hr><table width=100% cellspacing=0 cellpadding=0><tr><td><font size=2>$pnews_claim</font><br>\n";
echo "<a href=http://sourceforge.net/projects/pnews/ target=_blank>http://sourceforge.net/projects/pnews/</a>\n";
echo '
</td><td align=right>
<a href="http://sourceforge.net/" alt="http://sourceforge.net/" target=_blank>
<img src="$sflogo" border="0" alt="SourceForge.net Logo">
</a>
</td></tr></table>
</font>
</body>
</html>
';
?>
