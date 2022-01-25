<?php
/**
 * Cosmosfarm_Members
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_File_Handler {
	
	public function __construct(){
		if(!function_exists('wp_handle_upload')){
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
	}
	
	public function upload_avatar($name, $scheme='relative'){
		if(isset($_FILES[$name])){
				
			$uploadedfile = $_FILES[$name];
			if(isset($uploadedfile['name']) && $uploadedfile['name']){
				
				$info = getimagesize($uploadedfile['tmp_name']);
				if($info !== false && ($info[2] === IMAGETYPE_GIF) || ($info[2] === IMAGETYPE_JPEG) || ($info[2] === IMAGETYPE_PNG)){
					
					$movefile = wp_handle_upload($uploadedfile, array('unique_filename_callback'=>array($this, 'unique_filename'), 'test_form'=>false));
					if($movefile && !isset($movefile['error'])){
						
						$this->image_orientation($movefile['file']);
						
						$movefile['file'] = $this->resize($movefile['file']);
						
						if($scheme == 'relative'){
							$movefile['file'] = str_replace(ABSPATH, '', $movefile['file']);
							$temp = explode('/wp-content/uploads', $movefile['url']);
							$movefile['url'] = end($temp);
						}
						
						return $movefile;
					}
					
				}
				else{
					@unlink($uploadedfile['tmp_name']);
					echo '<script>alert("'.__('Please upload an image file.', 'cosmosfarm-members').'");</script>';
				}
			}
		}
		return false;
	}
	
	public function upload_file($name, $scheme='relative'){
		if(isset($_FILES[$name])){
			
			$uploadedfile = $_FILES[$name];
			if(isset($uploadedfile['name']) && $uploadedfile['name']){
				
				$movefile = wp_handle_upload($uploadedfile, array('unique_filename_callback'=>array($this, 'unique_filename'), 'test_form'=>false));
				if($movefile && !isset($movefile['error'])){
					
					$this->image_orientation($movefile['file']);
					
					if($scheme == 'relative'){
						$movefile['file'] = str_replace(ABSPATH, '', $movefile['file']);
						$temp = explode('/wp-content/uploads', $movefile['url']);
						$movefile['url'] = end($temp);
					}
					
					return $movefile;
				}
			}
		}
		return false;
	}
	
	public function unique_filename($dir, $name, $ext){
		return uniqid() . $ext;
	}
	
	public function resize($file, $width=150, $height=150){
		$image_editor = wp_get_image_editor($file);
		if(!is_wp_error($image_editor)){
			$image_editor->resize($width, $height, true);
			$image_editor->save($file);
			return $file;
		}
		return $file;
	}
	
	public function image_orientation($image){
		if(mime_content_type($image) == 'image/jpeg'){
			$image_editor = wp_get_image_editor($image);
			if(!is_wp_error($image_editor) && function_exists('exif_read_data')){
				$exif = exif_read_data($image);
				if(isset($exif['Orientation']) && $exif['Orientation']){
					switch($exif['Orientation']){
						case 8: $image_editor->rotate(90); break;
						case 3: $image_editor->rotate(180); break;
						case 6: $image_editor->rotate(-90); break;
					}
				}
				$image_editor->save($image);
			}
		}
	}
}
?>