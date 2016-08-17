<?php

include __DIR__ . '/include.php';
include __DIR__ . '/header.php';

$parser = new \cebe\markdown\Markdown();

$problem_id = $_GET['problem_id'];
$custom_fields = array(
	array("name"=> "Dev Link", "id" => 25137258, "value"=> ""),
	array("name"=> "ERT Link", "id" => 34853527, "value"=> ""),
	array("name"=> "Article", "id" => 32254678, "value"=> "")
);
$problem_dc = array();

if ($loggedin) {
	if ($problem_id) {

		$problem = curlWrap("/tickets/" . $problem_id . ".json", null, "GET");
		$incidents = curlWrap("/tickets/" . $problem_id . "/incidents.json", null, "GET");
		$dynamic_content = curlWrap("/dynamic_content/items.json", null, "GET");
		$comments = curlWrap("/tickets/" . $problem_id . "/comments.json", null, "GET");

		// get the custom fields
		foreach ($problem['ticket']['custom_fields'] as $akey => $afield) {
			foreach ($custom_fields as $bkey => $bfield ) {
				if ($afield['id'] == $bfield['id']) {
					$custom_fields[$bkey]['value'] = $afield['value'];
					continue 2;
				}
			}
		}		

		$agent_process;
		// find agent procedure
		foreach ($comments['comments'] as $key => $comment) {
			if (stripos($comment['body'], "### Agent Process") !== false) {
				$agent_process = $comment;
				$agent_process['html_body'] = str_replace("Agent Process", "", $agent_process['html_body'] );
				break;
			}
		}

		// update agent process
		if (isset($_POST['agent_process'])) {

			$agent_process['html_body'] = $parser->parse($_POST['message']);
			$message = str_replace("\r\n", '\r\n', $_POST['message']);
			$message = str_replace('"', '\"', $message);

			if ($message) {

				$res_process = curlWrap("/tickets/" . $problem_id . ".json", '{"ticket": { "comment":  { "body": "### Agent Process\n\n' . $message . '", "public": false }}}', "PUT");

				//$agent_process['html_body'] = $parser->parse(str_replace("Agent Process", "", $message ));

				echo '<p>Process created</p>';
				//$parser->parse($message);
			}

			unset($_POST);
		} 

		// check for mass message history
		foreach ($comments['comments'] as $key => $comment) {
			if (stripos($comment['body'], "### Mass message") !== false) {
				// found mass message
				$comments['comments'][$key]['html_body'] = str_replace("Mass message sent", "", $comment['html_body'] );
				//echo $comment['body'];
			} else {
				unset($comments['comments'][$key]);
			}
		}

		// find related dynamic content
		//echo "<pre>", print_r($dynamic_content['items'], 1), "</pre>";
		foreach ($dynamic_content['items'] as $key => $item) {
			
			$problem_pos = stripos($item['variants'][0]['content'] , $problem_id);

			if ( $problem_pos !== false ) {
				$problem_dc = $item;
				$problem_dc['variants'][0]['html_content'] = str_replace("\r\n", '<br />',$problem_dc['variants'][0]['content']);

				//echo $problem_dc['variants'][0]['html_content'];
			}
		}

		// create new automated message for problem
		if (isset($_POST['auto_message'])) {
			$message = str_replace("\r\n", '\r\n', $_POST['message']);
			$status = $_POST['status'];
			$trigger_tag = $_POST['trigger_tag'];

			$content = 'public:true,\r\nstatus:' . $status . ',\r\ntype:incident,\nproblem_id:' . $problem_id . ',\r\n~\r\n' . $message;
			$dc_data = '{"item": {"name": "TAG: ' . $trigger_tag . '", "default_locale_id": 1,"variants": [{"locale_id": 1,"default": true,"content": "' . $content . '"}]}}';

			if ($message && $status && $trigger_tag ) {
				
				$res_dc = curlWrap("/dynamic_content/items.json", $dc_data, "POST");
				$problem_dc = $res_dc['item'];
				$problem_dc['variants'][0]['html_content'] = str_replace("\n", '<br />',$problem_dc['variants'][0]['content']);

				$message = str_replace('\n', '\n>', $message);
				$res_ticket = curlWrap("/tickets/" . $problem_id . ".json", '{"ticket": { "comment":  { "body": "### Automation created\n\n\n**Status applied:** ' . $status . '\n**Triggering Tag:** ' . $trigger_tag .'\n\n>' . $message . '", "public": false },"status": "open"}}', "PUT");

				echo '<p>Automated message created: ' . $res_dc['item']['name'] . '</p>';
				
			}

			unset($_POST);
		} 

		// delete automated message
		if (isset($_POST['delete_auto_message'])) {
			$dynamic_id = $_POST['dynamic_id'];
			if ( $dynamic_id ) {
				$res_dc = curlWrap("/dynamic_content/items/" . $dynamic_id . ".json", null, "DELETE");
				
				echo '<p>Automated message deleted: ' . $dynamic_id . '</p>';
				echo $res_dc;
		
				unset($problem_dc);
			}
			unset($_POST);
		}

		// most recent comments first
		$comments = array_reverse($comments['comments']);

		// check for message
		if (isset($_POST['send_message'])) {

			$mass_message = str_replace("\r\n", '\n', $_POST['comment']);
			$mass_status = $_POST['status'];

			// make sure the message is not empty and send
			if ($_POST['comment'] && $_POST['comment'] != ''){

				foreach ($incidents['tickets'] as $incident) {
					$mass_ids = $mass_ids . $incident['id'] . ($incident == end($incidents['tickets']) ? "" : ",");
				}

				$update_status = curlWrap("/tickets/update_many.json?ids=" . $mass_ids, '{"ticket": { "comment":  { "body": "' . $mass_message . '", "public": true },"status": "' . $mass_status . '"}}', "PUT");
				
				$mass_message = str_replace('\n', '\n>', $mass_message);
				$update_status_p = curlWrap("/tickets/" . $problem_id . ".json", '{"ticket": { "comment":  { "body": "### Mass message sent\n\n**Status:** ' . $mass_status . '\n**Incidents:** ' . $mass_ids .'\n\n>' . $mass_message . '", "public": false }}}', "PUT");

				echo '<p>Message queued for tickets: ' . $mass_ids . '</p>';

			}
			
			unset($_POST);
		}

		// sort incidents by status
		usort($incidents['tickets'], function($a, $b) {
			return strcmp($a['status'], $b['status']);
		});

		$data = array(
			"problem"=>$problem['ticket'], 
			"incidents"=>$incidents, 
			"fields"=>$custom_fields,
			"dynamic_content"=>$problem_dc,
			"agent_process"=>$agent_process,
			"comments"=>$comments
			);

		echo $m->render('incidents', $data);

	} else {
		$problems = curlWrap("/problems.json?include=incident_counts", null, "GET");
		$filters = curlWrap("/ticket_fields.json", null, "GET");
		
		//valid tagger fields only
		$valid_fields = array(21043339);// 21043339 = Game

		// setup all the filters
		foreach ($filters['ticket_fields'] as $key => $filter) {
			foreach($valid_fields as $field) {
				 if ($filter['id'] == $field) {
				 	// field is valid
				 } else {
				 	// field is not valid
				 	unset($filters['ticket_fields'][$key]);
				 	continue 2;
				 }
			}

			// are there any filters applied
			if (isset($_POST['filter'])) {
				if (isset($_POST['filter_' . $filter['id']])) {
					foreach ($filter['custom_field_options'] as $keya => $options) {
						if ($options['value'] == $_POST['filter_' . $filter['id']]) {
							$filters['ticket_fields'][$key]['custom_field_options'][$keya]['selected'] = 'selected';

							// apply filter to problems
							foreach ($problems['tickets'] as $pkey => $problem) {
								foreach ($problem['custom_fields'] as $fkey => $ffield) {
									if ($ffield['id'] == $filter['id'] ) {
										if ($ffield['value'] != $_POST['filter_' . $filter['id']]) {
											// filter out non matching fields
											unset($problems['tickets'][$pkey]);
										}
									}
								}
							}
						}
					}
				}

				unset($_POST);
			}
		}

		// sort problems by status
		usort($problems['tickets'], function($a, $b) {
			return strcmp($a['status'], $b['status']);
		    // for INT use: return $a['status'] - $b['status'];
		});

		// reset keys because of unset
		$filters['ticket_fields'] = array_values($filters['ticket_fields']);

		// check for new problem ticket creation
		if (isset($_POST['create_problem'])) {

			$message = str_replace("\r\n", '\n', $_POST['comment']);
			$new_fields = '';
			$prefix = '';

			foreach ($custom_fields as $key => $field ) {
				if (isset($_POST['field_' . $field['id']])) {
					// field exists
					$new_fields .= $prefix . '{"id": ' . $field['id'] . ', "value": "' . $_POST['field_' . $field['id']] . '"}';
					$prefix = ', ';
				}
			}

			foreach ($filters['ticket_fields'] as $key => $field ) {
				if (isset($_POST['field_' . $field['id']])) {
					// field exists
					$new_fields .= $prefix . '{"id": ' . $field['id'] . ', "value": "' . $_POST['field_' . $field['id']] . '"}';
					$prefix = ', ';
				}
			}

			// make sure the message is not empty
			if ($_POST['comment'] && $_POST['subject'] && $_POST['comment'] != '') {

				$data = '{"ticket": {"ticket_form_id": 175057, "type": "problem", "subject": "' . $_POST['subject'] . '", "custom_fields": [' . $new_fields . '], "comment": { "body": "' . $_POST['comment'] . '" }}}';
				$new_ticket = curlWrap("/tickets.json", $data, "POST");

				if ($new_ticket['ticket']['id']) {
					echo '<p>New ticket created <a href="/problems.php?problem_id=' . $new_ticket['ticket']['id'] . '">' . $new_ticket['ticket']['id'] . '</a></p>';
					//echo "<pre>", print_r($new_ticket, 1), "</pre>";
				} else {
					echo '<p>Error creating new ticket</p>';
				}
				unset($_POST);
			}
		}

		// loop through problems and apply filters
		/*foreach ($problems['tickets'] as $pkey => $problem) {
			foreach ($filters['ticket_fields'] as $fkey => $filter ) {
				foreach ($filter['custom_field_options'] as $keya => $options) {
					if ($options['value'] == $_POST['filter_' . $filter['id']]) {
						$filters['ticket_fields'][$key]['custom_field_options'][$keya]['selected'] = 'selected';
					}
				}
			}
		}*/

		$data = array(
			"problems"=>$problems['tickets'],
			"filter"=>$filters,
			"fields"=>$custom_fields
		);

		echo $m->render('problems', $data);
	}
} else {
	echo $m->render('login', $data);
}

include __DIR__ . '/footer.php';