<?

# PHP News Reader / PHPBB Authentication Module
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

# Basic tutorial on how to keep sessions going across phpBB and your site 
# Author: A_Jelly_Doughnut 
# URL: http://www.phpbb.com/kb/article.php?article_id=143

$phpbb_root_path = $CFG['auth_phpbb_path'];

define( 'IN_PHPBB', true );

include($CFG['auth_phpbb_path'] . 'extension.inc');
include($CFG['auth_phpbb_path'] . 'common.'.$phpEx);

// 
// Start session management 
// 
$phpbb_userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($phpbb_userdata);
// 
// End session management 
//

#print_r($phpbb_userdata);

function auth_already_login() {
	global $phpbb_userdata;
	return( $phpbb_userdata['session_logged_in'] );
}

function auth_show_login_page( $target_url ) {
	global $CFG, $phpEx;
	header( 'Location: ' .  $CFG['auth_phpbb_url_base'] . 'login.' . $phpEx . '?redirect=' . $target_url );
	exit;
}

function auth_logout() {
	global $phpbb_userdata;
	if( $phpbb_userdata['session_logged_in'] ) {
		session_end($userdata['session_id'], $phpbb_userdata['user_id']);
	}
}

function auth_get_name() {
	global $phpbb_userdata;
	return( $phpbb_userdata['username'] );
}

function auth_get_email() {
	global $phpbb_userdata;
	return( $phpbb_userdata['user_email'] );
}

?>
