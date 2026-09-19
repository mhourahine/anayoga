<?

/* Contact info */
$anayoga_email = "info@anayoga.com";

/* Database Information */
$dbhost = "db";
$dbname = "anayoga_anayoga";
$dbusername = "anayoga_catalog";
$dbpasswd = "acc3ss";

/* common variables */
$prodImgPath = "/var/www/html/product_imgs";

/* set local info */
setlocale(LC_MONETARY, 'en_US');

function connectDB() {
    global $dbhost,$dbname,$dbpasswd,$dbusername;

    //connect to database
    $db = mysql_connect($dbhost, $dbusername, $dbpasswd);
    if (! $db) {
        echo "Error: Unable to connect to database.";
        exit;
    }
    mysql_select_db($dbname);
    
}

function showCategories() {

    //connect to database
    connectDB();
    
    echo "<h2>Categories</h2>";
    
    $qryCategories = "SELECT categories.catID, categories.title, categories.description, categories.orderIndex,count( products.prodID ) as numProducts  FROM categories LEFT  JOIN products ON categories.catID = products.category GROUP BY categories.catID, categories.title, categories.description ORDER BY categories.orderIndex ASC";
    if (! $result = mysql_query($qryCategories)) {
	echo "<P>An error has occured while attempting to query the categories table.";
	echo "<p>The following error was returned: " . mysql_error();
	exit;
    }
    
    echo "<table cellspacing=10>\n";
    while ($row = mysql_fetch_assoc($result)) {
        echo "<tr>\n";
        echo "<td valign=top width=150><a href=\"showcat.php?id=".$row['catID']."\">".$row['title']."</a> (".$row['numProducts']." items)</td><td valign=top>".stripslashes($row['description'])."</td></tr>\n";        
    }
    echo "</table>\n";
}

function showFeatures() {
    //connect to database
    connectDB();
    
    $qryFeatures = "select * from products where featured=1";
        if (! $result = mysql_query($qryFeatures)) {
	echo "<P>An error has occured while attempting to query the products table.";
	echo "<p>The following error was returned: " . mysql_error();
	exit;
    }
    
    if (mysql_num_rows($result) == 0) return;
    if (mysql_num_rows($result) == 1) {
    	echo "<h2>This Month's Featured Product</h2>";
    } else {
    	echo "<h2>This Month's Featured Products</h2>";
    }
    $count = 0;
    echo "<table width=100% align=center>";
    while ($prod = mysql_fetch_assoc($result)) {
        if ($count % 2 == 0) echo "<tr>";
        echo "<td><center><a href=\"showprod.php?id=".$prod['prodID']."\"><img src=\"thumb.php?img=".$prod['imgFileName']."&scale=0.2\"><br>".$prod['name']."</a> &nbsp;&nbsp;$".number_format($prod['price'], 2, '.', '')."</td>";
        if ($count % 2 == 1) echo "</tr>";
        $count++;
    }
    echo "</table>";
    
}
?>