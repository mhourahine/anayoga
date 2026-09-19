<?
include("../global.php.inc");

function showAddProduct() {

    //display category drop down box
    connectDB();
    
    $qryCat = "select catID,title from categories;";
    if (! $result = mysql_query($qryCat)) {
		echo "<P>An error has occured while attempting to query the category table.";
		echo "<p>The following error was returned: " . mysql_error();
		exit;
    }
    
    $categories = "<select name=prodcat>";
    while ($row = mysql_fetch_assoc($result)) {
        $categories .= "<option value=".$row[catID].">".$row['title']."\n";
    }
    $categories .= "</select>";
    
?>
    <form enctype="multipart/form-data" action="addprod.php" method="post">
    <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
    <input type="hidden" name="adding" value=true>
    <table align=center>
    <tr><td>Product Name:</td><td><input name=prodname type=text size=20></td></tr>
    <tr><td>Product Price:</td><td><input name=prodprice type=text size=20></td></tr>
    <tr>
        <td>Featured?</td>
        <td><select name=featured>
                <option value=0>No
                <option value=1>Yes
            </select>
        </td>
    </tr>
    <tr><td>Category:</td><td><? echo $categories; ?></td>
    <tr><td>Attach Main product image:</td><td><input name=imgFile type=file></td></tr>
    <tr><td>Attach Detail product image:</td><td><input name=detailImgFile type=file></td></tr>
    <tr><td colspan=2><center>(JPEGs Only - size should approximate 640X480 pixels)</center></td></tr>
    <tr><td align=center colspan=2><br><textarea name="proddesc" cols=50 rows=5>Place description here.</textarea></td></tr>
    <tr><td align=center colspan=2><center><input type="submit" value="Add Product"></center></td></tr>
    </table>
    </form>
<?
}

function showAddCategory() {
?>
    <form action=addcat.php method=post>
    <input type="hidden" name="adding" value=true>
        <table align=center>
            <tr>
                <td>Title: <input type=text size=40 name=cattitle></td>
            </tr>
            <tr>
                <td valign=top>Description:<br><textarea name=catdesc cols=40 rows=5></textarea></td>
            </tr>
            <tr>
                <td align=center><input type=submit name=submit value="Add Category"></td>
            </tr>
        </table>
    </form>
<?
}

function listProducts($id) {
        
    //connect to database
    connectDB();
    
    $qryProducts = "select products.prodID,products.name,products.price,products.description,products.featured,products.orderIndex,products.category,categories.title
                        from products inner join categories on products.category = categories.catID
                        where categories.catID = $id
                        order by products.orderIndex ASC;";
    if (! $result = mysql_query($qryProducts)) {
        echo "An error occurred while querying the products table.<br>";
        echo "MySQL returned: ".mysql_error();
        exit;
    }
    
    echo "<table border=1 cellspacing=0 cellpadding=4>\n";
    echo "<tr><td>Action</td><td>ID</td><td>Name</td><td>Price</td><td>Featured</td><td>Category</td><td>Order</td><!-- <td width=30%>Description</td> --></tr>";
    
    while($row = mysql_fetch_assoc($result)) {
        ?>
        <tr>
            <td><a href="editprod.php?id=<? echo $row['prodID']; ?>">Edit</a>
                <a onClick='confirmDelete("delprod.php?id=<? echo $row['prodID']."&category=".$row['category']; ?>");' href="#">Delete</a>
                <a href="reorder.php?prodID=<? echo $row['prodID']; ?>&direction=up&type=product">Up</a>
        	    <a href="reorder.php?prodID=<? echo $row['prodID']; ?>&direction=down&type=product">Down</a>
            </td>
            <td align=center><? echo $row['prodID']; ?></td>
            <td><? echo $row['name']; ?></td>
            <td><? echo $row['price']; ?></td>
            <td align=center><? echo $row['featured']; ?></td>
            <td><? echo $row['title']; ?></td>
            <td align=center><? echo $row['orderIndex']; ?></td>
            <!-- <td><? echo $row['description']; ?></td> -->
        </tr>
        <?
    }
    echo "</table>";
    
    
}

function listCategories() {
    
    //connect to database
    connectDB();
    
    $qryCategories = "SELECT categories.catID, categories.title, categories.description, categories.orderIndex,count( products.prodID ) as numProducts
                      FROM categories LEFT JOIN products ON categories.catID = products.category
                      GROUP BY categories.catID, categories.title, categories.description
                      ORDER BY categories.orderIndex ASC";
    if (! $result = mysql_query($qryCategories)) {
        echo "An error occurred while querying the categories table.<br>";
        echo "MySQL returned: ".mysql_error();
        exit;
    }
    
    echo "<table border=1 cellpadding=4 cellspacing=0>";
    echo "<tr><td>Actions</td><td>ID</td><td>Title</td><td>Order</td><td># of Products</td></tr>";
    while($row = mysql_fetch_assoc($result)) {
        ?>
        <tr>
            <td><a href="editcat.php?id=<? echo $row['catID']; ?>">Edit</a>
        <?  if ($row['numProducts'] == 0) echo "<a onClick='confirmDelete(\"delcat.php?id=".$row['catID']."\");' href=\"#\">Delete</a>"; ?>
        	<a href="reorder.php?catID=<? echo $row['catID']; ?>&direction=up&type=category">Up</a>
        	<a href="reorder.php?catID=<? echo $row['catID']; ?>&direction=down&type=category">Down</a>
            </td>
            <td><? echo $row['catID']; ?></td>
            <td><a href="showcat.php?id=<? echo $row['catID'];?>"><? echo $row['title']; ?></a></td>
            <td><? echo $row['orderIndex']; ?></td>
            <td><? echo $row['numProducts']; ?></td>
        </tr>
        <?
    }
    echo "</table>";
    
}


function showConfirmScript() {
    ?>
    	<script language=javascript>
	
		function confirmDelete(URL) {
			var test = confirm("You are about to delete this product.  Do you wish to continue?");
			if (test) {
				document.location = URL;
			} else {
				//do nothing
			}
		}
	
	</script>
    <?
}

function getMaxCategoryIndex() {
	connectDB();
	
	$qryMax = "select max(orderIndex) as MaxIndex from categories;";
	if (! $result = mysql_query($qryMax)) {
        echo "An error occurred while querying the categories table.<br>";
        echo "MySQL returned: ".mysql_error();
        exit;
    }
    $row = mysql_fetch_assoc($result);
    return $row["MaxIndex"];
    
}
function getMaxProductIndex($catID) {
	connectDB();
	
	$qryMax = "select max(orderIndex) as MaxIndex from products where category=$catID;";
	if (! $result = mysql_query($qryMax)) {
        echo "An error occurred while querying the products table.<br>";
        echo "MySQL returned: ".mysql_error();
        exit;
    }
    $row = mysql_fetch_assoc($result);
    return $row["MaxIndex"];	
}

?>