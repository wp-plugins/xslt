<?php
/*
 * Plugin Name: Better RSS Feeds
 * Plugin URI: http://wordpress.org/plugins/xslt/
 * Author: Waterloo Plugins
 * Description: Make your RSS feeds look pretty! Your RSS feed is at <a href="../?feed=rss2">http://yoursite.com/?feed=rss2</a>
 * Version: 1.0.2
 * License: GPL2+
 */
 
if(!defined('WPINC'))
	die;

function xslt_add_template($arg){
	if(strpos(end(headers_list()),'Content-Type')!==false){
		remove_filter('option_blog_charset','xslt_add_template');
		return $arg.'"?><?xml-stylesheet type="text/xsl" href="'.get_bloginfo('home').'/wp-content/plugins/xslt/template.xsl';
	}else
		return $arg;
}

function xslt_charset_hook($arg){
	if(is_feed()&&(strpos(get_query_var('feed'),'feed')===0||strpos(get_query_var('feed'),'rss')===0))
		add_filter('option_blog_charset','xslt_add_template');
}

function xslt_encoded_url(){
	echo '<encoded>';
	$host=@parse_url(home_url());
	echo rawurlencode(esc_url(apply_filters('self_link',set_url_scheme('http://'.$host['host'].wp_unslash($_SERVER['REQUEST_URI'])))));
	echo '</encoded>';
}

add_action('wp','xslt_charset_hook');
add_action('rss_head','xslt_encoded_url');
add_action('rss2_head','xslt_encoded_url');
