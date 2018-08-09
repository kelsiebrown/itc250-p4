<?php
//Category.php

namespace Feeds;

class Category {
    public $categoryID = 0;
    public $categoryName = '';
    
    function __construct($id, $name) {
        $this->categoryName = $name;
        $this->categoryID = $id;
    }
}