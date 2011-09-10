=== MyOpenID Delegation ===
Contributors: benatkin
Tags: openid, identity, delegation, MyOpenID
Requires at least: 3.2.0
Tested up to: 3.2
Stable tag: 0.3.2

This plugin delegates OpenID to MyOpenID.

== Description ==

This plugin adds the required meta tags to delegate OpenID to a http://myopenid.com/ account.

== Installation ==

Use the standard WP plugin installation procedure. After installing and activating, go to
the Settings > General to set the username of your MyOpenID account. Once that is set, link
and meta tags to delegate OpenID to your MyOpenID account will be added to your home page.

== Troubleshooting ==

If rewrite rules from a caching plugin, or custom rewrite rules, cause the page to do a 301
redirect, login will fail, at least for some sites. Remove these rewrites to fix it.
