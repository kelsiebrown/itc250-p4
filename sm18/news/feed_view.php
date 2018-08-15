<?php
/**
 *
 * feed_view.php
 *
 * @package p4_Newsfeed
 * @authors Kelsie Brown <kelsie.brown@seattlecentral.edu>, Ge Jin <jinge920119@gmail.com>, Tran Duong <huyen-tran.duong@seattlecentral.edu>
 * @version 1.0 2018/08/08
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @todo update working app, check PSR-1 and PSR-2 compliance, final code review
 *
**/

require '../inc_0700/config_inc.php';

spl_autoload_register('MyAutoLoader::NamespaceLoader');
startSession();
# check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page

if(isset($_GET['id']) && (int)$_GET['id'] > 0){
	 $myID = (int)$_GET['id'];
}else{
	myRedirect(VIRTUAL_PATH . "feed.php");
}

$myFeed = new Feeds\Feed();
if(isset($_SESSION['feed']) && array_key_exists($myID, $_SESSION['feed'])) {
    $arr = $_SESSION['feed'][$myID];
    $time = $arr["time"];
    if(time() > $time + 600) {//session is over 10 mins, just clear
        $myFeed->setID($myID);
        unset($_SESSION['feed']);
        resetFeed($myFeed);
    } else {//rechieve data from session
        $myFeed->setFields($arr["ID"], $arr["URL"], $arr["Title"], $arr["Desc"],    $arr["valid"]);
    }
} else {//create new object and add into session
    $myFeed->setID($myID);
    resetFeed($myFeed);
}

function resetFeed($feed) {
    $myArr = ["ID" => $feed->FeedID, 
              "URL" => $feed->FeedURL, 
              "Title" => $feed->Title, 
              "Desc" => $feed->Description,
              "valid" => $feed->isValid,
              "time" => time()];
    $_SESSION['feed'][$feed->FeedID] = $myArr;
}

if($myFeed->isValid)
{
	$config->titleTag = "'" . $myFeed->Title . "' Feed!";
}else{
	$config->titleTag = smartTitle(); //use constant 
}
#END CONFIG AREA ---------------------------------------------------------- 

get_header();
?>
<h3><?=$myFeed->Title;?></h3>

<?php

$myFeed->showFeeds();

get_footer();
