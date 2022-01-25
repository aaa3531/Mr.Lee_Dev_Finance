<?php
/**
 * Cosmosfarm_Members_Mail
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Mail {
	
	var $call_to_actions = array(); // array('name'=>'url');
	
	public function __construct(){
		
	}
	
	public function send($args){
		add_filter('wp_mail_content_type', array($this, 'content_type'));
		add_filter('wp_mail', array($this, 'message_template'));
		
		$to = isset($args['to']) ? $args['to'] : '';
		$subject = isset($args['subject']) ? $args['subject'] : '';
		$message = isset($args['message']) ? $args['message'] : '';
		$this->call_to_actions = isset($args['call_to_actions']) ? $args['call_to_actions'] : array();
		
		do_action('cosmosfarm_members_mail_send', $args, $this);
		
		$result = wp_mail($to, $subject, $message);
		
		remove_filter('wp_mail', array($this, 'message_template'));
		remove_filter('wp_mail_content_type', array($this, 'content_type'));
		
		return $result;
	}
	
	public function content_type(){
		return 'text/html';
	}
	
	public function message_template($args){
		$subject = $args['subject'];
		$message = wpautop($args['message']);
		$message = str_replace('<p>', "<p style=\"font-family: 'Apple SD Gothic Neo','Malgun Gothic',arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;\">", $message);
		$call_to_actions = is_array($this->call_to_actions) ? $this->call_to_actions : array();
		
		ob_start();
		include COSMOSFARM_MEMBERS_DIR_PATH . '/email/template.php';
		$args['message'] = ob_get_clean();
		
		return $args;
	}
}