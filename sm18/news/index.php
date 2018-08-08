<?php

require '../inc_0700/config_inc.php';

$config->titleTag = 'Updated news of three aspects';
$config->loadhead .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';

get_header();
echo '<h3 align="center">News List</h3>';

$prev = '<i class="fa fa-chevron-circle-left"></i>';
$next = '<i class="fa fa-chevron-circle-right"></i>';

//SQL Statements

$sql = "SELECT s.FeedID, s.Title, s.FeedURL, s.Description, a.Category, 
date_format(s.PubDate, '%W %D %M %Y %H:%i') 'DateAdded' FROM "
. PREFIX . "feedsP4 s, " . PREFIX . "categoriesP4 a WHERE s.CategoryID=a.CategoryID order by s.CategoryID desc";


# Create instance of new 'pager' class
$myPager = new Pager(10,'',$prev,$next,'');
$sql = $myPager->loadSQL($sql);  #load SQL, add offset
# connection comes first in mysqli (improved) function
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
    
if(mysqli_num_rows($result) > 0)
{#records exist - process
	
	while($row = mysqli_fetch_assoc($result))
	{# process each row
		echo '
			<div class="list-group" style="margin: auto; width: 50%;">
				<a href="' . VIRTUAL_PATH . 'surveys/feed_view.php?id=' . (int)$row['FeedID'] . '" class="list-group-item list-group-item-action flex-column align-items-start">
					<div class="d-flex w-100 justify-content-between">
						<h5 class="mb-1">' . dbOut($row['Title']) . '</h5>
						<small>' . dbOut($row['Category']) . '</small>
					</div>
					<p class="mb-1">' . dbOut($row['Description']) . '</p>
					<small>Click here for all the news!</small>
				</a>
			</div>
			<br/>
		';
	}
	echo $myPager->showNAV(); # show paging nav, only if enough records	 
}else{#no records
    echo "<div align=center>There are currently no feeds.</div>";	
}
@mysqli_free_result($result);
get_footer(); #defaults to theme footer or footer_inc.php
?>
