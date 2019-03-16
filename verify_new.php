<?php
session_start();
// Load Framework & Config Files
require_once ("libraries/TeamSpeak3/TeamSpeak3.php");
require_once('config.php');
// connect to TS3 Server via Server Query
$ts3_VirtualServer = TeamSpeak3::factory("serverquery://".$config['username'].":".$config['password']."@".$config['ip'].":".$config['queryport']."/?server_port=".$config['serverport']."&nickname=".$config['botname']."");
//Set Step Initial
$step = 0;

//Step1
if (isset($_REQUEST['select']))
	{
	$uid_dbid_id = $_REQUEST['select'];
	$uid_dbid_id_array = explode(" ", $uid_dbid_id);
	$uid = $uid_dbid_id_array[0];
	$dbid = $uid_dbid_id_array[1];
	$id = $uid_dbid_id_array[2];
	$name = $uid_dbid_id_array[3];
	$_SESSION["uid"] = $uid;
	$_SESSION["dbid"] = $dbid;
	$_SESSION["id"] = $id;
	$_SESSION["name"] = $name;
	$length = 5;
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++)
		{
		$randomString.= $characters[rand(0, $charactersLength - 1) ];
		}

	$_SESSION["code"] = $randomString;
	$ts3_VirtualServer->clientGetByUid($uid)->poke("VC: [b][color=blue]" . $randomString . "[/color][/b]");
	unset($_REQUEST['select']);
	$step = 1;
	}

//Step 2
if (isset($_REQUEST['code']))
	{
	$code = $_REQUEST['code'];
	$dbid = $_SESSION['dbid'];
	$id = $_SESSION['id'];
	$name = $_SESSION['name'];
	$channel_name = $name . "'s Channel";
	if ($_SESSION['code'] == $code)
		{
		$ts3_VirtualServer->serverGroupClientAdd($config['vservergroup'], $dbid);
		if ($config['channel']==TRUE) {
			$cid = $ts3_VirtualServer->channelCreate(array(
				"channel_name" => $channel_name,
				"channel_topic" => "User successfuly validated their account",
				"channel_codec" => TeamSpeak3::CODEC_OPUS_VOICE,
				"channel_flag_permanent" => TRUE,
			));
			$ts3_VirtualServer->clientSetChannelGroup($dbid, $cid, $config['vchannelgroup']);
			$ts3_VirtualServer->clientMove($id, $cid, null);
		}
		unset($_REQUEST['code']);
		header("Location: ".$config['vfile']);
		}
	  else
		{
		$step = 0;
		unset($_REQUEST['code']);
		header("Location: ".$config['vfile']);
		}
	}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo($config['sitename']) ?></title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <div class="vspacer"></div>
    <div class="info">
        <div class="name"><?php echo($config['sitename']) ?> - Verify</div>
        <div class="icons">
        <?php

if ($step == 0)
	{
	echo '<form method="post" action="verify_new.php">
    		<select name="select">';
	foreach($ts3_VirtualServer->clientList() as $tsClient)
		{
		if (!$tsClient->getInfo() ['client_type'])
			{
			$groups = $tsClient->getInfo() ['client_servergroups'];
			$groups_array = explode(",", $groups);
			if ((in_array($config['vservergroup'], $groups_array)) == FALSE)
				{
				echo '<option value="' . $tsClient->getInfo() ['client_unique_identifier'] . ' ' . $tsClient->getInfo() ['client_database_id'] . ' ' . $tsClient->getInfo() ['clid'] . ' ' . $tsClient->getInfo() ['client_nickname'] . '">' . $tsClient->getInfo() ['client_nickname'] . '</option>';
				}
			}
		}

	echo '
			</select>
			<button type="submit">
    			<i class="fas fa-check"></i>
			</button>
		</form>
		';
	} ?>


		<?php

if ($step == 1)
	{
	echo '
        <form method="post" action="verify_new.php">
            <input type="text" name="code" placeholder="Enter Verification Code...">
			<button type="submit">
    			<i class="fas fa-check"></i>
			</button>
		</form>
		';
	} ?>
        </div>
    </div>
    <div class="vspacer"></div>
    <div class="copyright">© 2019 - Made with <i class="fas fa-heart"></i> by <a href="dbarrett.uk">Dan B</a></div>
</body>
</html>