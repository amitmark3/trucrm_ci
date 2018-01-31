<div class="row">
    <div class="col-xs-12">
        <div class="box">
			<div class="grid">
				<table class="table table-striped table-bordered" style="width: 50%; padding-top:30px;" align="center">
                    <thead>
                        <tr>
                            <th class="no-print">Folder</th>
                            <th class="no-print">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						<tr>
							<td><a href="<?php echo site_url('documents/index/images/'); ?>" style="font-size: 17px;"><i class="fa fa-folder"style="color:#efd83b; font-size:50px; vertical-align:middle;"></i>&nbsp;&nbsp; &nbsp;Images</a></td>
							<td>&nbsp;</td>
						</tr>	
						<?php
						for($i=0; $i < count($subDirArr); $i++)
						{
						?>
							<tr>
								<td><a href="index/<?= $subDirArr[$i] ?>" style="font-size: 17px;"><i class="fa fa-folder"style="color:#efd83b; font-size:50px; vertical-align:middle;"></i>&nbsp;&nbsp; &nbsp;<?= $subDirArr[$i] ?></a></td>
								<td>
									<?php
									if($subDirArr[$i] == 'avatars') {
									
									} else { ?>
										<a href="rename_folder/<?= $subDirArr[$i] ?>"><i class="fa fa-lg fa-pencil-square"></i> Edit </a> &nbsp;&nbsp;&nbsp; <a class="confirm" data-folder="<?= $subDirArr[$i] ?>" ><i class="fa fa-lg fa-times-circle-o"></i> Delete </a>
									<?php } ?>	
								</td>
							</tr>
						<?php 
						}
						?>
						
					</tbody>
				</table>
			</div>
        </div>
    </div>
</div>