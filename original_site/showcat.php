<?
include("global.php.inc");
include("top.php");

//ensure info is passed via GET
if (! $_GET['id']) {
    echo "<P>An error has occurred.";
    include("bottom.php");
    exit;
}
$id = $_GET['id'];

//initialize connection to database
connectDB();

//get category information
$qryCat = "select * from categories where catID=$id;";
if (! $result = mysql_query($qryCat)) {
	echo "<P>An error has occured while attempting to query the category table.";
	echo "<p>The following error was returned: " . mysql_error();
	exit;
}

if (mysql_num_rows($result) != 1) {
        echo "<p>Category not found.";
        include("bottom.php");
        exit;
}
$catinfo = mysql_fetch_assoc($result);

//get product info
$qryProd = "select * from products where category=$id order by orderIndex;";
if (! $products = mysql_query($qryProd)) {
	echo "<P>An error has occured while attempting to query the category table.";
	echo "<p>The following error was returned: " . mysql_error();
	exit;
}

echo "<p><a href=\"products.php\">Back to Products</a>";
echo "<br><h2>Category: ".$catinfo['title']."</h2>\n";
echo "<p>".stripslashes($catinfo['description'])."\n";

//build product table
echo "<br><br><center><table class=prodtable cellpadding=5 cellspacing=0 border=1 width=350>\n";
while ($prod = mysql_fetch_assoc($products)) {
	//check to ensure product image is entered correctly otherwise use spacer
	if (($prod['imgFileName'] == "") | (! file_exists($prodImgPath."/".$prod['imgFileName']))) {
	    $img = "product_spacer.jpg";
	} else {
	    $img = $prod['imgFileName'];
	}
    echo "<tr><td width=20% class=prodcell align=center><img src=\"thumb.php?img=$img&scale=0.1\"></td><td class=prodcell><a href=\"showprod.php?id=".$prod['prodID']."\">".$prod['name']."</a></td><td width=20% class=prodcell>$".number_format($prod['price'], 2, '.', '')."</td></tr>\n";
}
echo "</table></center>\n";


include("bottom.php");
?>