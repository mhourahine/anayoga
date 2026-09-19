<?
include("adminfunc.php.inc");

if ($_GET['id'] == "") {
    echo "Error: A category ID must be provided.";
    exit;
}

connectDB();
$qryDelCat = "delete from categories where catID = $id;";
if (! mysql_query($qryDelCat)) {
    echo "An error has occurred while attempting to delete this category.";
    echo "MySQL returned: " . mysql_error();
    exit;
}

header("Location: categories.php");
?>