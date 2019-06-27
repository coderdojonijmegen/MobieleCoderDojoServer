<?php
include "shell.php";
processActions();
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="mobile-web-app-capable" content="yes">
		<style>
			body {
				font-family: sans-serif;
				background-color: #EEE;
			}
			div#container {
				max-width: 1000px;
				margin: auto;
				background-color: white;
				padding: 20px;
				border: 1px solid #CCC;
				text-align: center;
				box-shadow: 4px 4px 10px 2px #c7c7c7b8;
			}
			div#container .title {
				font-weight: bold;
				font-size: 3vw;
			}
			button {
			}
			div#power button {
				background-color: red;
				color: white;
				font-weight: bold;
			}
			div#networks {
				overflow-x:auto;
			}
			table {
				width: 100%;
				margin: auto;
				border-collapse: collapse;
				border-spacing: 0;
				border: 1px solid #ddd;
			}
			th {
				text-align: left;
			}
			tr:nth-child(even) {
				background-color: #f2f2f2;
			}
			input {
			}
			@media screen and (max-width: 600px) {
				body, button, table, input {
					font-size: 5vw;
				}
				button {
					margin: 1vw;
					padding: 1vw;
				}
				div#container .title {
					font-size: 10vw;
				}
			}
		</style>
	</head>
	<body>
		<div id="container">
			<span class="title">Mobiele CoderDojo Server</span>
			<div id="doc_mgmnt">
				<h1>Instructies</h1>
				<h2>Aanwezig</h2>
				<table>
					<thead><tr><th>instructies</th><th>acties</th></tr></thead>
					<tbody>
					<?php listDocDirs(getDocDirs()); ?>
					</tbody>
				</table>
				<h2>Nieuw toevoegen</h2>
				<input type="text" id="reponaam" placeholder="repository naam" /> <button onclick="post('/mgmnt/', { action: 'add', repo: document.getElementById('reponaam').value });">toevoegen</button>
			</div>
			<div id="wifi_mgmnt">
				<h1>Wifi</h1>
				<a href="/mgmnt/">ververs</a>
				<div id="networks">
					<table>
						<thead><tr><th>naam</th><th>signaal</th><th>snelheid</th><th>beveiliging</th><th>actie</th></tr></thead>
						<tbody>
						<?php listWifiNetworks(getWifiNetworks()); ?>
						</tbody>
					</table>
				</div>
			</div>
			<div id="power">
				<h1>MCS herstarten of afsluiten</h1>
				<button onclick="post('/mgmnt/', { action: 'restart' });">herstarten</button> <button onclick="post('/mgmnt/', { action: 'shutdown' });">afsluiten</button>
			</div>
		</div>
	</body>
	<script>
		function post(path, keyValues, method='post') {
			const form = document.createElement('form');
			form.method = method;
			form.action = path;
			for (const key in keyValues) {
				if (keyValues.hasOwnProperty(key)) {
					const hiddenField = document.createElement('input');
					hiddenField.type = 'hidden';
					hiddenField.name = key;
					hiddenField.value = keyValues[key];
					form.appendChild(hiddenField);
				}
			}
			document.body.appendChild(form);
			form.submit();
		}
	</script>
</html>


<?php

function processActions() {
	if (array_key_exists("action", $_POST)) {
		$action = $_POST["action"];
		if ($action === "refresh" || $action === "add") {
			$repo = $_POST["repo"];
			updateDocs($repo);
		} else if ($action == "delete") {
			$repo = $_POST["repo"];
			deleteDocs($repo);
		} else if ($action == "connect") {
			$network = trim($_POST["network"]);
                        $password = empty($_POST["password"])? false: $_POST["password"];
			connectToWifiNetwork($network, $password);
		} else if ($action == "disconnect") {
			disconnectWifi();
		} else if ($action == "restart") {
			restartServer();
		} else if ($action == "shutdown") {
			shutdownServer();
		} else {
			echo "actie ".$action." is onbekend!";
		}
	}
}

function listDocDirs($docDirs) {
	foreach($docDirs as $key => $value) {
		echo "<tr><td><a href=\"/docs/".$value."\">".$value
			."</a></td><td><button onclick=\"post('/mgmnt/', { action: 'refresh', repo: '".trim($value)."' });\">werk bij</button> "
			."<button onclick=\"post('/mgmnt/', { action: 'delete', repo: '".$value."' });\">verwijder</button></td></tr>";
	}
}

