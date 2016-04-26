<?php

namespace Freshope\Iamport;

class IamportAuthException extends Exception {
	public function __construct($message, $code) {
		parent::__construct($message, $code);
	}
}
