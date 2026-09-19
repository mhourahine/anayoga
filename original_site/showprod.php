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

//connect to database
$db = mysql_connect($dbhost, $dbusername, $dbpasswd);
if (! $db) {
    echo "Error: Unable to connect to database.";
    exit;
}
mysql_select_db($dbname);

$qryProd = "select * from products where prodID=$id;";
if (! $result = mysql_query($qryProd)) {
	echo "<P>An error has occured while attempting to query the product table.";
	echo "<p>The following error was returned: " . mysql_error();
	exit;
}

if (mysql_num_rows($result) != 1) {
        echo "<p>Product not found.";
        include("bottom.php");
        exit;
}

$row = mysql_fetch_assoc($result);

//get category name
$qryCat = "select * from categories where catID=".$row['category'].";";
if (! $result = mysql_query($qryCat)) {
	echo "<P>An error has occured while attempting to query the category table.";
	echo "<p>The following error was returned: " . mysql_error();
	exit;
}
$catinfo = mysql_fetch_assoc($result);

//check to ensure product image is entered correctly otherwise use spacer
if (($row['imgFileName'] == "") | (! file_exists($prodImgPath."/".$row['imgFileName']))) {
    $img = "product_spacer.jpg";
} else {
    $img = $row['imgFileName'];
}

//check to ensure product image is entered correctly otherwise use spacer
if (($row['imgDetailFileName'] == "") | (! file_exists($prodImgPath."/".$row['imgDetailFileName']))) {
    $detailImg = "product_spacer.jpg";
    $one_image = 1;
} else {
    $detailImg = $row['imgDetailFileName'];
}


?>

<br>
<p>Category: <a href="showcat.php?id=<? echo $row['category']; ?>"><? echo $catinfo['title']; ?></a>
<br><br>
<table class=prodtable width=100% border=1 cellpadding=5 cellspacing=0>
<tr>
    <td colspan=2 class=prodcell><b>Product:</b> <? echo $row['name']; ?></td>
</tr>
<tr>
	<td colspan=2 class=prodcell><b>Description:</b> <? echo $row['description']; ?></td>
</tr>
<tr>
<?
	if ($one_image) {
	?>
    	<td colspan=2 class=prodcell align=center><a href="product_imgs/<? echo $img; ?>"><img src="<? echo "thumb.php?img=$img&scale=0.2"; ?>"><br><font size="-2"><i><center>Click to enlarge.</center></i></font></a></td>
    <?
    } else {
    ?>
    <td class=prodcell align=center><a href="product_imgs/<? echo $img; ?>"><img src="<? echo "thumb.php?img=$img&scale=0.2"; ?>"><br><font size="-2"><i><center>Click to enlarge.</center></i></font></a></td>
    <td class=prodcell align=center><a href="product_imgs/<? echo $detailImg; ?>"><img src="<? echo "thumb.php?img=$detailImg&scale=0.2"; ?>"><br><font size="-2"><i><center>Click to enlarge.</center></i></font></a></td>
    <?
    }
    ?>
</tr>
<tr>
    <td class=prodcell colspan=2><center><b>Price: </b><? echo "$".number_format($row['price'], 2,'.',''); ?></td>
</tr>
</table>

<?
include("bottom.php");
?>