<?php

$output = array();

//exec("svn cleanup");
exec("svn update /home3/theracco/public_html/revolutioncycles.net", $output, $ret_val);

?><h1>SVN Update</h1>

<h2>Return Value: <?php echo $ret_val; ?></h2>

<h2>Output:</h2>

<pre>
<?php

foreach ($output as $line) {
	print "$line\n";
}

?>
</pre>
<?php


?>