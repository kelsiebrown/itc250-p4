<?php
/**
 *
 * Category.php
 *
 * @package p4_Newsfeed
 * @authors Kelsie Brown <kelsie.brown@seattlecentral.edu>, Ge Jin <jinge920119@gmail.com>, Tran Duong <huyen-tran.duong@seattlecentral.edu>
 * @version 1.0 2018/08/08
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @todo update working app, check PSR-1 and PSR-2 compliance, final code review
 *
**/

namespace Feeds;

class Category {
    public $categoryID = 0;
    public $categoryName = '';
    
    function __construct($id, $name) {
        $this->categoryName = $name;
        $this->categoryID = $id;
    }
}
