<?php

class Shell {

        public function exec($command) {
		$command .= " 2>&1";
		$output = array();
                exec($command, $output, $returnCode);
                return array("returnCode" => $returnCode, "output" => $output, "command" => $command);
        }
}

//$shell = new Shell();
//print_r($shell->exec("ls"));
?>
