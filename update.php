<?php
  // execute the git pull command and print out the result
  echo shell_exec("/usr/bin/git pull 2>&1");
?>