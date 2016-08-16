<div class="mdl-grid mdl-cell--12-col">
	<div class="mdl-cell mdl-card mdl-shadow--4dp mdl-cell--6-col">
    	<div class="mdl-card__title mdl-color--primary mdl-color-text--white">
			<h2 class="mdl-card__title-text">Problem #{{problem.id}}</h2>
		</div>
  		<div class="mdl-card__supporting-text">
			<div class="mdl-card__supporting-text">
        	<h5>{{problem.subject}}</h5>
		    <p><b>Status:</b> {{problem.status}}<br />
		    	{{# fields}}
		    	<b>{{name}}:</b> <a href="{{value}}" target="_blank">{{value}}</a> <br />
			    {{/ fields}}
		    </p>
		    <p>{{problem.description}}</p>
        </div>
		</div>
		<div class="mdl-card__actions mdl-card--border">
			<a role='buttion' href="https://firemonkeys.zendesk.com/agent/tickets/{{problem.id}}" target="_blank" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Open in Zendesk</a>
		</div>
	</div>
     <div class="mdl-cell mdl-card mdl-shadow--4dp mdl-cell--6-col">
	    <form action="" method='post'>
	    	<div class="mdl-card__title mdl-color--green-500 mdl-color-text--white">
				<h2 class="mdl-card__title-text">Mass Message</h2>
			</div>
	  		<div class="mdl-card__supporting-text">
			  <div class="mdl-textfield mdl-js-textfield mdl-cell--12-col">
			    <textarea class="mdl-textfield__input" type="text" rows= "5" id="comment" name="comment"  ></textarea>
			    <label class="mdl-textfield__label" for="comment">Public message...</label>
			  </div>
			   <div class="mdl-select mdl-js-select mdl-select--floating-label">
		        <select class="mdl-select__input" id="status" name="status">
		          <option value="open">Open</option>
		          <option value="hold">On-hold</option>
		          <option value="pending">Pending</option>
		          <option value="solved">Solved</option>
		        </select>
		        <label class="mdl-select__label" for="status">Status</label>
		      </div>		
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<button role='input' type="submit" name="send_message" value="send_message" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Send Message</button>
			</div>
		</form>
	</div>
</div>

<div class="mdl-grid mdl-cell--12-col">
	<div class="mdl-cell mdl-card mdl-shadow--4dp mdl-cell--12-col">
    	<div class="mdl-card__title mdl-color--accent mdl-color-text--white">
			<h2 class="mdl-card__title-text">Automation</h2>
		</div>
		
			{{# dynamic_content.variants }}
			<form action="" method='post'>
				<div class="mdl-card__supporting-text">
					<input type="hidden" name="dynamic_id" id="dynamic_id" value="{{dynamic_content.id}}" />
					{{ dynamic_content.name }}<br />
					{{{ html_content }}}
	       		</div>
				<div class="mdl-card__actions mdl-card--border">
					<button role='input' type="submit" name="delete_auto_message" value="delete_auto_message" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Delete Automation</button>
				</div>
			</form>
			{{/ dynamic_content.variants }}
			{{^ dynamic_content.variants }}
			<form action="" method='post'>
				<div class="mdl-card__supporting-text">
						<div class="mdl-textfield mdl-js-textfield">
							<input class="mdl-textfield__input" type="text" name="trigger_tag" id="trigger_tag" />
							<label class="mdl-textfield__label" for="trigger_tag">Trigger on tag</label>
						</div>
						<div class="mdl-textfield mdl-js-textfield mdl-cell--12-col">
						    <textarea class="mdl-textfield__input" type="text" rows= "5" id="message" name="message"  ></textarea>
						    <label class="mdl-textfield__label" for="message">First response message...</label>
						</div>
						<div class="mdl-select mdl-js-select mdl-select--floating-label">
					        <select class="mdl-select__input" id="status" name="status">
								<option value="open">Open</option>
								<option value="hold">On-hold</option>
								<option value="pending">Pending</option>
								<option value="solved">Solved</option>
					        </select>
					        <label class="mdl-select__label" for="status">Status applied</label>
					    </div>
		        </div>
				<div class="mdl-card__actions mdl-card--border">
					<button role='input' type="submit" name="auto_message" value="auto_message" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Create Automation</button>
				</div>
			</form>
			{{/ dynamic_content.variants }}
	</div>
</div>


<div class="mdl-grid mdl-cell--12-col">
	<!--<h4 class="category-section-title mdl-typography--display-1-colorcontrast">Attached Cases</h4>-->
	<table class="mdl-cell mdl-cell--6-col mdl-data-table mdl-js-data-table mdl-shadow--2dp">
		<thead>
			<tr>
				<th class="mdl-data-table__cell--non-numeric">ID</th>
				<th class="mdl-data-table__cell--non-numeric">Status</th>
				<th class="mdl-data-table__cell--non-numeric">Subject</th>
			</tr>
		</thead>
		<tbody>
			{{# incidents.tickets }}
			<tr>
				<td class="mdl-data-table__cell--non-numeric">
					<a href="https://firemonkeys.zendesk.com/agent/tickets/{{id}}" target="_blank">{{id}}</a>
				</td>
				<td class="mdl-data-table__cell--non-numeric">{{status}}</td>
				<td class="mdl-data-table__cell--non-numeric">{{subject}}</td>
			</tr>
			{{/ incidents.tickets }}
		</tbody>
	</table>
	<div class="mdl-cell mdl-card mdl-shadow--4dp mdl-cell--6-col">
    	<div class="mdl-card__title mdl-color--green-900 mdl-color-text--white">
			<h2 class="mdl-card__title-text">Message History</h2>
		</div>
		
  		<div class="mdl-card__supporting-text mdl-card--border">
			<div class="mdl-card__supporting-text">
				{{# comments}}
					{{{html_body}}}
					<hr />
        		{{/ comments}}
	        </div>
        </div>
	</div>

</div>