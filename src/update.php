<?php
  // execute the git pull command
  $results = shell_exec("git pull");

  // print out the command result output
  echo "<pre>$results</pre>"
?>