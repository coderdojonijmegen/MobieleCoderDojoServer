<html>
	<head>
		<title>Instructies</title>
                <style>
                        body {
                                font-family: sans-serif;
                                background-color: #EEE;
                        }
                        div#container {
                                width: 1000px;
                                margin: auto;
                                background-color: white;
                                padding: 20px;
                                border: 1px solid #CCC;
                                text-align: center;
                                box-shadow: 4px 4px 10px 2px #c7c7c7b8;
                        }
			li {
				list-style: none;
			}
                </style>
	</head>
	<body>
		<div id="container">
			<h1>Instructies</h1>
			<ul>
			<?php listDocDirs(getDocDirs()); ?>
			</ul>
		</div>
	</body>
</html>

<?php


function listDocDirs($docDirs) {
	foreach($docDirs as $key => $value) {
		echo "<li><a href=\"/docs/".$value."\">".$value
			."</a></li>";
	}
}

function getDocDirs() {
	$dir = ".";
	$filesAndDirs = scandir($dir);

	$docDirs = array();
	foreach ($filesAndDirs as $key => $value) {
		if (!in_array($value, array(".", "..")) && is_dir($dir . DIRECTORY_SEPARATOR . $value) && $value != ".htmp") {
			$docDirs[] = $value;
		}
	}
	return $docDirs;
}
?>
