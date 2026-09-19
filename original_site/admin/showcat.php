<?
include("adminfunc.php.inc");
?>

<HTML>
<HEAD>
<TITLE>AnaYoga.com</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK href="../main.css" rel="stylesheet" type="text/css">
<? showConfirmScript(); ?>
</HEAD>
<BODY BGCOLOR=#FFFFFF LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>

<?
if ($_GET['id'] == "") {
    echo "Error: A category ID must be provided.";
    exit;
}

connectDB();

//get category name
$qryCatTitle = "select title from categories where catID=".$_GET['id'].";";
if (! $result = mysql_query($qryCatTitle)) {
        echo "An error occurred while querying the categories table.<br>";
        echo "MySQL returned: ".mysql_error();
        exit;
}
$row = mysql_fetch_assoc($result);
?>
<h2>Category: <? echo $row['title']; ?></h2>
<?

listProducts($_GET['id']);

?>

</BODY>
</HTML>