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

include('utils.inc.php');

# -------------------------------------------------------------------

$artnum = $_GET['artnum'];
$filename = $_GET['filename'];

$c = check_group( $server, $group );

if( ! ( $nnrp->open( $server, $news_nntps[$c] ) && nnrp_authenticate() ) )
	connect_error( $server );

list( $code, $count, $lowmark, $highmark ) = $nnrp->group( $group );

$artinfo = $nnrp->head( $artnum, $news_charset[$curr_category], $CFG['time_format'] );

if( !$artinfo ) {
	echo "unable to download this attachement<br>\n";
	exit;
}

list($attach, $size) = $nnrp->get_attachment( $artnum, $_GET['type'], $filename );

$nnrp->close();

$mimetype = array( 'doc'  => 'application/msword',
		   'htm'  => 'text/html',
		   'html' => 'text/html',
		   'gif'  => 'image/gif',
		   'png'  => 'image/png',
		   'jpg'  => 'image/jpeg',
		   'jpeg' => 'image/jpeg',
		   'bmp'  => 'image/bmp',
		   'pdf'  => 'application/pdf',
		   'txt'  => 'text/plain',
		   'rtf'  => 'text/rtf',
		   'mpg'  => 'video/mpeg',
		   'mpeg' => 'video/mpeg',
		   'mov'  => 'video/quicktime',
		   'avi'  => 'video/x-msvideo',
		   'mp3'  => 'audio/mpeg',
		   'mid'  => 'audio/midi',
		   'wav'  => 'audio/x-wav',
		   'zip'  => 'application/zip',
		   'tar'  => 'application/x-tar',
		   'ppt'  => 'application/vnd.ms-powerpoint',
		   'xls'  => 'application/vnd.ms-excel' ); 

if( $attach ) {

	$ext = strtolower(substr( $filename, strrpos( $filename, '.') + 1));

#	header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
#	header( 'Expires: 0' );
	header( 'Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

#	header( 'Cache-Control: no-store, no-cache, must-revalidate');	// HTTP/1.1
#	header( 'Cache-Control: post-check=0, pre-check=0', false);
#	header( 'Pragma: no-cache' );		// HTTP/1.0
#	header( 'Cache-Control: private' );

	header( 'Accept-Ranges: bytes' );
	header( 'Content-Length: ' . $size );
	header( 'Content-Transfer-Encoding: binary' );

	if( ini_get('zlib.output_compression') )
		ini_set( 'zlib.output_compression', 'Off' );

#	if( strstr( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) )
#		$dtype = 'inline';
#	else
		$dtype = 'attachment';

	if( isset($mimetype[$ext]) ) {
		header( 'Content-Type: ' . $mimetype[$ext] );
		header( "Content-Disposition: $dtype; filename=\"$filename\"" );
	}
	else {
		header( 'Content-Type: application/octet-stream' );
		header( "Content-Disposition: $dtype; filename=\"$filename\"" );
	}
	if( !$CFG['cache_dir'] )
		print $attach;
	elseif( file_exists( $attach ) )
		@readfile( $attach );
}
else {
	echo "no such attachment<br>\n";
}

exit;

?>