function updateDocs($repoName) {
	$shell = new Shell();

	$results = array();
	$results['repoName'] = $repoName;
	$results['delete .htmp/'] = $shell->exec("rm -rf .htmp/");
	$results['clone repo'] = $shell->exec("git clone https://github.com/coderdojonijmegen/".$repoName.".git ".dirname(__FILE__)."/.htmp/");
	$results['delete old docs'] = $shell->exec("rm -rf ../docs/".$repoName."/");
	$results['publish new docs'] = $shell->exec("cp -r .htmp/docs/ ../docs/".$repoName."/");

	printResultsIfError($results);
}

function deleteDocs($repoName) {
	$shell = new Shell();

	$results = array();
	$results['repoName'] = $repoName;
	$results['delete docs'] = $shell->exec("rm -rf ../docs/".$repoName."/");

	printResultsIfError($results);
}

function printResultsIfError($results) {
	$hasErrorOccurred = false;
	foreach($results as $key => $result) {
		if (is_array($result) && $result['returnCode'] !== 0) {
			$hasErrorOccurred = true;
		}
	}
	if ($hasErrorOccurred) {
		echo "<pre>";
		print_r($results);
		echo "</pre>";
	}
}

function getDocDirs() {
	$dir = "../docs/";
	$filesAndDirs = scandir($dir);

	$docDirs = array();
	foreach ($filesAndDirs as $key => $value) {
		if (!in_array($value, array(".", "..")) && is_dir($dir . DIRECTORY_SEPARATOR . $value) && $value != ".htmp") {
			$docDirs[] = $value;
		}
	}
	return $docDirs;
}

function getWifiNetworks() {
	$shell = new Shell();
	$networks = array();
	$result = $shell->exec("nmcli device wifi");
	if ($result['errorCode'] === false) {
		echo "<pre>";
		print_r($result);
		echo "</pre>";
		return;
	} else {
		echo "<pre>";
		foreach($result['output'] as $key => $network) {
			$re = '/(\*){0,1}[ ]+([\w -\/]+)[ ]{2,}Infra[ ]*([\d]+)[ ]*([\d]+ Mbit\/s)+[ ]*([\d]+)[^WPAE128-]+([WEPA1280.X -]+)/';
			preg_match_all($re, $network, $matches, PREG_SET_ORDER, 0);
			if (!empty($matches)) {
				$networks[] = array(
					"isConnected" => !empty($matches[0][1]),
					"name" => trim($matches[0][2]),
					"channel" => $matches[0][3],
					"bandwidth" => $matches[0][4],
					"signalStrength" => $matches[0][5],
					"security" => $matches[0][6] == "--"? "": $matches[0][6]
				);
			}
		}
	}
	return $networks;
}

function listWifiNetworks($networks) {
	foreach($networks as $key => $network) {
		echo "<tr><td>".$network['name']."</td><td>".$network['signalStrength']."</td><td>".$network['bandwidth']."</td><td>".$network['security']."</td><td>";
		if ($network['isConnected']) {
			echo "<button onclick=\"post('/mgmnt/', { action: 'disconnect', network: '".$network['name']."' });\">verbreek verbinding</button>";
		} else if (!$network['isConnected']) {
			echo "<button onclick=\"post('/mgmnt/', { action: 'connect', network: '".$network['name']."', password: document.getElementById('network_password').value });\">verbind</button>";
			if (!empty($network['security'])) {
				echo " <input type=\"text\" id=\"network_password\" placeholder=\"netwerk wachtwoord\" />";
			}
		}
		echo "</td></tr>";
	}
}

function disconnectWifi() {
	$shell = new Shell();

	$results = array();
	$results['repoName'] = $repoName;
	$results['disconnect wifi'] = $shell->exec("sudo nmcli device disconnect wlp0s20f3");

	printResultsIfError($results);
}

function connectToWifiNetwork($network, $password = false) {
	$shell = new Shell();

	$results = array();
	$results['network'] = $network;
	$results['password'] = $password;
	$results['connect wifi'] = $shell->exec("sudo nmcli device wifi connect \"".$network."\" ". ($password == false? "": "password \"".$password."\"")." && sleep 10");

	printResultsIfError($results);
}

function restartServer() {
	$shell = new Shell();

	$results = array();
	$results['restart server'] = $shell->exec("sudo reboot now");

	printResultsIfError($results);
}

function shutdownServer() {
	$shell = new Shell();

	$results = array();
	$results['shutdown server'] = $shell->exec("sudo shutdown now");

	printResultsIfError($results);
}
?>
