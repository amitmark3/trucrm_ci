<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div class="grid">
                    <ul class="captioned">
                    <?php
                    $image_types = ['png', 'gif', 'jpg', 'jpeg', 'bmp'];
                    $file_types = ['txt', 'csv', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'psd', 'zip', 'rar', 'pdf'];
					if(count($files) > 0) {
						$i = 0;
						foreach ($files as $file)
						{
							$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

							echo '<li class="grid-item img-wrap" id="item-'.$i.'">';
							echo '<span class="delete" title="Delete">&times;</span>';
							//echo '<span class="zoom_image" title="Zoom Image">&times;</span>';
							if (in_array($ext, $image_types))
							{
								echo '<a class="zoom_image" title="'.$file.'" data-folder="'.$folder_name.'" data-id="'.site_url('uploads/'.$this->company['uploads_folder'].'/'.$folder_name.'/'.$file).'">';
								echo '<img src="'.site_url('uploads/'.$this->company['uploads_folder'].'/'.$folder_name.'/'.$file).'" alt="'.$file.'" width="200px" class="img-responsive">';
								echo '<div class="caption"><div class="caption-text blur"><p>'.$file.'</p></div></div>';
								echo '</a>';
							}

							if (in_array($ext, $file_types))
							{
								echo '<a href="'.site_url('uploads/'.$this->company['uploads_folder'].'/'.$folder_name.'/'.$file).'" target="_blank" title="'.$file.'">';
								echo '<img src="'.site_url('assets/img/icons/'.$ext.'-lg.png').'" width="200px" class="img-responsive" alt="'.$file.'">';
								echo '<div class="caption"><div class="caption-text blur"><p>'.$file.'</p></div></div>';
								echo '</a>';
							}

							echo '</li>';

							$i++;
						}
					} else {
						echo "<h4 align='center'><strong>No image found!</strong></h4>";
					}
                    ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="add_action_image" style="display: none;" enctype="multipart/form-data" accept-charset="utf-8">
   
    <div class="row">
        <div class="form-group col-xs-12">
            <div align="center" id="img_src_val"></div>
        </div>
    </div>
  <div class="row">
    <div class="form-group" id="form-actions">
        <button style="margin-right:15px;" type="button" data-dismiss="modal" class="btn btn-default pull-right">Close&nbsp </button>
    </div>
  </div>	
</form>
