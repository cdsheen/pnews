<?
	include_once('../version.inc.php');

	$menus = array( 'index.php' => 'Introduction',
			'guide.php' => 'Documentation',
			'history.php' => 'Changes',
			'download.php' => 'Download' );

	$titles = array( 'index.php' => 'PHP News Reader - Introduction',
			'guide.php' => 'PHP News Reader - Installation and Configuration Documentation',
			'history.php' => 'PHP News Reader - Release notes and Histroy',
			'download.php' => 'PHP News Reader - Download' );

	echo <<<REL
<table width=100% cellpadding=0 cellspacing=0>
<tr>
 <td>
 <font face="Georgia"><h3>$pnews_name $pnews_version</h3></font>
 </td>
 <td align=right valign=bottum>
 <font face="Georgia" size=1>Release Date: $pnews_release</font>
 </td></tr>
</table>
REL;
	$curr_scr = basename($_SERVER['PHP_SELF']);
//	echo $curr_scr;
	$title = $titles[$curr_scr];

	echo "<table width=100%>\n<tr><td align=left><font size=3 face=Georgia>$title</font>";
	echo "</td><td align=right>\n";
	echo "<table cellpadding=0 cellspacing=1>\n<tr><td bgcolor=black>";
	echo "<table cellpadding=3 cellspacing=2 style='font-family: Georgia'>\n";
	echo "<tr bgcolor=white>\n";
#	echo "<td><font size=3 color=black>PHP News Reader</font></td>\n";
	foreach( $menus as $scr => $menu ) {
		if( $curr_scr == $scr )
			echo "<td align=25% bgcolor=black><font size=3 color=white>$menu</font></td>\n";
		else
			echo "<td align=25%><a href=$scr><font size=3>$menu</font></a></td>\n";
	}
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td></tr></table>\n";
	echo "</td></tr></table>\n<hr />\n";
?>
