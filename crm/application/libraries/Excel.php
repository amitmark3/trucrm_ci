<?php
class Excel{

    private $excel;

    public function __construct() {
        // initialise the reference to the codeigniter instance
        require_once APPPATH.'third_party/PHPExcel.php';
        $this->excel = new PHPExcel();    
    }

    public function load($path) {
		$inputFileType = PHPExcel_IOFactory::identify($path);
        //$objReader = PHPExcel_IOFactory::createReader('Excel5');
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
        $this->excel = $objReader->load($path);
    }

    public function save($path) {
        // Write out as the new file
		$inputFileType = PHPExcel_IOFactory::identify($path);
        //$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, $inputFileType);
        return $objWriter->save($path);
    }

    public function stream($filename) {       
        header('Content-type: application/ms-excel');
        header("Content-Disposition: attachment; filename=\"".$filename."\""); 
        header("Cache-control: private");        
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');    
    }

    public function  __call($name, $arguments) {  
        // make sure our child object has this method  
        if(method_exists($this->excel, $name)) {  
            // forward the call to our child object  
            return call_user_func_array(array($this->excel, $name), $arguments);  
        }  
        return null;  
    }  
}
?>