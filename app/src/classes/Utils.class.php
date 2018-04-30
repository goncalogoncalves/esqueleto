<?php

namespace Esqueleto\Classes;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Utils {

	public function logEvent($title = '', $message = '') {
		if (is_array($message)) {
			$tempMessage = '';
			for ($i = 0; $i < sizeOf($message); $i++) {
				$tempMessage .= ' - ' . $message[$i];
			}
			$message = $tempMessage;
		}
		$userIP = $this->getUserIP();
		$forwardedFor = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
		$messageLog = ' Message: ' . $message . ' _ From ' . $userIP . ' _ ' . $forwardedFor;

		$log = new Logger('log');
		$log->pushHandler(new StreamHandler(LOG_FILE, Logger::INFO));
		$log->addInfo($title . ' _ ' . $messageLog);
	}

	public function getUserIP() {
		$client = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : '';
		$forward = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
		$remote = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

		if (filter_var($client, FILTER_VALIDATE_IP)) {
			$ip = $client;
		} elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
			$ip = $forward;
		} else {
			$ip = $remote;
		}

		return $ip;
	}

	/**
	 * Responsible to tell if the request was made through ajax.
	 *
	 * @return bool True if the request was made through ajax
	 */
	public function isAjax() {
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && mb_strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return true;
		}

		return false;
	}

}
