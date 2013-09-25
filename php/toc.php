<?php
include 'db.php';

$query = "SELECT * FROM sections";

if ($result = mysqli_query($link, $query)) {
	echo '<ul>';
	while ($row = mysqli_fetch_assoc($result)) {
		echo '<li><a href="php/gallery.php?section=' . $row['section'] . '">' . $row['section'] . '</a></li>';
//		echo '<li>' . $row['section'] . '</li>';
	}
	echo '</ul>';
	/* free result set */
	mysqli_free_result($result);
}
mysqli_close($link);
?>
