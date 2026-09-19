<?
include("global.php.inc");
//connect to database
$db = mysql_connect($dbhost, $dbusername, $dbpasswd);
if (! $db) {
    echo "Error: Unable to connect to database.";
    exit;
}
mysql_select_db($dbname);

include("top.php");
?>

<h1>Product Catalogue</h1>

<p>Welcome to <font class=anayoga>AnaYoga</font> and thank
you for visiting.&nbsp; Please take a moment to check out all
that <font class=anayoga>AnaYoga</font> has to offer.&nbsp; Whether you are an
experienced yoga enthusiast searching for that perfect yoga bag to fit all
your gear, or a curious shopper looking for something a little different,
<font class=anayoga>AnaYoga</font> has wonderful, handmade items for
everyone.&nbsp; From yoga accessories to non-yoga items such as totes, jewelry
and gifts.&nbsp; You'll be pleased with the quality and beautiful designs,
and you'll also feel good about making a purchase from someone who respects
the environment every step of the way.&nbsp; So relax, take a little time
out of your busy day, and enjoy what <font class=anayoga>AnaYoga</font> has
to offer.</p>

<?
showFeatures();
showCategories();
include("bottom.php");
?>