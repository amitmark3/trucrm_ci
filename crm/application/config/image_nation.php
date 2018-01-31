<?php defined('BASEPATH') OR exit('No direct script access allowed');

// set the image library that will be used by the image_lib.
$config['image_library'] = 'gd2';

// if there is a default source directory for the images; the source directory should be relative to the main index.php
$config['source_directory'] = 'uploads';

// if there is a default parent directory for the images; if not, leave empty and it will use the source directory as parent directory
$config['parent_directory'] = '';

// if we want different sizes to be automatically put inside directories having the same name as the sizes
$config['size_folders'] = FALSE;

// default sizes that will automatically be processed
$config['default_sizes'] = '25x25|160x160|800x600|1600x1200';

// if we want the images to have the dimensions we've mentioned in $_default_sizes (images will be cropped and then resized), then we must set $_keep_aspect_ratio to FALSE. if we only want to resize the images, then we set it to TRUE
$config['keep_aspect_ratio'] = TRUE;

// $_default_master_dim only works if we do resizing without cropping (that is, if $_keep_aspect_ratio is set to TRUE)
$config['default_master_dim'] = 'auto';

// here we say what is our preference when the cropping is done. we want the cropping to be done from the center of the image (where the action is actually happening inside a photo)
$config['default_style'] = ['vertical' => 'center', 'horizontal' => 'center'];

// if we are sure that there is no possibility that the overwriting of existing files would affect our application, we can set this one to TRUE. but I think is much better to leave it to FALSE
$config['overwrite_images'] = FALSE;

// we can set a default quality for the processed image. Usually should be set to 70%
$config['default_quality'] = '75%';