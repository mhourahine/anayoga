<?
include("adminfunc.php.inc");

if (! $_POST["update"]) {
	//opening category to edit
	$catID = $_GET["id"];
	
	//get category information from database
	if ($_GET['id'] == "") {
    	echo "Error: A category ID must be provided.";
  	  	exit;
	}

	connectDB();

	//get category name
	$qryCatTitle = "select * from categories where catID=".$_GET['id'].";";
	if (! $result = mysql_query($qryCatTitle)) {
        echo "An error occurred while querying the categories table.<br>";
        echo "MySQL returned: ".mysql_error();
        exit;
	}
	$row = mysql_fetch_assoc($result);
	
	//display form with controls filled in
	if ($row) {
		$title = $row['title'];
		$description = $row['description'];
	} else {
		$title = "#error";
		$description = "#error";
	}
	
	include("top.php");
?>

    <form action=editcat.php method=post>
    <input type=hidden name=update value=true>
    <input type=hidden name=id value=<? echo $_GET['id']; ?>>
    <table>
        <tr>
            <td>Title: <input type=text size=20 name=cattitle value="<? echo $title; ?>"></td>
        </tr>
        <tr>
            <td valign=top>Description:<br><textarea name=catdesc cols=20 rows=5><? echo $description; ?></textarea></td>
        </tr>
        <tr>
            <td align=center><input type=submit name=submit value="Save Changes"></td>
        </tr>
    </table>
    </form>

<?
	include("bottom.php");
	
} else {
	//saving from edit	
	$id = $_POST["id"];
	$title = $_POST["cattitle"];
	$description = $_POST["catdesc"];
	
	
	connectDB();
	$qryUpdate = "update categories set title=\"$title\",description=\"$description\" where catID=$id;";
	if (! mysql_query($qryUpdate)) {
    	echo "An error has occurred updating category.<br>\n";
    	echo "MySQL returned: ".mysql_error();
	}

	header("Location: categories.php");
}

?>