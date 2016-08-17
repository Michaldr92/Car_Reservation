<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Car Tracking</title>

    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery.contextMenu.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery.dataTables.min.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/cartracking.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-timepicker.css" />

    <script type='text/javascript' src="<?php echo base_url(); ?>assets/js/jquery-1.11.3.min.js"></script>
    <script type='text/javascript' src="<?php echo base_url(); ?>assets/js/jquery-ui.js"></script>
    <script type='text/javascript' src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
    <script type='text/javascript' src="<?php echo base_url(); ?>assets/js/jquery-datepicker.js"></script>
    <script type='text/javascript' src="<?php echo base_url(); ?>assets/js/jquery.contextMenu.js"></script>
    <script type='text/javascript' src="<?php echo base_url(); ?>assets/js/jquery.ui.position.min.js"></script>
    <script type='text/javascript' src="<?php echo base_url(); ?>assets/js/main_dialog.js"></script>
    <script type='text/javascript' src="<?php echo base_url(); ?>assets/js/main_cartracking.js"></script>
    <script type='text/javascript' src="<?php echo base_url(); ?>assets/js/dataTable.csvutf8.plugin.js"></script>

    <script>
        var base_url = "<?=base_url();?>";
        var netid = "<?php echo $session['netid'];?>";
        var first_name = "<?php echo $session['first_name'];?>";
        var last_name = "<?php echo $session['last_name'];?>";
        var email = "<?php echo $session['email'];?>";
        var admin = <?php echo $session['admin'];?>;
    </script>
</head>

<body>

    <div class="pasek"></div>

    <div id="logo">
        <?php echo '<img src="'.base_url().'assets/img/logo.png"/>'; ?></div>

    <div class="links_div">
        <?php
		// Sprawdzenie danych użytkownika
			if (isset($session['netid']) && $session['netid']!='') {
				echo '<span id= "wyloguj" class = "ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"><a href="'.base_url().'auth/logout">Wyloguj: '.$session['first_name'].'&nbsp;'.$session['last_name'].'</a></span>';
			} else {
					echo '<span id = "loguj" class = "ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"><a href="'.base_url().'auth/login">Zaloguj</a></span>';	
			}		
		?></div>

    <div class="naglowek">CAR TRACKING</div>

    <!--Tabelka z danymi-->
    <div id="main_table">
        <table id="cartracking" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Drivers Name</th>
                    <th>Phone</th>
                    <th>Business Destination</th>
                    <th>Start Date</th>
                    <th>Name of People Travelling</th>
                    <th>End Date</th>
                    <th>Name of People Travelling</th>
                    <th>Car Pass</th>
                    <th>Krosno Pass</th>
                    <th>Comments</th>
                    <th>Add by</th>
                    <th>Edit row</th>
                </tr>
            </thead>
        </table>

        <?php
			$this->load->view('dialog_v'); // Wczytanie głównego widoku
		?>
            <!----------->
            <button id="add_row" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">Add Row</button>
            <button id="eksport" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">Eksport</button>
            <!-- EKSPORT DO CSV -->
    </div>

</body>

</html>