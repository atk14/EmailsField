<?php
class TcEmailsField extends TcBase {

	function test(){
		$this->field = new EmailsField();
		
		$value = $this->assertValid("john@doe.com");
		$this->assertEquals("john@doe.com",$value);
	}
}
