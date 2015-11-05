<?php

/**
* Loads and caches supported categories from `/spb/data/categories.txt`
* into an array and returns it.
*
* 1 line = 1 category = 1 prayer.
*
* - Empty lines are ignored.
* - Categories are sorted alphabetically.
*
* @return string[]
*/
function getCategories(){
    static $categories; // cache
    if (!isset($categories)) {
	    $categories = file('../data/categories.txt',FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
        asort($categories);
    }
    return $categories;
}

/**
* Generates and outputs HTML for category dropdown.
*/
function addCategoriesComboBox($id, $class = 'categories'){
    echo "<select id='$id' class='$class'>";
	echo '<option value="ANY">ANY</option>';
    foreach (getCategories() as $cat) {
		echo "<option value='$cat'>$cat</option>";
    }
    echo '</select>';
}

class Database {
        public function __construct($name) {
                $this->d = new SQLite3($name);
        }
        public function query($query) {
                return $this->d->query($query);
        }
        public function queryOne($query) {
                $a = $this->query($query);
                return $a->fetchArray(SQLITE3_NUM);
        }
        public function queryFirstEach($query) {
                $a = $this->query($query);
                $b = array();
                while ($c = $a->fetchArray(SQLITE3_NUM)) {
                        $b[] = $c[0];
                }
                return $b;
        }
}
