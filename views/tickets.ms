<div class="mdl-grid mdl-cell--12-col">
	<table class="mdl-cell mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-shadow--2dp">
		<thead>
			<tr>
				<th class="mdl-data-table__cell--non-numeric">ID</th>
				<th class="mdl-data-table__cell--non-numeric">Status</th>
				<th class="mdl-data-table__cell--non-numeric">Subject</th>
			</tr>
		</thead>
		<tbody>
			{{# results}}
			<tr>
				<td class="mdl-data-table__cell--non-numeric"><a href="https://firemonkeys.zendesk.com/agent/tickets/{{id}}" target="_blank">{{id}}</a></td>
				<td class="mdl-data-table__cell--non-numeric">{{status}}</td>
				<td class="mdl-data-table__cell--non-numeric">{{subject}}</td>
			</tr>
			{{/ results}}
		</tbody>
	</table>
</div>