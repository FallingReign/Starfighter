<div class="mdl-grid mdl-cell--12-col">
	<div class="mdl-cell mdl-card mdl-shadow--4dp mdl-cell--6-col">
	    <form action="" method='post'>
	    	<div class="mdl-card__title mdl-color--primary mdl-color-text--white">
				<h2 class="mdl-card__title-text">Filter cases</h2>
			</div>
	  		<div class="mdl-card__supporting-text">
	  			{{# filter.ticket_fields}}
				<div class="mdl-select mdl-js-select mdl-select--floating-label">
				    <select class="mdl-select__input" id="filter_{{id}}" name="filter_{{id}}">
				      	<option>-</option> 
						{{# custom_field_options}}
						<option value="{{value}}" {{selected}}>{{name}}</option> 
						{{/ custom_field_options}}
				    </select>
				    <label class="mdl-select__label" for="filter_{{id}}">{{title}}</label>
				</div>
			    {{/ filter.ticket_fields}}  	
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<button role='input' type="submit" name="filter" value="filter" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Apply filter</button>
			</div>
		</form>
	</div>
	<div class="mdl-cell mdl-card mdl-shadow--4dp mdl-cell--6-col">
	    <form action="" method='post'>
	    	<div class="mdl-card__title mdl-color--green-500 mdl-color-text--white">
				<h2 class="mdl-card__title-text">Create problem</h2>
			</div>
	  		<div class="mdl-card__supporting-text">
	  			<div class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" name="subject" id="subject" />
					<label class="mdl-textfield__label" for="subject">Subject</label>
				</div>
				<div class="mdl-textfield mdl-js-textfield mdl-cell--12-col">
					<textarea class="mdl-textfield__input" type="text" rows= "5" id="comment" name="comment"  ></textarea>
					<label class="mdl-textfield__label" for="comment">Description...</label>
				</div>

	  			{{# filter.ticket_fields}}
				<div class="mdl-select mdl-js-select mdl-select--floating-label">
				    <select class="mdl-select__input" id="field_{{id}}" name="field_{{id}}">
						{{# custom_field_options}}
						<option value="{{value}}" {{selected}}>{{name}}</option> 
						{{/ custom_field_options}}
				    </select>
				    <label class="mdl-select__label" for="field_{{id}}">{{title}}</label>
				</div>
			    {{/ filter.ticket_fields}} 

				{{# fields}}
				<div class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" name="field_{{id}}" id="field_{{id}}" />
					<label class="mdl-textfield__label" for="field_{{id}}">{{name}}</label>
				</div>
				{{/ fields}}
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<button role='input' type="submit" name="create_problem" value="create_problem" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Create problem</button>
			</div>
		</form>
	</div>
	<table class="mdl-cell mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-shadow--2dp">
		<thead>
			<tr>
				<th class="mdl-data-table__cell--non-numeric">ID</th>
				<th class="mdl-data-table__cell--non-numeric">Status</th>
				<th class="mdl-data-table__cell--non-numeric">Subject</th>
				<th class="mdl-data-table__cell--non-numeric">Cases</th>
			</tr>
		</thead>
		<tbody>
			{{# problems}}
			<tr>
				<td class="mdl-data-table__cell--non-numeric"><a href="problems.php?problem_id={{id}}">{{id}}</a></td>
				<td class="mdl-data-table__cell--non-numeric">{{status}}</td>
				<td class="mdl-data-table__cell--non-numeric">{{subject}}</td>
				<td class="mdl-data-table__cell--non-numeric">{{incident_count}}</td>
			</tr>
			{{/ problems}}
		</tbody>
	</table>
</div>