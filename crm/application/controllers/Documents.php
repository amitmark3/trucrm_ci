<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends Account_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->language('documents');

        if ( ! in_array($this->user_group['id'], [2,3]) )
        {
            $this->flasher->set_warning_extra(lang('documents_access_denied'), 'dashboard',  TRUE);
        }

        $this->breadcrumbs->push('Home', 'dashboard');
        $this->breadcrumbs->push('Documents', 'documents');
    }

    public function index()
    {
		$this->breadcrumbs->push('Folder Listing', 'documents/folder_listing');
		//echo $this->uri->segment(3); die;
		if($this->uri->segment(3)=='images') {
			
			$this->breadcrumbs->push('Images', 'documents/index/images');
			$upload_path = $this->company_lib->get_uploads_folder($this->company['id']);

			$files = $this->get_filelist_as_array($upload_path);

        $this->template->title(lang('heading_index'))
                       ->set('files', $files)
                       ->set_js(['masonry.pkgd.min', 'bootbox-4.4.0.min'])
                       ->set_partial('custom_js', 'documents/index_js')
                       ->build('documents/index_images', $this->data);
			
		}elseif($this->uri->segment(3)) {
			$folder_name = $this->uri->segment(3);
			$base_path = $this->company_lib->get_uploads_folder($this->company['id']);
			$upload_path = $base_path."/".$folder_name;
			/* echo "<pre>";
			echo $base_path."<br/>";
			echo $upload_path."<br/>"; die;
			*/
			$files = $this->get_filelist_as_array($upload_path);

			$this->template->title(lang('heading_index'))
							->set('files', $files)
							->set('folder_name', $folder_name)
							->set_css(['bootstrap-select.min'])
							->set_js(['masonry.pkgd.min', 'bootbox-4.4.0.min', 'bootstrap-select.min',  'pdfmake.min', 'vfs_fonts'])
							->set_partial('custom_js', 'documents/index_js')
							->build('documents/index', $this->data);
		} else {
			redirect('documents/folder_listing');
		}
    }
	
	/* Function to show sub directories listing of images */
	public function folder_listing()
    {
		//$this->flasher->set_success('');
        $path = $this->company_lib->get_uploads_folder($this->company['id']);			
		$directories = glob($path . '/*' , GLOB_ONLYDIR);
		for($i=0; $i < count($directories); $i++) {
			$directory_path = $directories[$i];
			$folderArr = explode("/", $directory_path);
			$subDir = end($folderArr);
			$subDirArr[] = $subDir;
		}
		$this->breadcrumbs->push('Folder Listing', 'documents/folder_listing');
        $this->template->title('Folder Listing')
                       ->set('subDirArr', $subDirArr)
                       ->set_js(['masonry.pkgd.min', 'bootbox-4.4.0.min'])
                       ->set_partial('custom_js', 'documents/index_js')
                       ->build('documents/folder_listing', $this->data);
    }
	
	/* Function to create directory for uploading images */
    public function create_folder()
    {
        $this->load->helper('form');
		if($this->input->post('create_directory')) {
			if($this->input->post('directory_name')) {
				$path = $this->company_lib->get_uploads_folder($this->company['id']);
				//create directory after $path
				$subdir_name = $this->input->post('directory_name');
				$string = str_replace(' ', '-', $subdir_name); // Replaces all spaces with hyphens.
				$subdir_name = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
   
				if(!is_dir($path."/". $subdir_name ."/")) {
					mkdir($path."/". $subdir_name ."/");
					/* After creating folder, redirect it to upload function */
					redirect('/Documents/upload');
				} else {
					$this->flasher->set_warning(lang('create_folder_failed'), 'documents/create_folder', TRUE);
				}
				/* 
				echo "<pre>";
				print_r($path);
				echo "</pre>"; */
			}
		}
		        
		$this->breadcrumbs->push('Create Folder', 'documents/create_folder');

		$this->template->title(lang('heading_create_folder'))
					   ->set_css('fileinput.min')
					   ->set_js('fileinput.min')
					   ->set_partial('custom_js', 'documents/custom_js')
					   ->build('documents/create_folder', $this->data);
     
    }
	
	/* Function to rename folder from folders listing */
	public function rename_folder()
    {
        $old_folder = $this->uri->segment(3);
		//$this->data['foler_name'] = $old_folder;
		//echo $old_folder; die;
       
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('foler_name', 'Folder Name', 'trim|required');

        if ($this->form_validation->run() == TRUE)
        {
            $new_folder = $this->input->post('foler_name');
			/* Replaces all spaces with hyphens and Removes special chars */
			$string = str_replace(' ', '-', $new_folder);
			$new_folder_name = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
			
            $old_folder = $this->input->post('old_folder');
			$base_path = $this->company_lib->get_uploads_folder($this->company['id']);
			$old_path = $base_path."/".$old_folder;
			$new_path = $base_path."/".$new_folder_name;
		
			if(rename($old_path, $new_path))
            {
                $this->flasher->set_success('Folder has been renamed successfully', 'documents/folder_listing', TRUE);
            }

            $this->flasher->set_danger('Folder has not been renamed successfully', 'documents/rename_folder', TRUE);
        }
        else
        {
            $this->data['foler_name'] = [
                'name'  => 'foler_name',
                'id'    => 'foler_name',
                'value' => $this->form_validation->set_value('foler_name', $old_folder),
            ];

            $this->breadcrumbs->push('Rename Folder', 'documents/rename_folder');

            $this->template->title('Rename Folder')
                           ->set_css(['formvalidation.min'])
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set('foler_name', $old_folder)
                           ->set('old_folder', $old_folder)
                           ->build('documents/rename_folder', $this->data);
        }
    }
	
	/* Function to delete folder from folders listing */
	public function delete_folder()
    {
		if($this->uri->segment(3)) {
			$folder = $this->uri->segment(3);
			$base_path = $this->company_lib->get_uploads_folder($this->company['id']);
			$dir_path = $base_path."/".$folder;
			
			$dir = opendir($dir_path);
			while(false !== ( $file = readdir($dir)) ) {
				if (( $file != '.' ) && ( $file != '..' )) {
					$full = $dir_path . '/' . $file;
					
					unlink($full);
				}
			}
				
			if(is_dir($dir_path)) {
				if(rmdir($dir_path)) {
					$this->flasher->set_success('Folder has been deleted successfully', 'documents/folder_listing', TRUE);
					//redirect('documents/folder_listing');
				} else {
					$this->flasher->set_danger('Folder has not been deleted successfully', 'documents/folder_listing', TRUE);
				}
			}
		}
    }

    // TODO: Allow new folders to be created (V2)
    public function upload()
    {
        $percent_used = $this->company_lib->percentage_used($this->company['id']);

        if ($percent_used == 100)
        {
            $this->flasher->set_warning_extra(lang('disk_space_full'), 'documents', TRUE);
        }

        $this->load->library(['upload', 'image_lib']);
        $this->load->helper('form');
		/* Start functionality to show sub directories listing */
		$path = $this->company_lib->get_uploads_folder($this->company['id']);			
		$directories = glob($path . '/*' , GLOB_ONLYDIR);
		for($i=0; $i < count($directories); $i++) {
			$directory_path = $directories[$i];
			$folderArr = explode("/", $directory_path);
			$subDir = end($folderArr);
			$subDirArr[$subDir] = $subDir;
		}
			/* echo "<pre>";
			print_r($subDirArr);
			echo "</pre>"; die; */
		$subDirArr['default'] = 'Select Directory';
		$this->data['subDirArr'] = $subDirArr;
		
		/* End functionality to create directory to upload images */
        if ( ! empty($_FILES) )
        {
			if($this->input->post('select_directory') != 'default') {
				$selected_folder = $this->input->post('select_directory');
				$base_path = $this->company_lib->get_uploads_folder($this->company['id']);
				$upload_config['upload_path']       = $base_path."/".$selected_folder;
				$upload_config['allowed_types']     = '*';
				$upload_config['max_size']          = $this->config->item('max_file_size');
				$upload_config['file_ext_tolower']  = TRUE;

				$files = $_FILES;
				$file_count = count($_FILES['userfile']['name']);
				$documents = [];

				for ($i = 0; $i < $file_count; $i++)
				{
					$_FILES['userfile']['name']     = $files['userfile']['name'][$i];
					$_FILES['userfile']['type']     = $files['userfile']['type'][$i];
					$_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
					$_FILES['userfile']['error']    = $files['userfile']['error'][$i];
					$_FILES['userfile']['size']     = $files['userfile']['size'][$i];

					$this->upload->initialize($upload_config);

					$this->upload->do_upload();

					$upload_data = $this->upload->data();

					if ($upload_data['is_image'] == 1 && $upload_data['image_width'] > '1280')
					{
						$image_config['image_library']  = 'gd2';
						$image_config['maintain_ratio'] = TRUE;
						$image_config['width']          = 1280;
						$image_config['height']         = 1024;
						$image_config['source_image']   = $upload_data['full_path'];

						$this->image_lib->initialize($image_config);
						$this->image_lib->resize();
						$this->image_lib->clear();
					}

					$documents[$i]['id'] = NULL;
					$documents[$i]['company_id'] = $this->user->company_id;
					$documents[$i]['feature'] = NULL;
					$documents[$i]['file_name'] = $upload_data['file_name'];
					$documents[$i]['file_name_thumb'] = NULL;
					$documents[$i]['file_size'] = $upload_data['file_size'];
				}

				if ($this->db->insert_batch('uploads', $documents) === FALSE)
				{
					$this->flasher->set_danger(lang('upload_failed'), 'documents/upload', TRUE);
				}
				else
				{
					$this->flasher->set_success(lang('upload_success'), 'documents/folder_listing', TRUE);
				}
			} else {
				$this->flasher->set_danger(lang('directory_selection_error'), 'documents/upload', TRUE);
			}
		}
        else
        {
            $this->breadcrumbs->push('Upload', 'documents/upload');

            $this->template->title(lang('heading_upload'))
                           ->set_css('fileinput.min')
                           ->set_js('fileinput.min')
                           ->set_partial('custom_js', 'documents/custom_js')
                           ->build('documents/upload', $this->data);
       }
    }

    public function delete()
    {
        $this->load->helper('file');
		$file_name = $this->input->post('file_name', TRUE);
       $folder = trim($this->input->post('folder_name', TRUE));

        $uploads_folder = $this->company_lib->get_uploads_folder($this->company['id']);
		if($folder!='images' || $folder!='avatars' || $folder!=''){
			$uploads_folder .= "/".$folder;
		}	
		//echo $uploads_folder.'/'.$file_name;die;
        if (delete_image($file_name, $uploads_folder) == TRUE)
        {
            $query = $this->db->delete('uploads', ['file_name' => $file_name]);

            if ($query)
            {
                echo "deleted";
            }
        }
        else
        {
            echo "failed";
        }
    }

    // TODO: Exclude avatars folder.
    protected function get_filelist_as_array($dir, $recursive = true, $basedir = '')
    {
        if ($dir == '') { return array(); } else { $results = array(); $subresults = array(); }
        if (!is_dir($dir)) { $dir = dirname($dir); } // so a files path can be sent
        if ($basedir == '') { $basedir = realpath($dir) . DIRECTORY_SEPARATOR; }

        $files = scandir($dir);
        foreach ($files as $key => $value)
        {
            if ( ($value != '.') && ($value != '..') && strpos($value, '_thumb') === false )
            {
                $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
                if (is_dir($path)) // do not combine with the next line or..
                {
                    if ($recursive) // ..non-recursive list will include subdirs
                    {
                        $subdirresults = $this->get_filelist_as_array($path, $recursive, $basedir);
                        $results = array_merge($results, $subdirresults);
                    }
                }
                else // strip basedir and add to subarray to separate file list
                {
                    $subresults[] = str_replace($basedir, '', $path);
                }
            }
        }
        // merge the subarray to give the list of files then subdirectory files
        if (count($subresults) > 0) { $results = array_merge($subresults, $results); }
        return $results;
    }
}

/* End of file Documents.php */
/* Location: ./application/controllers/Documents.php */