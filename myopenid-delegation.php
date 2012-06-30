<?php
/*  Copyright 2011  Benjamin Atkin  (email : ben@benatkin.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
Plugin Name: OpenID Delegation
Plugin URI: https://github.com/benatkin/wordpress-myopenid-delegation
Description: Delegates blog URL to OpenID provider
Version: 0.0.1
Author: Ben Atkin
Author URI: http://benatkin.com/
License: GPL2
*/

$openid_delegation_account_types = array(
    'Google' => array(
	'server' => 'https://www.google.com/accounts/o8/ud?source=profiles',
	'localid' => 'https://profiles.google.com/{username}',
	'xrds' => 'https://www.google.com/accounts/o8/id',
    ),
    'MyOpenID' => array(
	'server' => 'http://www.myopenid.com/server',
	'localid' => 'http://{username}.myopenid.com',
	'xrds' => 'http://www.myopenid.com/xrds?username={username}.myopenid.com',
    ),
);

function openid_delegation_settings_api_init() {
	add_settings_section('openid_delegation_account_section',
		'OpenID Delegation',
		'openid_delegation_account_section_callback',
		'general');

	add_settings_field('openid_delegation_account_type',
		'OpenID Provider',
		'openid_delegation_account_type_field_callback',
		'general',
		'openid_delegation_account_section');

	add_settings_field('openid_delegation_account_username',
		'OpenID Username',
		'openid_delegation_account_username_field_callback',
		'general',
		'openid_delegation_account_section');

	register_setting('general', 'openid_delegation_account_type');
	register_setting('general', 'openid_delegation_account_username');
}

add_action('admin_init', 'openid_delegation_settings_api_init');

function openid_delegation_account_section_callback() {
	echo '<p>Please enter your OpenID username so this plugin can add the correct meta tags to the root of your site.</p>';
}

function openid_delegation_account_type_field_callback() {
	global $openid_delegation_account_types;
	echo '<select name="openid_delegation_account_type" id="openid_delegation_account_type">';
	$current_type = get_option('openid_delegation_account_type');
	foreach ($openid_delegation_account_types as $type => $configuration) {
	    echo '<option value="' . esc_attr($type) . '" ' . ($type == $current_type ? 'selected="selected"' : '') . '>' . $type . '</option>';
	}
	echo '</select>';
}

function openid_delegation_account_username_field_callback() {
	echo '<input name="openid_delegation_account_username" id="openid_delegation_account_username" type="text" value="' . esc_attr(get_option('openid_delegation_account_username')) . '" size="20" maxlength="40">';
}

function openid_delegation_render_meta_tags() {
	global $openid_delegation_account_types;
	if (!is_home())
	    return;
	
	$username = get_option('openid_delegation_account_username');
	if (!$username)
	    return;
	$config = $openid_delegation_account_types[get_option('openid_delegation_account_type')];
	if (!$config)
	    return;
	    
	foreach ($config as $var => $value) {
	    $$var = str_replace('{username}',esc_attr($username), $value);
	}
	
	echo join("\n",array(
	    '<link rel="openid2.local_id" href="'.$localid.'"/>',
	    '<link rel="openid2.provider" href="'.$server.'" />',
	    '<meta http-equiv="X-XRDS-Location" content="' . $xrds . '" />',
	    '<link rel="openid.server" href="'.$server.'" />',
	    '<link rel="openid.delegate" href="'.$localid.'" />',
	)) . "\n";
}

add_action('wp_head', 'openid_delegation_render_meta_tags');

?>
