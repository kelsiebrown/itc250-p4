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
	{#constructor sets stage by adding data to an instance of the object
		$this->FeedID = (int)$id;
		if($this->FeedID == 0){return FALSE;} //if no Feed cut the database export / kill
		
		#get Survey data from DB
		$sql = sprintf("SELECT Title, FeedURL, PubDate, Description from " . PREFIX . "feedsP4 Where FeedID =%d",$this->FeedID);
		
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
			     $this->PubDate = dbOut($row['PubDate']);
			}
		}
		@mysqli_free_result($result); #free resources
		
		if(!$this->isValid){return;}  #exit, as Feed is not valid
		
	}# end Feed() constructor
	

	function showFeeds()
	{
		if($this->FeedID > 0)
        {#be certain there are articles
			$myReturn = '';
			$contents = file_get_contents($this->FeedURL);
            
			foreach($this->aFeed as $article)
            {#print data for each 
				$myReturn = '<pre>';

				$contents = file_get_contents($this->FeedURL);
				echo $contents;
				
                '</pre>';

				// echo $question->QuestionID . " ";
				// echo $question->Text . " ";
				// echo $question->Description . "<br />";
				// #call showAnswers() method to display array of Answer objects
				// $question->showAnswers() . "<br />";
			}
		}else{
			echo "There are currently no questions for this survey.";	
        }
        return $myReturn;
    }# end showQuestions() method
    
}# end Survey class

