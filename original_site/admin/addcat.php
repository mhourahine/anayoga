<?
include("adminfunc.php.inc");

if (! $_POST["adding"]) {  //form display
	
	include("top.php");
	?><h2>Add Category</h2><?
 	showAddCategory();
 	include("bottom.php");
 	
} else {  //form processing

	$title = $_POST['cattitle'];
	$description = $_POST['catdesc'];
	
	//add category to database
	connectDB();
	
	//find out max order index
	$qryOrderIndex = "SELECT max( orderIndex ) AS maxOrder FROM categories";
	if (! $result = mysql_query($qryOrderIndex)) {
	    echo "An error has occurred adding a category to the database.<br>\n";
	    echo "MySQL returned: ".mysql_error();
	}
	$row = mysql_fetch_assoc($result);
	$orderIndex = $row['maxOrder'];
	$orderIndex++;
	
	$qryAddCat = "insert into categories (title,description,orderIndex) values ('$title','$description',$orderIndex);";
	if (! mysql_query($qryAddCat)) {
	    echo "An error has occurred adding a category to the database.<br>\n";
	    echo "MySQL returned: ".mysql_error();
	}
	
	header("Location: categories.php");
}
?>