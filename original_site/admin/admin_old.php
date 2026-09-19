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

<h1>AnaYoga Product Catalog Admin</h1>

<table width=750 border=0 cellpadding=5 cellspacing=0>
    <tr>
        <td colspan=2><b>Current Categories:</b><br><br><? listCategories(); ?></td>
    </tr>
    <tr>
        <td width=50% valign=center><b>Add Product:</b> <br><center><? showAddProduct(); ?></center></td>
        <td width=50% valign=center><b>Add Category:</b> <br><center><? showAddCategory(); ?></center></td>
    </tr>
</table>

<p><center><a href="https://www.anayoga.com:2083/tmp/anayoga/webalizer/index.html">AnaYoga.com Stats</a></center></p>

</body>
</html>