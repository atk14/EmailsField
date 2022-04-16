<?php
class EmailsField extends CharField {

	function __construct($options = []){
		$options += [
			"trim_value" => true,
			"null_empty_output" => true,
			"max_emails" => null,
		];

		$this->max_emails = $options["max_emails"];
		unset($options["max_emails"]);

		parent::__construct($options);

		$this->update_messages([
			"invalid" => _("Address %s is not valid email address"),
			"duplicit" => _("The email address %s is listed more than once"),
			"max_emails" => _("Maximum number of email addresses is %max_emails% (now it is %count%)"),
		]);
	}

	function clean($value){
		list($err,$value) = parent::clean($value);
		if(!is_null($err) || !strlen($value)){ return [$err,$value]; }

		$ear = new Yarri\EmailAddressRecognizer($value);
		$items = $ear->toArray();

		if(!$ear->isValid()){
			foreach($items as $item){
				if(!$item["valid"]){
					$err = sprintf($this->messages["invalid"],h($item));
					break;
				}
			}
			return [$err,null];
		}

		$addresses = [];
		foreach($items as $item){
			$address = strtolower($item["address"]);
			if(in_array($address,$addresses)){
				$err = sprintf($this->messages["duplicit"],h($address));
				return [$err,null];
			}
			$addresses[] = $address;
		}

		if(!is_null($this->max_emails) && sizeof($items)>$this->max_emails){
			$err = strtr($this->messages["max_emails"],[
				"%max_emails%" => $this->max_emails,
				"%count%" => sizeof($items)
			]);
			return [$err,null];
		}

		return [null,$ear->toString()];
	}
}
