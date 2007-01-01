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

echo <<<EOR
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
 <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5" />
 <LINK REL=STYLESHEET TYPE="text/css" HREF="style.css" />
 <title>PHP News Reader - Download</title>
</head>
<body style="background-color: #EEFFFF">

EOR;

include('header.php');

echo "<div>";

if( file_exists($adfile) )
          @include($adfile);

echo <<<EOR
<b>Project Home:</b>
<blockquote>
<a href="http://sourceforge.net/projects/pnews/" title="SourceForge Project: PHP News Reader" target=_blank>
http://sourceforge.net/projects/pnews/
</a>
</blockquote>
<p>
<b>Downloads:</b>
<blockquote>
<a href="http://sourceforge.net/project/showfiles.php?group_id=71412" target=_blank>http://sourceforge.net/project/showfiles.php?group_id=71412</a>
<br />
<br />
The latest version is also available from <a href=http://subversion.tigris.org target=_blank>Subversion</a>:
<br />
<br />
# <i>svn co https://pnews.svn.sourceforge.net/svnroot/pnews/trunk/pnews</i>
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
</div>
EOR;
	include('tailer.php');
?>
</body>
</html>
