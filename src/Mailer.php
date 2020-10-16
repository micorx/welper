<?php
namespace Micorx\WebHelpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {

	protected $language;

	protected $website;

	protected $emails;

	protected $fields;

	protected $recaptcha_secret;

	protected $recaptcha_response;

	function __construct() {
		$this->language = setSysLang();
	}

	protected function sendTechError($code = false) {
		$subject = "ERROR FORM";
		$text = $text = "Sito: " . $this->website . "\n";
		if (isset($GLOBALS['sys_page']) && is_array($GLOBALS['sys_page'])) {
			$this_page = $GLOBALS['sys_page1'];
			$text .= "Pagina: ";
			$array_key = array_key_first($this_page);
			if ($array_key === 0) {
				$text .= $this_page[$array_key];
			} else {
				$limit = 0;
				while (is_array($this_page[$array_key]) && 50 > $limit ++) {
					$text .= $array_key . " - ";
					$this_page = $this_page[$array_key];
					$array_key = array_key_first($this_page);
				}
				$text .= $array_key . " - " . $this_page[$array_key];
			}
			$text .= "\n\n";
		}
		$text .= "IP: " . getIPAddress() . "\n";
		$text .= "\n---------------------------------------\n\n";
		if (is_array($this->fields)) {
			foreach ($this->fields as $index => $value) {
				if (is_string($value)) {
					$text .= ucwords(strtolower(trim($index))) . ": " . $value . "\n";
				}
			}
		}
		if (isset($this->emails['from']['internal']['address']) && isset($this->emails['from']['internal']['name']) &&
			isset($this->emails['to']['tech']['address'])) {
			$this->senderEmailTEXT($this->emails['from']['internal']['address'], $this->emails['from']['internal']['name'],
				$this->emails['to']['tech']['address'], $subject, $text);
		}
		if ($code === false) {
			return responseMessage(R_ERROR);
		} else {
			return responseMessage(R_ERROR, $code);
		}
	}

	protected function senderEmailTEXT($from_addr, $from_name, $to_addr, $subject, $text) {
		$mail = new PHPMailer(TRUE);
		try {
			$mail->CharSet = 'UTF-8';
			$mail->setFrom($from_addr, $from_name);
			$mail->addAddress($to_addr);
			$mail->Subject = $subject;
			$mail->Body = $text;
			$mail->send();
			return true;
		} catch (Exception $e) {
			return false;
		} catch (\Exception $e) {
			return false;
		}
	}

	protected function senderEmailHTML($from_addr, $from_name, $to_addr, $subject, $template_page, $template_data = null) {
		$mail = new PHPMailer(TRUE);
		try {
			$mail->CharSet = 'UTF-8';
			$mail->setFrom($from_addr, $from_name);
			$mail->addAddress($to_addr);
			$mail->Subject = $subject;
			$mail->Body = $this->includeEmailHTML($template_page, $template_data);
			$mail->IsHTML(true);
			$mail->send();
			return true;
		} catch (Exception $e) {
			return false;
		} catch (\Exception $e) {
			return false;
		}
	}

	private function includeEmailHTML($file, $params = null) {
		$path = SYS_ROOT . $file;
		if ($params !== null) {
			$GLOBALS['sys_include_email'] = $params;
		}
		if (is_file($path)) {
			ob_start();
			include $path;
			return ob_get_clean();
		}
		if ($params !== null) {
			unset($GLOBALS['sys_include_email']);
		}
		return false;
	}

	protected function validateInfo($fields_input, $requireds) {
		if (is_array($fields_input)) {
			foreach ($fields_input as $field => $value) {
				if (trim($value) !== '') {
					$field_validated = $this->validateType($field, $value);
					if ($field_validated !== false) {
						$this->fields[$field] = $field_validated;
					}
				}
			}
		}
		foreach ($requireds as $value) {
			if (! isset($this->fields[$value])) {
				return false;
			}
		}
		if (isset($this->fields['recaptcha'])) {
			$this->getRecaptcha($this->fields['recaptcha']);
		}
		return true;
	}

	protected function validateType($input_field, $input_value) {
		
	}

	private function getRecaptcha($token) {
		$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
		$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $this->recaptcha_secret . '&response=' . $token);
		// $recaptcha_dec = json_decode($recaptcha, true);
		$this->recaptcha_response = $recaptcha;
	}
}
?>
