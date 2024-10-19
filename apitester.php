<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1 Dashboard API Tester</title>
    <link rel="stylesheet" href="css/apitester.css">
    <!-- Import Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Lobster&display=swap" rel="stylesheet">
</head>
<body>
    <div class="header">
        <h1>F1 Dashboard Project</h1>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="browse.php">Browse</a>
            <a href="apitester.php">APIs</a>
        </div>
    </div>

    <div class="content">
        <h2>API List</h2>
        <table class="api-table">
            <thead>
                <tr>
                    <th>URL</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><a href="/COMP3512-Assignment-1/api/circuits.php">/api/circuits.php</a></td>
                    <td>Returns all the circuits</td>
                </tr>
                <tr>
                    <td><a href="/COMP3512-Assignment-1/api/circuits.php?ref=monaco">/api/circuits.php?ref=monaco</a></td>
                    <td>Return just a specific circuit (use the circuitRef field), e.g., <i>/api/circuits/monaco</i></td>
                </tr>
                <tr>
                    <td><a href="/COMP3512-Assignment-1/api/constructors.php">/api/constructors.php</a></td>
                    <td>Returns all the constructors</td>
                </tr>
                <tr>
                    <td><a href="/COMP3512-Assignment-1/api/constructors.php?ref=mclaren">/api/constructors.php?ref=mclaren</a></td>
                    <td>Return just a specific constructor (use the constructorRef field), e.g., <i>/api/constructors/mclaren</i></td>
                </tr>
                <tr>
                    <td><a href="/COMP3512-Assignment-1/api/drivers.php">/api/drivers.php</a></td>
                    <td>Returns all the drivers for the season</td>
                </tr>
                <tr>
                    <td><a href="/COMP3512-Assignment-1/api/drivers.php?ref=hamilton">/api/drivers.php?ref=hamilton</a></td>
                    <td>Returns just the specified driver (use the driverRef field), e.g., <i>/api/drivers/hamilton</i></td>
                </tr>
                <tr>
                    <td><a href="/COMP3512-Assignment-1/api/drivers.php?race=1106">/api/drivers.php?race=1106</a></td>
                    <td>Returns the drivers within a given race, e.g., <i>/api/drivers/race/1106</i></td>
                </tr>
                <tr>
                    <td><a href="/COMP3512-Assignment-1/api/races.php?ref=2022">/api/races.php?ref=2022</a></td>
                    <td>Returns just the specified race.<i>/api/races/season/2022</i></td>
                </tr>
                <tr>
                    <td><a href="/COMP3512-Assignment-1/api/races.php">/api/races.php</a></td>
                    <td>Returns the races within the 2022 season ordered by round, e.g., <i>/api/races/season/2022</i></td>
                </tr>
                <tr>
                    <td><a href="/COMP3512-Assignment-1/api/qualifying.php?ref=1106">/api/qualifying.php?ref=1106</a></td>
                    <td>Returns the qualifying results for the specified race, e.g., <i>/api/qualifying/1106</i></td>
                </tr>
                <tr>
                    <td><a href="/COMP3512-Assignment-1/api/results.php?ref=1106">/api/results.php?ref=1106</a></td>
                    <td>Returns the results for the specified race, e.g., <i>/api/results/1106</i></td>
                </tr>
                <tr>
                    <td><a href="/COMP3512-Assignment-1/api/results.php?driver=max_verstappen">/api/results.php?driver=max_verstappen</a></td>
                    <td>Returns all the results for a given driver, e.g., <i>/api/results/driver/max_verstappen</i></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
