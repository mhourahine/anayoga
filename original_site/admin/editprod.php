<?
include("adminfunc.php.inc");

if (! ($_POST["update"] || $_POST["updateimg"] || $_POST["updatedtl"])) {  //form display
    if ($_GET['id'] == "") {
        echo "An error has occurred.";
        exit;
    }
    $id = $_GET['id'];

    connectDB();

    //get product data
    $qryProducts = "select products.prodID,products.name,products.price,products.description,products.featured,products.imgFileName,products.imgDetailFileName,products.orderIndex,categories.title,products.category
                    from products inner join categories on products.category = categories.catID
                    where products.prodID=$id";
    if (! $result = mysql_query($qryProducts)) {
        echo "An error occurred while querying the products table.<br>";
        echo "MySQL returned: ".mysql_error();
        exit;
    }
    $prodinfo = mysql_fetch_assoc($result);

    //build category drop down box text
    $qryCat = "select catID,title from categories;";
    if (! $result = mysql_query($qryCat)) {
	echo "<P>An error has occured while attempting to query the category table.";
	echo "<p>The following error was returned: " . mysql_error();
	exit;
    }

    $categories = "<select name=prodcat>";
    while ($row = mysql_fetch_assoc($result)) {
        $categories .= "<option value=".$row[catID];
        if ($row[catID] == $prodinfo['category']) { $categories .= " selected"; }
        $categories .= ">".$row['title']."\n";
    }
    $categories .= "</select>";

	include("top.php");
?>
	<form enctype="multipart/form-data" action="editprod.php" method="post">
	<input type=hidden name=update value=true>
	<input type="hidden" name=id value=<? echo $prodinfo['prodID']; ?>> 
	<table align=center>
	<tr>
	<td valign=top>
	    <table>
	    <tr><td>Product Name:</td><td><input name=prodname type=text size=20 value="<? echo htmlspecialchars($prodinfo['name'], ENT_QUOTES); ?>"></td></tr>
	    <tr><td>Product Price:</td><td><input name=prodprice type=text size=20 value="<? echo $prodinfo['price']; ?>"></td></tr>
	    <tr>
	        <td>Featured?</td>
	        <td><select name=featured>
	                <option value=0<? if ($prodinfo['featured'] == 0) echo " selected"; ?>>No
	                <option value=1<? if ($prodinfo['featured'] == 1) echo " selected"; ?>>Yes
	            </select>
	        </td>
	    </tr>
	    <tr><td>Category:</td><td><? echo $categories; ?></td></tr>
	    <tr><td>Order Index:</td><td><input type=text size=3 name=orderIndex value="<? echo $prodinfo['orderIndex']; ?>"></td></tr>
	    <tr><td colspan=2></td></tr>
	    </table>
	    
	</td>
	<td width=10></td>
	<td>Description:<br><center><textarea name="proddesc" cols=40 rows=6><? echo $prodinfo['description']; ?></textarea></center></td>
	</tr>
	<tr>
		<td colspan=3><center><input type="submit" value="Update Details"></center></td>
	</tr>
	</table>
	</form>
	<hr>
	
	<table align=center>
	<tr>
		<td>
			<form enctype="multipart/form-data" action="editprod.php" method="post">
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
			<input type="hidden" name="updateimg" value=true>
			<input type="hidden" name=id value=<? echo $prodinfo['prodID']; ?>>
		    <table>
		    <tr><td colspan=2><center><a href="../product_imgs/<? echo $prodinfo['imgFileName']; ?>"><img border=0 src="../<? echo "thumb.php?img=".$prodinfo['imgFileName']."&scale=0.2";?>"></a></td></tr>
		    <tr><td>Change product image:</td><td><input name="file" type="file"></td></tr>
		    <tr><td colspan=2 align=center>(JPEGs Only - size should approximate 640X480 pixels)</td></tr>
		    <tr><td colspan=2 align=center><!-- <input type=submit value="Update Images">--> <b>Feature Not Complete</b></td></tr>
		    </table>
		    </form>
		</td>
		<td>
			<form enctype="multipart/form-data" action="editprod.php" method="post">
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
			<input type="hidden" name="updatedtl" value=true>
			<input type="hidden" name=id value=<? echo $prodinfo['prodID']; ?>>
		    <table>
		    <tr>
		    	<? if ($prodinfo["imgDetailFileName"] != "") { ?>
		    		<td colspan=2><center><a href="../product_imgs/<? echo $prodinfo['imgDetailFileName']; ?>"><img border=0 src="../<? echo "thumb.php?img=".$prodinfo['imgDetailFileName']."&scale=0.2";?>"></a></td></tr>
		    	<? } else { ?>
		    		<td colspan=2><center><i>No Detail Image</i></td></tr>
		    	<? } ?>
		    <tr><td>Change Detail product image:</td><td><input name="file" type="file"></td></tr>
		    <tr><td colspan=2 align=center>(JPEGs Only - size should approximate 640X480 pixels)</td></tr>
		    <tr><td colspan=2 align=center><!-- <input type=submit value="Update Image">--> <b>Feature Not Complete</b></td></tr>
		    </table>
		    </form>		
		</td>
	</tr>
	</table>
<?

	include("bottom.php");
	
 } elseif ($_POST["update"]) {  //details processing
 	
 	$name = $_POST["prodname"];
 	$price = $_POST["prodprice"];
 	$featured = $_POST["featured"];
 	$category = $_POST["prodcat"];
 	$description = $_POST["proddesc"];
 	$orderIndex = $_POST["orderIndex"];
 	
 	connectDB();
	$qryUpdate = "update products set 
					name=\"$name\",
					price=$price,
					description=\"$description\",
					featured=$featured,
					category=$category,
					orderIndex=$orderIndex
				  where prodID=$id;";
				  
	if (! mysql_query($qryUpdate)) {
    	echo "An error has occurred updating category.<br>\n";
    	echo "MySQL returned: ".mysql_error();
	}

	header("Location: showcat.php?id=$category");
 	
 	
 } elseif ($_POST["updateimg"]) {  //product image 1 update
 	
 	echo "Update Image 1";
 	
} 
 else {  //product image 2 update
 	
 	echo "Image Update 2";
 
 }
?>
    