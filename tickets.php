<?php

include __DIR__ . '/include.php';
include __DIR__ . '/header.php';

$query = $_POST['query'];

if ($loggedin) {

	if (isset($_POST['query']) && $query) {
		//query present
		echo 'Showing results for: <pre>' . $query . '</pre>';
	} else {
		$query = 'created>1day';
	}

	$data = curlWrap("/search.json?query=type%3Aticket+" . urlencode($query), null, "GET");

	//echo "<pre>", print_r($data, 1), "</pre>";

	echo $m->render('tickets', $data);

} else {
	echo $m->render('login', $data);
}

include __DIR__ . '/footer.php';