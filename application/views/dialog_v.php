<!-- OKNO DIALOGOWE DO WYPEŁNIENIA!!! -->

<div id="dialog-form" title="Add">
  
	 <form id = "car_form">
		<fieldset id = "cartrack_editform">
		
				<div id = "top_up" class = "up">
					<div id = "jeden">
						<label for="driver">Driver:*</label>
						<input type="text" placeholder = "Driver" name="driver_name" id="driver_name" value="" class="text ui-widget-content ui-corner-all">
					</div>
					<div id = "dwa">
						<label for="phone">Phone:</label>
						<input type="text" placeholder = "example: 111222333" name="phone" id="phone" value="" class="text ui-widget-content ui-corner-all"><br/>
					</div>
					<div id = "trzy">
						<label for="destination">Destination:*</label>
						<input type="text" placeholder = "Destination" name="destination" id="destination" value="" class="text ui-widget-content ui-corner-all">
					</div>
				</div>			
					<br>
				<div id = "left_div" class = "travel">
					<p><b><label for = "start_travel">Start Travel</label></b></p>
					<label for="start_date">Date:*</label><br>
					<input type="text" placeholder = "yy-mm-dd h:m:s" name="start_date" id="start_date" value="" readonly="readonly" class="text ui-widget-content ui-corner-all"><br>
					<label for="nameppl_travel">Name of People Traveling:</label><br>
					<span id = "ppl_start">
						<input type="text" placeholder = "First Person*" id="nameppl_travel1" name="nameppl_travel1" value="" class="text ui-widget-content ui-corner-all"><br>
						<input type="text" placeholder = "Second Person" id="nameppl_travel2" name="nameppl_travel2" value="" class="text ui-widget-content ui-corner-all"><br>
						<input type="text" placeholder = "Third Pesron" id="nameppl_travel3" name="nameppl_travel3" value="" class="text ui-widget-content ui-corner-all"><br>
						<input type="text" placeholder = "Fourth Person" id="nameppl_travel4" name="nameppl_travel4" value="" class="text ui-widget-content ui-corner-all"><br>
						<input type="text" placeholder = "Fifth Person" id="nameppl_travel5" name="nameppl_travel5" value="" class="text ui-widget-content ui-corner-all"><br>
					</span>
				</div>
				<div id = "right_div" class = "travel">
					<p><b><label for = "end_travel">End Travel</label></b></p>
					<label for="end_date">Date:*</label><br>
					<input type="text" placeholder = "yy-mm-dd h:m:s" name="end_date" id="end_date" value="" readonly="readonly" class="text ui-widget-content ui-corner-all"><br>
					<label for="nameppl_travel_back">Name of People Traveling:</label><br>
					<span id = "ppl_back">
						<input type="text" placeholder = "First Person*" id="nameppl_travel_back1" name="nameppl_travel_back1" value="" class="text ui-widget-content ui-corner-all"><br>
						<input type="text" placeholder = "Second Person" id="nameppl_travel_back2" name="nameppl_travel_back2" value="" class="text ui-widget-content ui-corner-all"><br>
						<input type="text" placeholder = "Third Pesron" id="nameppl_travel_back3" name="nameppl_travel_back3" value="" class="text ui-widget-content ui-corner-all"><br>
						<input type="text" placeholder = "Fourth Person" id="nameppl_travel_back4" name="nameppl_travel_back4" value="" class="text ui-widget-content ui-corner-all"><br>
						<input type="text" placeholder = "Fifth Person" id="nameppl_travel_back5" name="nameppl_travel_back5" value="" class="text ui-widget-content ui-corner-all"><br>
					</span>
				</div>
				<div id = "bottom_down">
					<br>
					<br>
					<label for="car_pass">Car Pass:</label>
					<input type="checkbox" name="car_pass" id="car_pass" value="value[]" class="text ui-widget-content ui-corner-all"><br>
					<label for="kro_pass">Krosno Pass:</label>
					<input type="checkbox" name="kro_pass" id="kro_pass" value="value[]" class="text ui-widget-content ui-corner-all">
					<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
			</div>
					<br>
					<br>
			<div id = "Comments">
			<br>
					<label for="comments">Comments:</label><br>
					 <textarea name="comment" id="comments" class="text ui-widget-content ui-corner-all"></textarea>
					 <input type="hidden" name="tryb" id="tryb" value="" readonly="readonly" class="text ui-widget-content ui-corner-all" >
					 <input type="hidden" name="id" id="id" value="" readonly="readonly" class="text ui-widget-content ui-corner-all" >
					<!-- <input type="text" name="edit_by" id="edit_by" value="" readonly="readonly" class="text ui-widget-content ui-corner-all" >-->
			</div>
		</fieldset>
	 </form>
</div>