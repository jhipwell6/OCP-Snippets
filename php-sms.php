<?php
/*
 * PHP to SMS Class
 * requires phone number and carrier input
 *
 */
 
class SMStext {
	 
	public $phone;
	public $carrier;
	public $message;
	public $from;
	
	public function send_sms($phone = null, $carrier = null, $message = null, $from = null) {
		if($phone == null)
			$phone = $this->phone;
		
		if($carrier == null)
			$carrier = $this->carrier;
		
		if($message == null)
			$message = $this->message;
		
		if($from == null)
			$from = $this->from;
		
		$domain = $this->get_carrier_domain($carrier);
		$to = $phone . '@' . $domain;
		$subject = '';
		$message = wordwrap($message, 70, "\r\n");
		$headers = 'From: <'.$from.'>' . "\r\n";
		
		$result = mail($to, $subject, $message, $headers);
	}
	
	private function get_carrier_domain($carrier) {
		switch($carrier) {
			case 'AT&T':
			case 'ATT':
				$domain =  'txt.att.net';
				break;
			case 'Sprint':
				$domain = 'messaging.sprintpcs.com';
				break;
			case 'T-Mobile':
			case 'tmobile':
				$domain = 'tmomail.net';
				break;
			case 'Verizon':
				$domain = 'vtext.com';
				break;
		}
		return $domain;
	}
}