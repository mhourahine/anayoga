<?
include("adminfunc.php.inc");

if (! $_POST["adding"]) {

	include("top.php");
	?><h2>Add Product</h2><?
	showAddProduct();
	include("bottom.php");

} else {
	
	//process image 1
	if ($_FILES['imgFile']['type'] == "image/jpeg" || $_FILES['imgFile']['type'] == "image/pjpeg") {
	
	   if (! move_uploaded_file($_FILES['imgFile']['tmp_name'], "$prodImgPath/".$_FILES['imgFile']['name'])) {
		        echo "Error uploading main image file.";
		        exit;
	   }
	   chmod("$prodImgPath/".$_FILES['imgFile']['name'],0755);
	}
	
	//process image 2
	if ($_FILES['detailImgFile']['type'] == "image/jpeg" || $_FILES['detailImgFile']['type'] == "image/pjpeg") {
	
	
	    //move uploaded file to the uploads directory
		if (! move_uploaded_file($_FILES['detailImgFile']['tmp_name'], "$prodImgPath/".$_FILES['detailImgFile']['name'])) {
		    echo "Error uploading main image file.";
		    exit;
		}
		chmod("$prodImgPath/".$_FILES['detailImgFile']['name'],0755);
	}
	
	$name = $_POST['prodname'];
	$price = $_POST['prodprice'];
	$feat = $_POST['featured'];
	$category = $_POST['prodcat'];
	$imgFileName = $_FILES['imgFile']['name'];
	$imgDetailFileName = $_FILES['detailImgFile']['name'];
	$description = $_POST['proddesc'];
	$orderIndex = getMaxProductIndex($category) + 1;
	
	//add this product to database
	connectDB();
	
	$qryAddProd = "insert into products (name,price,featured,category,imgFileName,imgDetailFileName,description,orderIndex)
	                             values ('$name',$price,$feat,$category,'$imgFileName','$imgDetailFileName','$description',$orderIndex);";
	if (! mysql_query($qryAddProd)) {
	    echo "Error adding product to the database.<br>";
	    echo "MySQL returned: ".mysql_error();
	}
	
	header("Location: categories.php");
}
?>