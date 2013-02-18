<?php
include 'common.php';

$query = "SELECT * FROM sections";

if ($result = mysqli_query($link, $query)) {
	echo '<ul>';
	while ($row = mysqli_fetch_assoc($result)) {
		$dir = $row['dir'];
		$section = $row['section'];
		$query = "SELECT * FROM images where dir='" . $dir . "'";
		$count = mysqli_num_rows(mysqli_query($link, $query));
		if ($count) {
			echo '<li><a href="gallery.php?dir=' . $dir . '&section=' . $section . '">' . $section . '</a>';
			echo '<span class="imgcount"> - (' . $count . ' images)</span>';
			echo '</li>';

		}
	}
	echo '</ul>';
	/* free result set */
	mysqli_free_result($result);
}
mysqli_close($link);
?>
