<?php 
$bearer = file_get_contents('.bearer');

$twuser = 'tevruden';

$authorization = "Authorization: Bearer $bearer";
$cursor="-1";

while($cursor != 0) {
	$url = "https://api.twitter.com/1.1/followers/list.json?cursor=$cursor&screen_name=$twuser&skip_status=true&include_user_entities=false&count=200";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$result = curl_exec($ch);
	file_put_contents('result', $result);
	curl_close($ch);
	if (is_string($result)) {
		$obj = json_decode($result);
		if (!isset($obj->errors)) {
			foreach($obj->users as $user) {
				file_put_contents('users/' . $user->id, print_r($user, true));
				echo $user->screen_name . "\n";
			}
			if(IntVal($obj->next_cursor)) {
			$cursor = $obj->next_cursor;
			} else {
				$cursor = 0;
			}
		} else {
			if($obj->errors->code == 88) {
				sleep(450);
			}
			exit(1);
		}
	} else {
		$cursor = 0;
	}

}
?>
