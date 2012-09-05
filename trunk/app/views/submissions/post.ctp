<?php
  if (isset($response)) {
		echo json_encode($response);
	} else {
		echo '{}';
	}
?>