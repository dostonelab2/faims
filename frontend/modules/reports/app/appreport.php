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
        $appavailable = $this->model->where([
            'tbl_ppmp_item.availability' => 1,
            'tbl_ppmp.year' => 2021,
            'tbl_ppmp_item.active' => 1,
            'tbl_ppmp_item.status_id' => 2
        ])->all();
        $row = 33;
        foreach($appavailable as $app){
            $this->getDocument()->getActiveSheet()->setCellValue('D'.$row,$app->description);
            $this->getDocument()->getActiveSheet()->setCellValue('E'.$row,$app->unit);
            $this->getDocument()->getActiveSheet()->setCellValue('F'.$row,$app->q1);
            $this->getDocument()->getActiveSheet()->setCellValue('G'.$row,$app->q2);
            $this->getDocument()->getActiveSheet()->setCellValue('H'.$row,$app->q3);
            $this->getDocument()->getActiveSheet()->setCellValue('I'.$row,'=SUM(F'.$row.':'.'H'.$row.')');
            $this->getDocument()->getActiveSheet()->setCellValue('J'.$row,'=I'.$row.'*AA'.$row);
            $this->getDocument()->getActiveSheet()->setCellValue('K'.$row,$app->q4);
            $this->getDocument()->getActiveSheet()->setCellValue('L'.$row,$app->q5);
            $this->getDocument()->getActiveSheet()->setCellValue('M'.$row,$app->q6);
            $this->getDocument()->getActiveSheet()->setCellValue('N'.$row,'=SUM(K'.$row.':'.'M'.$row.')');
            $this->getDocument()->getActiveSheet()->setCellValue('O'.$row,'=N'.$row.'*AA'.$row);
            $this->getDocument()->getActiveSheet()->setCellValue('P'.$row,$app->q7);
            $this->getDocument()->getActiveSheet()->setCellValue('Q'.$row,$app->q8);
            $this->getDocument()->getActiveSheet()->setCellValue('R'.$row,$app->q9);
            $this->getDocument()->getActiveSheet()->setCellValue('S'.$row,'=SUM(P'.$row.':'.'R'.$row.')');
            $this->getDocument()->getActiveSheet()->setCellValue('T'.$row,'=S'.$row.'*AA'.$row);
            $this->getDocument()->getActiveSheet()->setCellValue('U'.$row,$app->q10);
            $this->getDocument()->getActiveSheet()->setCellValue('V'.$row,$app->q11);
            $this->getDocument()->getActiveSheet()->setCellValue('W'.$row,$app->q12);
            $this->getDocument()->getActiveSheet()->setCellValue('X'.$row,'=SUM(U'.$row.':'.'W'.$row.')');
            $this->getDocument()->getActiveSheet()->setCellValue('Y'.$row,'=X'.$row.'*AA'.$row);
            $this->getDocument()->getActiveSheet()->setCellValue('Z'.$row,'=I'.$row.'+N'.$row.'+S'.$row.'+X'.$row);
            $this->getDocument()->getActiveSheet()->setCellValue('AA'.$row,$app->cost);
            $this->getDocument()->getActiveSheet()->setCellValue('AB'.$row,'=Z'.$row.'*AA'.$row);
            $this->getDocument()->getActiveSheet()->insertNewRowBefore($row + 1,1);
            $row++;
        }
        
        $this->getDocument()->getActiveSheet()->removeRow($row);
        $this->getDocument()->getActiveSheet()->removeRow($row);
        
        $appnotavailable = $this->model->where([
            'tbl_ppmp_item.availability' => 2,
            'tbl_ppmp.year' => 2021,
            'tbl_ppmp_item.active' => 1,
            'tbl_ppmp_item.status_id' => 2
        ])->all();
        $row = $row + 1;
        foreach($appnotavailable as $app){
            $this->getDocument()->getActiveSheet()->setCellValue('D'.$row,$app->description);
            $this->getDocument()->getActiveSheet()->setCellValue('E'.$row,$app->unit);
            $this->getDocument()->getActiveSheet()->setCellValue('F'.$row,$app->q1);
            $this->getDocument()->getActiveSheet()->setCellValue('G'.$row,$app->q2);
            $this->getDocument()->getActiveSheet()->setCellValue('H'.$row,$app->q3);
            $this->getDocument()->getActiveSheet()->setCellValue('I'.$row,'=SUM(F'.$row.':'.'H'.$row.')');
            $this->getDocument()->getActiveSheet()->setCellValue('J'.$row,'=I'.$row.'*AA'.$row);
            $this->getDocument()->getActiveSheet()->setCellValue('K'.$row,$app->q4);
            $this->getDocument()->getActiveSheet()->setCellValue('L'.$row,$app->q5);
            $this->getDocument()->getActiveSheet()->setCellValue('M'.$row,$app->q6);
            $this->getDocument()->getActiveSheet()->setCellValue('N'.$row,'=SUM(K'.$row.':'.'M'.$row.')');
            $this->getDocument()->getActiveSheet()->setCellValue('O'.$row,'=N'.$row.'*AA'.$row);
            $this->getDocument()->getActiveSheet()->setCellValue('P'.$row,$app->q7);
            $this->getDocument()->getActiveSheet()->setCellValue('Q'.$row,$app->q8);
            $this->getDocument()->getActiveSheet()->setCellValue('R'.$row,$app->q9);
            $this->getDocument()->getActiveSheet()->setCellValue('S'.$row,'=SUM(P'.$row.':'.'R'.$row.')');
            $this->getDocument()->getActiveSheet()->setCellValue('T'.$row,'=S'.$row.'*AA'.$row);
            $this->getDocument()->getActiveSheet()->setCellValue('U'.$row,$app->q10);
            $this->getDocument()->getActiveSheet()->setCellValue('V'.$row,$app->q11);
            $this->getDocument()->getActiveSheet()->setCellValue('W'.$row,$app->q12);
            $this->getDocument()->getActiveSheet()->setCellValue('X'.$row,'=SUM(U'.$row.':'.'W'.$row.')');
            $this->getDocument()->getActiveSheet()->setCellValue('Y'.$row,'=X'.$row.'*AA'.$row);
            $this->getDocument()->getActiveSheet()->setCellValue('Z'.$row,'=I'.$row.'+N'.$row.'+S'.$row.'+X'.$row);
            $this->getDocument()->getActiveSheet()->setCellValue('AA'.$row,$app->cost);
            $this->getDocument()->getActiveSheet()->setCellValue('AB'.$row,'=Z'.$row.'*AA'.$row);
            $this->getDocument()->getActiveSheet()->insertNewRowBefore($row + 1,1);
            $row++;
        }
        
        $this->getDocument()->getActiveSheet()->removeRow($row);
        $this->getDocument()->getActiveSheet()->removeRow($row);
        #set password
        //$this->getDocument()->getActiveSheet()->getProtection()->setSheet(true);
        //$this->getDocument()->getSecurity()->setLockWindows(true);
        //$this->getDocument()->getSecurity()->setLockStructure(true);
        //$this->getDocument()->getSecurity()->setWorkbookPassword("babala");
        
    }
    public function render()
    {
        //overrides the render so that it would do nothing with cdataactiveprovider
        return $this;
    }
}
