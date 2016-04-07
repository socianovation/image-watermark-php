<?php
$filelist = array();
if ($handle = opendir("fonts")) {
	while ($entry = readdir($handle)) {
		if($entry != "." && $entry != "..")
		{
			$filelist[] = $entry;
		}
	}
	closedir($handle);
}
?>

<form action="" enctype="multipart/form-data" method="POST">
<table border="1">
<tr>
	<td>Image to be watermarked : </td>
	<td><input type="file" name="gambar"></td>
</tr>
<tr>
	<td>Text Input for watermark :</td>
	<td><input type="text" name="watermark_text" value="INPUT TEXT HERE"></td>
</tr>
<tr>
	<td>Set TOP :</td>
	<td><input type="text" name="top_pos" value="50"></td>
</tr>
<tr>
	<td>Set LEFT :</td>
	<td><input type="text" name="left_pos" value="50"></td>
</tr>
<tr>
	<td>FONT SIZE :</td>
	<td><input type="text" name="font_size" value="20"></td>
</tr>
<tr>
	<td>Set TEXT COLOR :</td>
	<td>
	RED : <input type="text" name="r_color" value="255">
	GREEN : <input type="text" name="g_color" value="255">
	BLUE : <input type="text" name="b_color" value="255">
	</td>
</tr>
<tr>
	<td>Select Font : </td>
	<td>
		<select name="font_selection">
			<?php 
			foreach($filelist as $f)
			{
				echo '<option value="'.$f.'">'.$f.'</option>';
			}
			?>
		</select>
	</td>
</tr>
<tr>
	<td colspan="2"><input type="submit" value="Submit" name="submit"></td>
</tr>
</table>
</form>

<?php 
$target_dir = 'uploads/';
$watermarked_dir = 'watermarked/';
$font_dir = 'fonts/';

if(isset($_POST["submit"]))
{
	$target_file = $target_dir . basename($_FILES["gambar"]["name"]);
	$imageFileType = pathinfo($_FILES["gambar"]["name"],PATHINFO_EXTENSION);
	
	if(strtolower($imageFileType) != "jpg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "gif" ) {
        echo "File is not an image.";
        $uploadOk = 0;
    }
	else
	{
		if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
			
			if(strtolower($imageFileType) == "jpg" || strtolower($imageFileType) == "jpeg")
			{
				$imagetobewatermark=imagecreatefromjpeg($target_file); 
				$watermarktext=$_POST['watermark_text'];
				$font=$font_dir.$_POST['font_selection'];
				$fontsize=$_POST['font_size'];
				$color = imagecolorallocate($imagetobewatermark, $_POST['r_color'], $_POST['g_color'], $_POST['b_color']); //set color via RGB
				imagettftext($imagetobewatermark, $fontsize, 0, $_POST['left_pos'], $_POST['top_pos'], $color, $font, $watermarktext);
				//header("Content-type:image/jpeg"); 
				$watermark_file = $watermarked_dir.date("dmy").rand()."_watermarked.jpg";
				imagejpeg($imagetobewatermark,$watermark_file);
				imagedestroy($imagetobewatermark);		
				
				echo 'Here\'s Your watermarked image : <a target="_blank" href='.$watermark_file.'>'.$watermark_file.'</a>';
			}
			else if(strtolower($imageFileType) == "png")
			{
				$imagetobewatermark=imagecreatefrompng($target_file); 
				$watermarktext=$_POST['watermark_text'];
				$font=$font_dir.$_POST['font_selection'];
				$fontsize=$_POST['font_size'];
				$color = imagecolorallocate($imagetobewatermark, $_POST['r_color'], $_POST['g_color'], $_POST['b_color']); //set color via RGB
				imagettftext($imagetobewatermark, $fontsize, 0, $_POST['left_pos'], $_POST['top_pos'], $color, $font, $watermarktext);
				//header("Content-type:image/png");
				$watermark_file = $watermarked_dir.date("dmy").rand()."_watermarked.png";
				imagepng($imagetobewatermark,$watermark_file);
				imagedestroy($imagetobewatermark);		

				echo 'Here\'s Your watermarked image : <a target="_blank" href='.$watermark_file.'>'.$watermark_file.'</a>';				
			}
			
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
}
?>

