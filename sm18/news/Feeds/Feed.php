<?php
//Feed.php
namespace Feeds;

class Feed
{
	 public $FeedID = 0;
	 public $FeedURL = "";
	 public $Title = "";
	 public $Description = "";
	 public $PubDate = "";
	 public $TotalArticles= 0;
	 public $aFeed = Array();
	
    function __construct($id)
	{
		$this->FeedID = (int)$id;
		if($this->FeedID == 0){return FALSE;}
		
		$sql = sprintf("SELECT Title, FeedURL, Description FROM " . PREFIX . "feedsP4 Where FeedID =%d",$this->FeedID);
		
		#in mysqli, connection and query are reversed!  connection comes first
		$result = mysqli_query(\IDB::conn(),$sql) or die(trigger_error(mysqli_error(\IDB::conn()), E_USER_ERROR));
		if (mysqli_num_rows($result) > 0)
		{#Must be a valid survey!
			$this->isValid = TRUE;
			while ($row = mysqli_fetch_assoc($result))
			{#dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
			     $this->Title = dbOut($row['Title']);
			     $this->FeedURL = dbOut($row['FeedURL']);
			     $this->Description = dbOut($row['Description']);
			}
		}
		@mysqli_free_result($result);
		if(!$this->isValid){return;}
	}
	
	function showFeeds()
	{
		if($this->isValid)
        { #check to see if we have a valid FeedID
            echo '<br/>';
            $contents = file_get_contents($this->FeedURL);
            $xml = simplexml_load_string($contents);
            foreach($xml->channel->item as $story)
            {
                echo '<small>' . $story->pubDate . '</small><br />';
                echo '<a href="' . $story->link . '">' . $story->title . '</a><br />'; 
                echo '<p>' . $story->description . '</p>';
            }
        }else{
            echo "Sorry, no News!";	
        }
    }  
}