<?
include("adminfunc.php.inc");

if ($_GET['id'] == "") {
    echo "Error: A product ID must be provided.";
    exit;
}

connectDB();
$qryDelProd = "delete from products where prodID = $id;";
if (! mysql_query($qryDelProd)) {
    echo "An error has occurred while attempting to delete this product.";
    echo "MySQL returned: " . mysql_error();
    exit;
}

header("Location: showcat.php?id=".$_GET['category']);
?>