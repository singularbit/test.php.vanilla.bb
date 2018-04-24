<?php

namespace APP;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<title>Burke&Best Invaders</title>
	</head>
	<body>
		<div id="arena"></div>

		<?php
		// Main file
		// Comment this line for production
		error_reporting(E_ALL & ~E_NOTICE);

		$application_folder = 'application';

		// The name of THIS file
		define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

		// The path to the "application" folder
		if (is_dir($application_folder)) {
			define('APPPATH', $application_folder . '/');
		} else {
			if (!is_dir(BASEPATH . $application_folder . '/')) {
				exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: " . SELF);
			}

			define('APPPATH', BASEPATH . $application_folder . '/');
		}

		// Error level
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');

		define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		?>

		<script>
            // Initialize the game
            $(document).ready(function () {

                var request = $.ajax({
                    url: "application/models/Router.php",
                    type: "POST",
                    data: {
                        url: 'http://burkeandbest.bit/application/controllers/Game/init',
                        arena_size: 121,
                        number_of_players: 10,
                        maximum_number_of_players: 6,
                        minimum_number_of_players: 4,
                        players_flux: 1
                    }
                });

                request.done(function (data) {
                    $('#arena').html(data)
                });

                request.fail(function (jqXHR, textStatus) {
                    console.log("Request failed: " + textStatus);
                });


                $(document).on('click', '#next_turn', function () {

                    var request2 = $.ajax({
                        url: "application/models/Router.php",
                        type: "POST",
                        data: {
                            url: 'http://burkeandbest.bit/application/controllers/Game/turn'
                        }
                    });

                    request2.done(function (data) {
						console.log('data = ' + data);
                        $('#arena').html(data)
                    });

                    request2.fail(function (jqXHR, textStatus) {
                        console.log("Request failed: " + textStatus);
                    });


                });


            });

		</script>
	</body>
</html>