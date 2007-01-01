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
<blockquote>
<table>
<tr><td align=left>Pascal Aubry from France</td><td> - French translation, CAS authentication and phpCAS library.</td></tr>
<tr><td align=left>Markus Oversti from Finland</td><td> - Finnish translation.</td></tr>
<tr><td align=left>Czz from China</td><td> - Help to refine the Chinese (GB2312) translation.</td></tr>
<tr><td align=left>Jochen Staerk from Germany</td><td> - German translation.</td></tr>
<tr><td align=left>Francesco Rolando from Italian</td><td> - Italian translation.</td></tr>
<tr><td align=left>Tichu from Slovakia</td><td> - Slovak translation.</td></tr>
</table>
</blockquote>
</div>
EOH;
	include('tailer.php');
?>
</body>
</html>
