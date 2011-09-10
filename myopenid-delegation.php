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
Plugin Name: MyOpenID Delegation
Plugin URI: https://github.com/benatkin/wordpress-myopenid-delegation
Description: Delegates blog URL to MyOpenID
Version: 0.0.1
Author: Ben Atkin
Author URI: http://benatkin.com/
License: GPL2
*/

function myopenid_delegation_settings_api_init() {
	add_settings_section('myopenid_delegation_account_section',
		'MyOpenID Delegation',
		'myopenid_delegation_account_section_callback',
		'general');

	add_settings_field('myopenid_delegation_account_username',
		'MyOpenID Username',
		'myopenid_delegation_account_username_field_callback',
		'general',
		'myopenid_delegation_account_section');

	register_setting('general', 'myopenid_delegation_account_username');
}

add_action('admin_init', 'myopenid_delegation_settings_api_init');

function myopenid_delegation_account_section_callback() {
	echo '<p>Please enter your MyOpenID username so this plugin can add the correct meta tags to the root of your site.</p>';
}

function myopenid_delegation_account_username_field_callback() {
	echo '<input name="myopenid_delegation_account_username" id="myopenid_delegation_account_username" type="text" value="' . esc_attr(get_option('myopenid_delegation_account_username')) . '" size=20 maxlength=40 > .myopenid.com';
}

function myopenid_delegation_render_meta_tags() {
	if (is_home()) {
		$username = get_option('myopenid_delegation_account_username');
		if ($username) {
			echo '<link rel="openid.server" href="http://www.myopenid.com/server" />' . "\n";
			echo '<link rel="openid.delegate" href="http://' . esc_attr($username) . '.myopenid.com/" />' . "\n";
			echo '<link rel="openid2.local_id" href="http://' . esc_attr($username) . '.myopenid.com" />' . "\n";
			echo '<link rel="openid2.provider" href="http://www.myopenid.com/server" />' . "\n";
			echo '<meta http-equiv="X-XRDS-Location" content="http://www.myopenid.com/xrds?username=' . esc_attr($username) . '.myopenid.com" />' . "\n";
		}
	}
}

add_action('wp_head', 'myopenid_delegation_render_meta_tags');

?>
