<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>404 Page Not Found</title>
<style type="text/css">
body {
    background: -webkit-linear-gradient(45deg, #88bfe8 0%,#70b0e0 100%);
    background: linear-gradient(45deg, #88bfe8 0%,#70b0e0 100%);
    color: #202020;
	margin: 9%; padding: 25px;
    border: 1px solid #FFFFFF;
	font: 13px/20px Verdana, sans-serif;
}
h1{
    border-bottom: 1px dotted #4060AA;
    padding-bottom: 15px;
    margin-bottom: 10px;
    color: #406090;
}
</style>
</head>
<body>
	<div id="container">
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
	</div>
</body>
</html>