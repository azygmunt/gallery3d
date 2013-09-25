<?php
require_once ('../../Connections/gallery3d_home.php');
?>
<?php
if (!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
		$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
		$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
		switch ($theType) {
			case "text" :
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "long" :
			case "int" :
				$theValue = ($theValue != "") ? intval($theValue) : "NULL";
				break;
			case "double" :
				$theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
				break;
			case "date" :
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "defined" :
				$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
				break;
		}
		return $theValue;
	}

}

mysql_select_db($database_gallery3d_home, $gallery3d_home);
$query_section_records = "SELECT `section` FROM sections ORDER BY `section` ASC";
$section_records = mysql_query($query_section_records, $gallery3d_home) or die(mysql_error());
$row_section_records = mysql_fetch_assoc($section_records);
$totalRows_section_records = mysql_num_rows($section_records);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>3d Viewer</title>
		<script src="../js/jquery-1.8.1.min.js" type="text/javascript"></script>
		<script src="../js/gallery3d.js" type="text/javascript"></script>
		<link href="../css/gallery3d.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div id="threedviewer">
			<div id="viewport">
				<div id="gallery"></div>
				<div id="imagedisplay">
					<img src="../images/images/IMG_0007_l.jpg" width="400" height="300" /><img src="../images/images/IMG_0007_r.jpg" width="400" height="300" />				</div>
			</div>
			<div id="controls">
				<h2>Type</h2>
				<select name="viewtype" id="viewtype">
					<option value="left">Left</option>
					<option value="right">Right</option>
					<option value="parallel">Parallel</option>
					<option value="crosseye">Cross-eye</option>
					<option value="redblue">Red/Blue</option>
					<option value="flicker">Flicker</option>
				</select>
				<select name="sectionlist" id="sectionlist">
					<?php
					do {
						echo '<option value="' . $row_section_records['section'] . '">' . $row_section_records['section'] . '</option>';
					} while ($row_section_records = mysql_fetch_assoc($section_records));
					$rows = mysql_num_rows($section_records);
					if ($rows > 0) {
						mysql_data_seek($section_records, 0);
						$row_section_records = mysql_fetch_assoc($section_records);
					}
					?>
				</select>
				<input type="submit" id="viewgallery" value="Gallery">
				<input type="button" id="viewindex" value="Index">
			</div>
		</div>
</body>
</html>
<?php
mysql_free_result($section_records);
?>
