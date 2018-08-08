<?php
/***
*
*TODO: Call Feed Class to get the $myFeed to work 
*
**/
 
require '../inc_0700/config_inc.php';
spl_autoload_register('MyAutoLoader::NamespaceLoader');//required to load SurveySez namespace objects
$config->metaRobots = 'no index, no follow';#never index survey pages

# check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "feed.php");
}

$myFeed = new feed\Feed($myID); //Example from survey_view 
if($myFeed->isValid)
{
	$config->titleTag = "'" . $myFeed->Title . "' Feed!";
}else{
	$config->titleTag = smartTitle(); //use constant 
}
#END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to theme header or header_inc.php
?>
<h3><?=$myFeed->Title;?></h3>

<?php

if($myFeed->isValid)
{ #check to see if we have a valid FeedID
	echo '<br/>';
	$contents = file_get_contents("$myFeed->FeedURL");
	$xml = simplexml_load_string($contents);
    foreach($xml->channel->item as $story)
  	{
		echo '<small>' . $myFeed->PubDate . '</small><br />';
		echo '<a href="' . $story->link . '">' . $story->title . '</a><br />'; 
		echo '<p>' . $story->description . '</p>';
    	
  	}
}else{
	echo "Sorry, no News!";	
}

get_footer(); #defaults to theme footer or footer_inc.php

