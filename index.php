<!DOCTYPE html>
<html>
	<head>
		<title>3d Viewer</title>
		<link href='http://fonts.googleapis.com/css?family=Oxygen:400,700' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,900,900italic,700italic,500italic,500,400italic,300italic,300,100italic,100' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="css/gallery3d.css">
		<link rel="stylesheet" type="text/css" href="css/colorbox.css">
		<script src="js/jquery-1.8.1.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
		<script type="text/javascript" src="js/gallery3d.js"></script>
	</head>
	<body>
		<div id="threedviewer">
			<div id="header" class="round color1">
				<h1 class="viewindex">3d Photo Gallery</h1>
			</div>
			<div></div>
			<div id="viewport" class="round color2">
				&nbsp;
			</div>
			<div id="controls" class="round color1">
				<div id="ctrl-index" class="ctrl">
					<input type="button" class="viewindex" value="Index">
				</div>
				<div id="ctrl-type" class="ctrl">
					<h2>Type: </h2>
					<select name="viewtype" id="viewtype">
						<option value="parallel">Parallel</option>
						<option value="crosseye">Cross-eye</option>
						<option value="redblue">Red/Blue</option>
						<option value="flicker">Flicker</option>
						<option value="left">Left</option>
						<option value="right">Right</option>
					</select>
				</div>
				<div id="ctrl-width" class="ctrl">
					<h2>Width: </h2>
					<input type="text" id="width" value="400" size="4">
					px
				</div>
				<div id="ctrl-gap" class="ctrl">
					<h2>Gap:</h2>
					<input type="text" id="gap" value="0" size="2">
					px
				</div>
				<div id="ctrl-rate" class="ctrl">
					<h2>Rate:</h2>
					<input type="text" id="rate" value="100" size="2">
					ms
				</div>
				<div id="ctrl-color" class="ctrl">
					<input type="radio" name="color" value="mono" checked>
					Monochrome
					<input type="radio" name="color" value="color">
					Color
				</div>
				<div class="clearboth"></div>
			</div>
		</div>
	</body>
</html>