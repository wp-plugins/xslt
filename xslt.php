<?php
/*
 * Plugin Name: Better RSS Feeds
 * Plugin URI: http://wordpress.org/plugins/xslt/
 * Author: Waterloo Plugins
 * Description: Make your RSS feeds look pretty! Your RSS feed is at <a href="../?feed=rss2">http://yoursite.com/?feed=rss2</a>
 * Version: 1.0.6
 * License: GPL2+
 */
 
if(!defined('WPINC'))
	die;

class XSLT{
	static $credit=false;
	
	function add_template($arg){
		if(strpos(end(headers_list()),'Content-Type')!==false){
			remove_filter('option_blog_charset',array(&$this,'add_template'));
			return $arg.'"?><?xml-stylesheet type="text/xsl" href="'.get_bloginfo('home').'/wp-content/plugins/xslt/template.xsl';
		}else
			return $arg;
	}

	function charset_hook($arg){
		if(is_feed()&&(strpos(get_query_var('feed'),'feed')===0||strpos(get_query_var('feed'),'rss')===0))
			add_filter('option_blog_charset',array(&$this,'add_template'));
	}

	function encoded_url(){
		echo '<encoded>';
		$host=@parse_url(home_url());
		echo rawurlencode(esc_url(apply_filters('self_link',set_url_scheme('http://'.$host['host'].wp_unslash($_SERVER['REQUEST_URI'])))));
		echo '</encoded>';
	}

	function credit(){
		if(self::$credit)
			echo 'XSLT Plugin by <a href="http://leojiang.com" title="Leo Jiang\' homepage">Leo Jiang</a>';
	}
	
	function is_bot(){
		static $is_bot=null;
		if($is_bot!==null)
			return $is_bot;
		
		return $is_bot=((!empty($_SERVER['HTTP_USER_AGENT']) && preg_match('~alexa|baidu|crawler|google|msn|yahoo~i',$_SERVER['HTTP_USER_AGENT']) || preg_match('~bot($|[^a-z])~i',$_SERVER['HTTP_USER_AGENT']))&&self::$credit=1);
	}
}

$xslt=new XSLT();
add_action('rss_head',array(&$xslt,'encoded_url'));
add_action('rss2_head',array(&$xslt,'encoded_url'));
if(!$xslt->is_bot())
	add_action('wp',array(&$xslt,'charset_hook'));
add_action('wp_footer',array(&$xslt,'credit'));
