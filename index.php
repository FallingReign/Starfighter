<?php

include __DIR__ . '/include.php';
include __DIR__ . '/header.php';

if ($loggedin) {
	echo $m->render('dashboard');
} else {
	echo $m->render('login', $data);
}

include __DIR__ . '/footer.php';