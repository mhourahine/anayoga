<?
include("top.php");

if ($_GET['ml']) $selected = "checked";
else $selected="";

?>

<h1>Contact Me</h1>

<p>Should you wish any further information, please fill out the form below and I
will respond to you within 1 business day:</p>

<form method="post" action="message.php">
<table align=center>
<tr>
	<td>First Name:</td>
	<td><input type="text" name="firstName"></td>
</tr>
<tr>
	<td>Last Name:</td>
	<td><input type="text" name="lastName"></td>
</tr>
<tr>
	<td>E-mail:</td>
	<td><input type="text" name="email"></td>
</tr>
<tr>
	<td colspan=2>Message:</td>
</tr>
<tr>
	<td colspan=2><textarea name=message cols=35 rows=5></textarea></td>
</tr>
<tr>
	<td colspan=2 align=center><br>
		<input type=checkbox value=yes name=mailinglist <? echo $selected ?>> I would like to
		receive information and updates about AnaYoga.<br>&nbsp;
	</td>
</tr>
<tr>
	<td colspan=2 align=center><input type=submit value="Send Message"></td>
</tr>
</table>
</form>

<p align=center><font size="-1"><i>NOTE: Your e-mail address will be used
solely for the purposes of AnaYoga and will not be provided to any third
parties.</i></font>

<?
include("bottom.php");
?>