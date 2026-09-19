<?
/*
reorder.php - Will move the desired category/product up/down one step in 
order index

Get request parameters:
catID - specifies which category to change (optional if product change)
prodID - specifies which product to change
direction - string to specify direction to move: "up" or "down"
type - string to specify category or product

*/
include("adminfunc.php.inc");

if (! ($_GET["direction"] && $_GET["type"])) {
	echo "Error: A step number and direction must be specified";
	exit;
}

connectDB();
if ($_GET["type"] == "category") { //process category move
	
	//find index of given ID
	$qryGetIndex = "select orderIndex from categories where catID=".$_GET['catID'].";";
	if (! $result = mysql_query($qryGetIndex)) {
        echo "An error occurred while querying the categories table.<br>";
        echo "MySQL returned: ".mysql_error();
        exit;
	}
	$row = mysql_fetch_assoc($result);
	$current_index = $row['orderIndex'];
	
	//if item is first and direction is up then do nothing
	//if item is last and direction is down then do nothing
	if (! (($current_index == 1 && $_GET["direction"] == "up")
	     || ($current_index==getMaxCategoryIndex() && $_GET["direction"]=="down"))) { 
		
		//find ID of item adjacent desired item
		if ($_GET["direction"] == "up") {
			$new_index = $current_index - 1;
		} else {
			$new_index = $current_index + 1;
		}
		
		$qryGetID = "select catID from categories where orderIndex=$new_index;";
		if (! $result = mysql_query($qryGetID)) {
	        echo "An error occurred while querying the categories table.<br>";
	        echo "MySQL returned: ".mysql_error();
	        exit;
		}
		$row = mysql_fetch_assoc($result);
		$adjacentID = $row['catID'];
		
		//update index of given ID
		$qryUpdate = "update categories set orderIndex=$new_index where catID=".$_GET['catID'].";";
		if (! mysql_query($qryUpdate)) {
	    	echo "An error has occurred updating category.<br>\n";
	    	echo "Query: $qryUpdate";
	    	echo "MySQL returned: ".mysql_error();
		}
		
		//update index of adjacent item
		$qryUpdate = "update categories set orderIndex=$current_index where catID=$adjacentID;";
		if (! mysql_query($qryUpdate)) {
	    	echo "An error has occurred updating category.<br>\n";
	    	echo "Query: $qryUpdate";
	    	echo "MySQL returned: ".mysql_error();
		}
	}		
} elseif ($_GET["type"] == "product") {
		
	//find index and category of given ID
	$qryGetIndex = "select orderIndex,category from products where prodID=".$_GET["prodID"].";";
	if (! $result = mysql_query($qryGetIndex)) {
        echo "An error occurred while querying the products table.<br>";
        echo "MySQL returned: ".mysql_error();
        exit;
	}
	$row = mysql_fetch_assoc($result);
	$current_index = $row['orderIndex'];
	$category = $row['category'];
	
	//if item is first and direction is up then do nothing
	//if item is last and direction is down then do nothing
	if (! (($current_index == 1 && $_GET["direction"] == "up")
	     || ($current_index==getMaxProductIndex($category) && $_GET["direction"]=="down"))) { 
		
		//find ID of item adjacent desired item
		if ($_GET["direction"] == "up") {
			$new_index = $current_index - 1;
		} else {
			$new_index = $current_index + 1;
		}
		
		$qryGetID = "select prodID from products where orderIndex=$new_index AND category=$category;";
		if (! $result = mysql_query($qryGetID)) {
	        echo "An error occurred while querying the products table.<br>";
	        echo "MySQL returned: ".mysql_error();
	        exit;
		}
		$row = mysql_fetch_assoc($result);
		$adjacentID = $row['prodID'];
		
		//update index of given ID
		$qryUpdate = "update products set orderIndex=$new_index where prodID=".$_GET['prodID'].";";
		if (! mysql_query($qryUpdate)) {
	    	echo "An error has occurred updating category.<br>\n";
	    	echo "Query: $qryUpdate";
	    	echo "MySQL returned: ".mysql_error();
		}
		
		//update index of adjacent item
		$qryUpdate = "update products set orderIndex=$current_index where prodID=$adjacentID;";
		if (! mysql_query($qryUpdate)) {
	    	echo "An error has occurred updating category.<br>\n";
	    	echo "Query: $qryUpdate";
	    	echo "MySQL returned: ".mysql_error();
		}
	}		
		
		
} else {
	echo "Error: A category ID or product ID must be provided.";
	exit;	
}


if ($_SERVER['HTTP_REFERER']) {
	header("Location: ".$_SERVER['HTTP_REFERER']);
} else {
	echo "Item has been moved.";	
}

?>