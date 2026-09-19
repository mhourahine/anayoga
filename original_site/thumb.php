<?
include("global.php.inc");

if ($_GET['img'] & $_GET['scale']) {
    $img = $_GET['img'];
    $scale = $_GET['scale'];
    $src_img = imagecreatefromjpeg($prodImgPath."/".$img);
    $size = getimagesize($prodImgPath."/".$img);
    $dst_img = imagecreatetruecolor($size[0]*$scale,$size[1]*$scale);
    if (imagecopyresampled($dst_img,$src_img,0,0,0,0,$size[0]*$scale,$size[1]*$scale,$size[0],$size[1])) {
        //output image to browser
        header("Content-type: image/jpeg");
        imagejpeg($dst_img);
    } else {
        echo "An error has occured creating image.";
    }
}

?>