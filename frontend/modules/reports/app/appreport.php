<?php
namespace frontend\modules\reports\app;
use PhpOffice\PhpSpreadsheet\Spreadsheet as phpSpreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii2tech\spreadsheet\Spreadsheet as yii2techSpreadsheet;

class appreport extends yii2techSpreadsheet
{
    public $model;
    public $location="";
	public function init(){
		$this->location = \Yii::$app->basePath.'/modules/reports/app/';
    }
    public function loaddoc()
    {
        $this->setDocument(IOFactory::load($this->location."app.xlsx"));
        $this->getDocument()->getActiveSheet()->setCellValue('C2',$this->model->description);
        $this->getDocument()->getActiveSheet()->setCellValue('C3',$this->model->description);

        #set password
        $this->getDocument()->getActiveSheet()->getProtection()->setSheet(true);
        $this->getDocument()->getSecurity()->setLockWindows(true);
        $this->getDocument()->getSecurity()->setLockStructure(true);
        $this->getDocument()->getSecurity()->setWorkbookPassword("babala");
    }
    public function render()
    {
        //overrides the render so that it would do nothing with cdataactiveprovider
        return $this;
    }
}
