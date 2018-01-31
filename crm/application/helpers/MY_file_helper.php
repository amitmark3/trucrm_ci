<?php defined('BASEPATH') OR exit('No direct script access allowed');

// ------------------------------------------------------------------------

if ( ! function_exists('base64_encode_image'))
{
    /**
     * Text Input Field
     *
     * @param   string    $file
     * @return  string
     */
    function base64_encode_image($file)
    {
        $ext = pathinfo($file, PATHINFO_EXTENSION); // get file extention
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $file);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $dataUri = 'data:image/' . $ext . ';base64,' . base64_encode($data);
        curl_close($ch);
        return $dataUri;
    }
}

// ------------------------------------------------------------------------

// TODO: Add check if filename has more tha one period in it! (http://php.net/manual/en/function.pathinfo.php#49765)
if ( ! function_exists('delete_image'))
{
    function delete_image($file_name, $uploads_folder)
    {
		$file_deleted='';
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $name = pathinfo($file_name, PATHINFO_FILENAME);
		/*unlink('./uploads/company2_1516709167/avatars/1516780364_1435524069_Mastercard_Curved.png');*/
		//$uploads_folder = str_replace("\",'/',$uploads_folder);
        //echo $file = $uploads_folder . '/' . $file_name;
        //echo $file_thumb = $uploads_folder . '/' . $name . '_thumb.' . $ext;die;
		$file = $uploads_folder . DIRECTORY_SEPARATOR . $file_name;
        $file_thumb = $uploads_folder . DIRECTORY_SEPARATOR . $name . '_thumb.' . $ext;

        if (file_exists($file))
        {
            $file_deleted = unlink($file);
        }

        if (file_exists($file_thumb))
        {
            $thumb_deleted = unlink($file_thumb);
        }

        if ($file_deleted)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
}

if ( ! function_exists('uploadfile_image'))
{
    function uploadfile_image($image_data)
    {
		
		$field = $image_data['field'];
		$cur_time = !isset($image_data['cur_time'])?time():$image_data['cur_time'];
		$directory = !isset($image_data['directory'])?'/uploads/':$image_data['directory'];
		$file_type = !isset($image_data['file_type'])?'image':$image_data['file_type'];
		$create_thumb = !isset($image_data['create_thumb'])?FALSE:TRUE;
		$width = !isset($image_data['width'])?25:$image_data['width'];
		$height = !isset($image_data['height'])?25:$image_data['height'];
		$max_size = !isset($image_data['max_size'])?'10240':$image_data['max_size'];
		//$config['max_size'] = $this->config->item('max_file_size');
		$file_data = uploadfile_type($field,$file_type,$max_size);
		//print_r($file_data);die;
		if($file_data['error']==''){
		
			$CI =& get_instance();
			$admin_upload_path = FCPATH . DIRECTORY_SEPARATOR . 'uploads';
			$time = $cur_time == ''?time():$cur_time;
			$file_name = trim($_FILES[$field]['name']);
			$file_name = preg_replace('/[^a-zA-Z0-9\-\._]/','', $file_name);
			$fname = str_replace(" ","",$file_name);
			$new_name = $time.'_'.$fname;
			
			if($file_type=='image'){
				$allowed = 'gif|jpg|jpeg|png|bmp';
			}elseif($file_type=='excel'){
				$allowed = 'xls|xlsx|csv';
			}elseif($file_type=='video'){
				$allowed = 'avi|fli|mpg|mp4|mov|vivo|msvideo|quicktime|webm|3gp|ogv|mpe|qt';
			}else{
				$file_type = explode("/",$file_type);
				$allowed = $file_type[1];
			}
			
			$uploaddir = $directory;
			//$config['sess_save_path'] = './temp_sess/';
			//$uploaddir = '/uploads/images/';
			//$file = "." . $uploaddir . basename($file_name);    
			//echo $uploaddir; 
			$path =  $uploaddir.'/';
			$config = array (
				'upload_path' => './'.$path,
				'allowed_types' => $allowed,
				'max_size' => $max_size,
				'file_name' => $new_name,
				'file_ext_tolower' => TRUE
				
			);
			
			//'encrypt_name' => TRUE,
			
			$CI->load->library ('upload',$config); //load codeigniter libraries
			//$CI->upload->initialize($config);
			$response = array ();
			$img_data = array ();
			$count = count ( $_FILES[ $field ][ 'size' ] );
			if ( count ( $_FILES[ $field ] ) == count ( $_FILES[ $field ], COUNT_RECURSIVE ) ) //if only one image is present
			{
				//echo $field; 
				
				if ( !$CI->upload->do_upload ( $field ) )
				{
					$response = array('error' => $CI->upload->display_errors());
					//echo 'Invalid file type.';
					//print_r($error);die;
					//return FALSE;
				}else{
					$upload_image_data = $CI->upload->data ();
					$img_data['full_path'] = $upload_image_data['full_path'];
					$img_data['file_name'] = $upload_image_data['file_name'];
					$img_data['error'] = '';
					//$data['full_path'] = $path.$new_name;
					//$data['file_name'] = $new_name;
					
					//print_r($data);die;
					//return $data; //return the uploaded file name
					// Start for create Thumb
					if($create_thumb==TRUE){
						$create_thumb_data['full_path']=$upload_image_data['full_path'];
						$create_thumb_data['width'] = $width;
						$create_thumb_data['height'] = $height;
						create_thumb($create_thumb_data);
					}
					// End for create Thumb
					$response = $img_data;
					
				}
				return $response; 
			}
			else //if multiple images selected
			{
				foreach ( $_FILES as $key => $value )
				{
					for ( $s = 0; $s < $count; $s++ )
					{
						$_FILES[ $field ][ 'name' ] = $value[ 'name' ][ $s ];
						$_FILES[ $field ][ 'type' ] = $value[ 'type' ][ $s ];
						$_FILES[ $field ][ 'tmp_name' ] = $value[ 'tmp_name' ][ $s ];
						$_FILES[ $field ][ 'error' ] = $value[ 'error' ][ $s ];
						$_FILES[ $field ][ 'size' ] = $value[ 'size' ][ $s ];
						if ( $CI->upload->do_upload () )
						{
							$data = $CI->upload->data ();
							$data['save_path'] = $path.$new_name;
							$data['file_name'] = $new_name;
							$response[] = $data;
						}
						else
						{
							$response[] = array('error' => $CI->upload->display_errors());
						}
					}
				}
				return $response;//return the names of images uploaded
			}
		}else{
			$response = array('error' => $file_data['error']);
			return $response;
		}		
        
    }
}
/***
***	TODO Create New Directory
***/
if ( ! function_exists('create_directory')){
    function create_directory($directory = '',$directory_name=''){
		$dir = '/uploads/';
		$uploaddir = $directory == ''?$dir:$directory;
		$folder_name = $directory_name == ''?$dir:$directory_name;
		//$config['sess_save_path'] = './temp_sess/';
		//$file = "." . $uploaddir . basename($file_name);    
		/*if(!is_dir("." . $uploaddir . date('Y')))
		mkdir("." . $uploaddir . date('Y'), 0777, true);	
		if(!is_dir("." . $uploaddir . date('Y') . "/" . date('m')))
		mkdir("." . $uploaddir . date('Y') . "/" . date('m'), 0777, true);
		if(!is_dir("." . $uploaddir . date('Y') . "/" . date('m') . "/" . date('d')))
		mkdir("." . $uploaddir . date('Y') . "/" . date('m') . "/" . date('d'), 0777, true);*/
		//echo $uploaddir; 
		// create an folder
        //return $path =  $uploaddir.date('Y') . "/" . date('m') . "/" . date('d').'/';
		$path = mkdir($uploaddir . DIRECTORY_SEPARATOR . $folder_name, 0755, TRUE);
		return $path;
		
	}	
	
}
/***
***	TODO file type
***/
if ( ! function_exists('uploadfile_type'))
{
    function uploadfile_type($field, $file_type = '',$allowed_file_size = ''){
		///$CI =& get_instance();
		//$CI->load->library ( 'upload' ); //load codeigniter libraries
		//$upload=$CI->upload;
		$file_path = $_FILES[$field]['tmp_name'];
		if(isset($file_path) && $file_path!=''){
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime_type = finfo_file($finfo, $file_path);
			finfo_close($finfo);
			$file_size = $_FILES[ $field ][ 'size' ];
			//print_r($mime_type);die;
			
			//For Video
			$allowed_video_file_type = array('video/x-ms-asf','video/x-ms-asf-plugin', 'application/x-troff-msvideo', 'video/avi','video/msvideo','video/x-msvideo','video/avs-video','video/x-dv', 'video/fli','video/x-fli','video/x-atomic3d-feature','video/gl','video/x-gl','video/mpeg','video/x-mpeg','video/x-mpeq2a','video/x-sgi-movie','video/x-qtc','video/quicktime','video/vnd.rn-realvideo', 'video/x-scm','video/vdo','video/vivo','video/vnd.vivo','video/x-amt-demorun','video/x-flv','video/flv', 'video/webm', 'video/mp4', 'video/3gp', 'video/ogv','video/mpg','video/mpe','video/qt','video/mov');
			// For Image
			$allowed_image_file_type = array("image/jpeg", "image/gif", "image/png", "image/jpg", "image/bmp");
			//For Excel
			$allowed_excel_file_type =  array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel.sheet.macroEnabled.12','application/excel', 'application/vnd.ms-excel', 'application/msexcel','application/vnd.ms-office','text/plain','text/csv','application/csv','text/x-csv','application/x-csv','text/x-comma-separated-values','text/comma-separated-values','application/octet-stream','application/vnd.msexcel');
			if($file_type=='image'){
				$allowed = $allowed_image_file_type;
			}elseif($file_type=='excel'){
				$allowed = $allowed_excel_file_type;
			}elseif($file_type=='video'){
				$allowed = $allowed_video_file_type;
			}else{
				$allowed = $file_type;
			}
			//print_r($allowed);		die;
			$allowed_file_size = $allowed_file_size==''?'8000000':$allowed_file_size;
			$data = array();
			//echo '<pre>'; print_r($allowed);	echo "<br/>"; print_r($mime_type);		die;
			
			if(!in_array($mime_type, $allowed)) {
				//echo 'asdf';die;
				$data['error'] = 'Invalid file type';
			}elseif($allowed_file_size<$file_size){
				$data['error'] = 'Invalid file size';
			}else{
				$data['file_type'] = $mime_type;
				$data['file_size'] = $_FILES[ $field ][ 'size' ];
				$data['error'] = '';			
			}
			//print_r($data);die;
			return $data; //return the uploaded file name
		}else{
			$data['error'] = 'The upload path does not appear to be valid.';
			return $data;
		}
	}
}

/***
***
***/
if ( ! function_exists('create_thumb'))
{
    function create_thumb($create_thumb_data){
		$CI =& get_instance();
		 // create a 25x25 pixels thumbnail of the avatar
		$full_path = $create_thumb_data['full_path'];
		$width = $create_thumb_data['width'];
		$height = $create_thumb_data['height'];
		$image_config['image_library'] = 'gd2';
		$image_config['source_image'] = $full_path;
		$image_config['create_thumb'] = TRUE;
		$image_config['maintain_ratio'] = TRUE;
		$image_config['width'] = $width==''?25:$width;
		$image_config['height'] = $height==''?25:$height;
		$CI->load->library ( 'upload' ); //load codeigniter libraries
		//$upload=$CI->upload;
		$CI->load->library('image_lib', $image_config);
		$CI->image_lib->resize();
		$CI->image_lib->clear();
	}
}	
//Default For US East (N. Virginia)	us-east-1	appstream2.us-east-1.amazonaws.com
// load upload file in s3
if ( ! function_exists('uploadfile_s3'))
{
    function uploadfile_s3($field,$cur_time = '', $directory = '', $file_type = '')
    {
		$CI =& get_instance();
		$CI->load->library('S3'); //load codeigniter libraries
		$s3 = $CI->s3;
		// AWS access info
		if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAILQ7L67BQE7TY3SQ');
		if (!defined('awsSecretKey')) define('awsSecretKey', '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');
		$s3->setAuth(awsAccessKey, awsSecretKey);
		//$bucket = 'directory-input-videos';
		$bucket = 'directory-input-videos-sgpr';
		// List your buckets:
		//echo "S3::listBuckets(): ".print_r($s3->listBuckets(), 1)."\n";
		
		$time = $cur_time == ''?time():$cur_time;
		$file_name = trim($_FILES[$field]['name']);
		$file_name = preg_replace('/[^a-zA-Z0-9\-\._]/','', $file_name);
		$fname = str_replace(" ","",$file_name);
		$filename = $time.'_'.$fname;
		$video_file_name = $filename;//md5($filename).'.m3u8';
		//$video_file_name = md5($filename).'.m3u8';
		$new_name = $file_type=='1'?$video_file_name:$filename; //$file_type=='1' only for video
		
		//$allowed_types = $file_type==''?'gif|jpg|jpeg|png':$file_type;
		//$file_path_name = "uploads/images/" . date('Y') . "/" . date('m') . "/" . date('d') . "/" . preg_replace("/[^a-z0-9\.]/", "-", strtolower($new_name));
		$file_path_name = "input/" . strtolower($new_name);
		//echo baseName($file_path_name); die;
		//$s3->setEndpoint('s3-ap-south-1.amazonaws.com');
		// Set for Singapur Region
		$elocation = 'ap-southeast-1';
		$s3->setEndpoint('s3-ap-southeast-1.amazonaws.com');
		//if ($s3->putBucket($bucket, S3::ACL_PUBLIC_READ, $elocation)) {
			//echo "Created bucket {$bucket}".PHP_EOL;
			if ($s3->putObjectFile($_FILES[$field]['tmp_name'], $bucket, $file_path_name, $s3->ACL_PUBLIC_READ)) {
				/*echo "S3::putObjectFile(): File copied to {$bucket}/".baseName($file_path_name).PHP_EOL;			
				// Get the contents of our bucket
				$contents = $s3->getBucket($bucket);
				echo "S3::getBucket(): Files in bucket {$bucket}: ".print_r($contents, 1);
				// Get object info
				$info = $s3->getObjectInfo($bucket, baseName($file_path_name));
				echo "S3::getObjectInfo(): Info for {$bucket}/".baseName($file_path_name).': '.print_r($info, 1);
				*/
				$data['save_path'] = $file_path_name;
				$data['file_name'] = $new_name;
				//print_r($data);die;
				return $data; //return the uploaded file name
			
			}else{
				echo "S3::putObjectFile(): Failed to copy file\n";
			}
		/*}else{
			echo "S3::putBucket(): Unable to create bucket (it may already exist and/or be owned by someone else)\n";
			//print_r($error);die;
			//return FALSE;
		}*/
	}	
	
}

if ( ! function_exists('uploadfile_s3mumbai')){
    function uploadfile_s3mumbai($field,$cur_time = '', $directory = '', $file_type = '')
    {
		$CI =& get_instance();
		//$CI->load->library('Amazons3'); //load codeigniter libraries
		$CI->load->library('amazons3');
		$CI->amazons3->up(); 
		//print_R($t);
		//$s3 = $CI->Amazons3;
		//$s3->updddd();
		//echo 'sdafsdf';die;
	}	
}
/* End of file MY_file_helper.php */
/* Location: ./application/helpers/MY_file_helper.php */