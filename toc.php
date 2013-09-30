<?php
//echo 'toc<br>';
include 'common.php';
$query = "SELECT * FROM sections";
if ($result = mysqli_query($link, $query)) {
	echo '<ul class="toc">';
	while ($row = mysqli_fetch_assoc($result)) {
		$dir = $row['dir'];
		$section = $row['section'];
		$query = "SELECT * FROM images where dir='" . $dir . "'";
		$count = mysqli_num_rows(mysqli_query($link, $query));
		if ($count) {
			echo '<a href="gallery.php?dir=' . $dir . '">';
			echo '<li>' . $section;
			echo '<small> - (' . $count . ' images)</small>';
			echo '</li>';
			echo '</a>';
		}
	}
	echo '</ul>';
	/* free result set */
	mysqli_free_result($result);
}
mysqli_close($link);
?>
