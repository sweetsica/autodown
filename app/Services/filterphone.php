
<form method='POST' action='' id='content_form' >
	<textarea form='content_form' name='content' style='width:450;height:250px'> </textarea>
	<button type="submit" name='submit'>Lọc số</button>
</form>



<?php
	if(isset($_POST['submit'])){
		$content = $_POST['content'];
		$file = 'listphone/textphone-'.date("h_i_sa").'.txt';
		file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

		$rule = "\+?\(?([0-9]{3})\)?[-.]?\(?([0-9]{3})\)?[-.]?\(?([0-9]{4})\)?";

		$results = preg_grep('/\+?\(?([0-9]{3})\)?[-.]?\(?([0-9]{3})\)?[-.]?\(?([0-9]{4})\)?/', explode("\n", $content));

		$phone = $results;
		$file = 'phonedone/phonelist-'.date("h_i_sa").'.txt';
		file_put_contents($file, $phone, FILE_APPEND | LOCK_EX);

		var_dump($results);


	}
?>
