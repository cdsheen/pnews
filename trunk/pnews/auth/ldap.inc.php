<?

# PHP News Reader / LDAP Authentication Module
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

function check_user_password( $username, $password ) {

	global $CFG;

	if( !isset( $CFG['ldap_server'], $CFG['ldap_dn'], $CFG['ldap_bind_rdn'], $CFG['ldap_bind_pwd'], $CFG['ldap_filter'] ) )
		return(null);

	$auth_ok = 0;

	$server = $CFG['ldap_server'];
	if( strstr( $server, ':' ) )
		list( $server, $port ) = split( ':', $server );
	else
		$port = 389;

	$ds = @ldap_connect( $server, $port );
	if( $ds ) {
		if( @ldap_bind( $ds,
				sprintf( str_replace( '%u', '%s', $CFG['ldap_bind_rdn'] ), $username ),
				sprintf( str_replace( '%p', '%s', $CFG['ldap_bind_pwd'] ), $password ) ) == true ) {
			$attrs = array();
			if( isset($CFG['ldap_variable']) )
				foreach( $CFG['ldap_variable'] as $var => $attr )
					$attrs[] = $attr;
			$filter_str = sprintf( str_replace( '%u', '%s', $CFG['ldap_filter'] ), $username );
			$filter_str = sprintf( str_replace( '%p', '%s', $filter_str  ), $password );
			$sr = @ldap_search( $ds, $CFG['ldap_dn'] , $filter_str, $attrs );
			if( $sr ) {
				$ldapentry = ldap_get_entries( $ds, $sr);
				if( $ldapentry['count'] > 0 ) {
					$auth_ok = 1;
					if( isset($CFG['ldap_variable']) )
						foreach( $CFG['ldap_variable'] as $var => $attr )
							$userinfo[$var] = $ldapentry[0][strtolower($attr)][0];
				}
			}
		}
		ldap_close($ds);
	}

	if( $auth_ok == 0 )
		return(null);

	$userinfo['%u'] = $username;

	return( $userinfo );
}

?>
