<?php
include_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Sport Calendar</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
<div id="calendar_div">
    <?php echo getCalender(); ?>
</div>
</body>
</html>