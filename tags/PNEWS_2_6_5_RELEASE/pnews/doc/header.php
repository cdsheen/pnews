<?

include_once('../version.inc.php');

$menus = array( 'index.php' => 'Introduction',
    		'requirement.php' => 'Requirement',
		'guide.php' => 'Documentation',
		'history.php' => 'Changes',
		'download.php' => 'Download' );

$titles = array( 'index.php' => 'PHP News Reader - Introduction',
		'guide.php' => 'PHP News Reader - Installation and Configuration Documentation',
		'history.php' => 'PHP News Reader - Release notes',
		'download.php' => 'PHP News Reader - Download',
		'requirement.php' => 'PHP News Reader - Requirement',
		'acknowlege.php' => 'PHP News Reader - Acknowlegement',
		'url_rewrite.php' => 'PHP News Reader - URL Rewriting with Apache/mod_rewrite' );

echo <<<REL
<table width=100% cellpadding=0 cellspacing=0>
<tr>
 <td>
 <font face="Georgia" size=4><b>$pnews_name</b></font>
<br />
<i>Web-based USENET News Reader</i>
<br />
<br />
 </td>
 <td align=right valign=top>
 <font face="Verdana" size=1>Release Date: $pnews_release</font>
 </td></tr>
</table>
REL;
	$curr_scr = basename($_SERVER['PHP_SELF']);
	$adfile = 'ad-'.$curr_scr;
//	echo $curr_scr;
	$title = $titles[$curr_scr];

	echo "<table width=100% cellpadding=0 cellspacing=0>\n<tr><td align=left><font size=3 face=Georgia>$title</font>";
	echo "</td><td align=right>\n";
	echo "<table cellpadding=0 cellspacing=1>\n<tr><td bgcolor=black>";
	echo "<table cellpadding=3 cellspacing=2 style='font-family: Verdana'>\n";
	echo "<tr bgcolor=white>\n";
#	echo "<td><font size=3 color=black>PHP News Reader</font></td>\n";
	foreach( $menus as $scr => $menu ) {
		if( $curr_scr == $scr )
			echo "<td align=25% bgcolor=black><font size=2 color=white>$menu</font></td>\n";
		else
			echo "<td align=25% bgcolor=#FFFFA0 onMouseover='this.bgColor=\"#CCCCFF\"' onMouseout='this.bgColor=\"#FFFFA0\"'><a class=link href=$scr><font size=2>$menu</font></a></td>\n";
	}
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td></tr></table>\n";
	echo "</td></tr></table>\n<div class=hr></div>\n";
?>
