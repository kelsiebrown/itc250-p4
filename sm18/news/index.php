<?php
require '../inc_0700/config_inc.php';

spl_autoload_register('MyAutoLoader::NamespaceLoader');
$config->titleTag = 'Updated news of three aspects';
$config->loadhead .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';

get_header();
echo '<h3 align="center">News List</h3>';

$prev = '<i class="fa fa-chevron-circle-left"></i>';
$next = '<i class="fa fa-chevron-circle-right"></i>';

//TODO: Extra. Use cache to avoid communication with database continuously.

$myPager = new Pager(10,'',$prev,$next,'');

$sql = "SELECT CategoryID, Category FROM " . PREFIX . "categoriesP4";
$sql = $myPager->loadSQL($sql);
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
$categories = array();
if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $category = new Feeds\Category((int)$row['CategoryID'], $row['Category']);
        array_push($categories, $category);
    }
}
@mysqli_free_result($result);

foreach($categories as $category) {
    $sql = "SELECT s.FeedID, s.Title, s.FeedURL, s.Description FROM " . PREFIX . "feedsP4 s WHERE s.CategoryID=$category->categoryID";
    $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
    
    echo "<h4 align='center'>$category->categoryName<h4>";
    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_assoc($result))
        {
            echo '
                <div class="list-group" style="margin: auto; width: 50%;">
                    <a href="' . VIRTUAL_PATH . 'news/feed_view.php?id=' . (int)$row['FeedID'] . '" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">' . dbOut($row['Title']) . '</h5>
                        </div>
                        <p class="mb-1">' . dbOut($row['Description']) . '</p>
                        <small>Click here for all the news!</small>
                    </a>
                </div>
                <br/>
            ';
        }
        echo $myPager->showNAV(); 
    }else{
        echo "<div align=center>There are currently no feeds.</div>";	
    }
    @mysqli_free_result($result);
}
get_footer();
?>