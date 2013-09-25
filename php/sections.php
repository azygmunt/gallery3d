<?php
include 'db.php';

$query = "SELECT * FROM sections";

if ($result = mysqli_query($link, $query)) {
//	echo '<select name="sectionlist" id="sectionlist">';
	while ($row = mysqli_fetch_assoc($result)) {
		echo '<option value="' . $row['section'] . '">' . $row['section'] . '</option>';
	}
//	echo '</select>';
	/* free result set */
	mysqli_free_result($result);
}
mysqli_close($link);
?>
