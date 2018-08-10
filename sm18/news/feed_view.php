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
# check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){
	 $myID = (int)$_GET['id'];
}else{
	myRedirect(VIRTUAL_PATH . "feed.php");
}

$myFeed = new Feeds\Feed($myID);
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
//if($myFeed->isValid)
//{ #check to see if we have a valid FeedID
//	echo '<br/>';
//	$contents = file_get_contents($myFeed->FeedURL);
//	$xml = simplexml_load_string($contents);
//    foreach($xml->channel->item as $story)
//  	{
//		echo '<small>' . $story->pubDate . '</small><br />';
//		echo '<a href="' . $story->link . '">' . $story->title . '</a><br />'; 
//		echo '<p>' . $story->description . '</p>';
//  	}
//}else{
//	echo "Sorry, no News!";	
//}
get_footer();
