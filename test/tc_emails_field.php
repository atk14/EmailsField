<?php
class TcEmailsField extends TcBase {

	function test(){
		$this->field = new EmailsField(["max_emails" => 3]);
		
		$value = $this->assertValid("john@doe.com");
		$this->assertEquals("john@doe.com",$value);

		$value = $this->assertValid(" john@doe.com ,  Samantha Doe <samantha@doe.com> ");
		$this->assertEquals("john@doe.com, Samantha Doe <samantha@doe.com>",$value);

		$err_msg = $this->assertInvalid("john.doe");
		$this->assertEquals("Address john.doe is not valid email address",$err_msg);

		$err_msg = $this->assertInvalid("john@doe.com, JOHN DOE <JOHN@DOE.COM>");
		$this->assertEquals("The email address john@doe.com is listed more than once",$err_msg);

		$err_msg = $this->assertInvalid("john@doe.com, samantha@doe.com, bob@doe.com, jack@doe.com");
		$this->assertEquals("Maximum number of email addresses is 3 (now it is 4)",$err_msg);
	}
}
