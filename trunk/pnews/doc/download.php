<?

# PHP News Reader
# Copyright (C) 2001-2005 Shen Cheng-Da
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
 <title>PHP News Reader - Download</title>
</head>
<body style="background-color: #EEFFFF">

EOR;

include('header.php');

echo <<<EOR
<font face=Georgia size=3>
Project Home:
<blockquote>
<a href="http://sourceforge.net/projects/pnews/" title="SourceForge Project: PHP News Reader" target=_blank>
http://sourceforge.net/projects/pnews/
</a>
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
# <font color=green>cvs -d:pserver:anonymous@cvs.sourceforge.net:/cvsroot/pnews login</font><br />
Logging in to :pserver:anonymous@cvs.sourceforge.net:2401/cvsroot/pnews<br />
CVS password: <font color=orange>(Press Enter)</font><br />
# <font color=green>cvs -z3 -d:pserver:anonymous@cvs.sourceforge.net:/cvsroot/pnews co pnews</font><br />
cvs server: Updating pnews<br />
...
</blockquote>
<p>
CVS is also available online:
<blockquote>
<a href="http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/pnews/pnews/" target=_blank>View CVS Repository on the Web</a><br />
<a href="http://cvs.sourceforge.net/cvstarballs/pnews-cvsroot.tar.bz2">Nightly CVS Tarball (pnews-cvsroot.tar.bz2)</a>
</blockquote>
<p>
Forum:
<blockquote>
<a href="https://sourceforge.net/forum/index.php?group_id=71412" target=_blank>https://sourceforge.net/forum/index.php?group_id=71412</a>
</blockquote>
<hr />
<font size=2>$pnews_claim</font>
</font>
</body>
</html>

EOR;

?>
