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

include('utils.inc.php');

# -------------------------------------------------------------------

$artnum = $_GET['artnum'];
$filename = $_GET['filename'];

$c = check_group( $server, $group );

$nhd = nnrp_open( $server, $news_nntps[$c] );

if( ! ( $nhd && nnrp_authenticate( $nhd ) ) )
	connect_error( $server );

list( $code, $count, $lowmark, $highmark ) = nnrp_group( $nhd, $group );

$artinfo = nnrp_head( $nhd, $artnum, $news_charset[$curr_catalog], $CFG['time_format'] );

if( !$artinfo ) {
	echo "unable to download this attachement<br>\n";
	exit;
}

$binary = nnrp_get_attachment( $nhd, $artnum, $_GET['type'], $filename );
$size = strlen($binary);

nnrp_close($nhd);

$mimetype = array( 'doc'  => 'application/msword',
		   'htm'  => 'text/html',
		   'html' => 'text/html',
		   'gif'  => 'image/gif',
		   'png'  => 'image/png',
		   'jpg'  => 'image/jpeg',
		   'pdf'  => 'application/pdf',
		   'txt'  => 'text/plain',
		   'rtf'  => 'text/rtf',
		   'mpg'  => 'video/mpeg',
		   'mpeg' => 'video/mpeg',
		   'avi'  => 'video/x-msvideo',
		   'mp3'  => 'audio/mpeg',
		   'mid'  => 'audio/midi',
		   'wav'  => 'audio/x-wav',
		   'zip'  => 'application/zip',
		   'tar'  => 'application/x-tar',
		   'ppt'  => 'application/vnd.ms-powerpoint',
		   'xls'  => 'application/vnd.ms-excel' ); 

if( $binary ) {
	$ext = substr( $filename, strrpos( $filename, '.') + 1);
	header( 'Accept-Ranges: bytes' );
	header( 'Content-Length: ' . $size );
	header( 'Content-transfer-encoding: binary' );
	header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	if( isset($mimetype[$ext]) ) {
		header( 'Content-Type: ' . $mimetype[$ext] );
		header( "Content-Disposition: inline; filename=\"$filename\"" );
	}
	else {
		header( 'Content-Type: application/octet-stream' );
		header( "Content-Disposition: attachment; filename=\"$filename\"" );
	}
	print $binary;
}
else {
	echo "no such attachment<br>\n";
}

exit;

?>
