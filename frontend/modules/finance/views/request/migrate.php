<?php
    use kartik\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;
    use yii\widgets\Pjax;

    use kartik\editable\Editable; 
    use kartik\grid\GridView;

    use common\models\finance\Os;
    use common\models\finance\Request;
    use common\models\procurement\Obligationrequest;
    use common\models\procurement\Disbursement;
    
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
    
    use yii\data\ArrayDataProvider;

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

    //require __DIR__ . '/../Header.php';

    $inputFileType = 'Xlsx';
    $inputFileName = 'D:/HP Files/2018/SAOB/OBLIGATION-2019.xlsx';
    $sheetname = 'OBLIGATION';

    class MyReadFilter implements IReadFilter
    {
        private $startRow = 0;

        private $endRow = 0;

        private $columns = [];

        public function __construct($startRow, $endRow, $columns)
        {
            $this->startRow = $startRow;
            $this->endRow = $endRow;
            $this->columns = $columns;
        }

        public function readCell($column, $row, $worksheetName = '')
        {
            if ($row >= $this->startRow && $row <= $this->endRow) {
                if (in_array($column, $this->columns)) {
                    return true;
                }
            }
            return false;
        }
    }

    $filterSubset = new MyReadFilter(6, 770, range('A', 'W'));

    //$helper->log('Loading file ' . pathinfo($inputFileName, PATHINFO_BASENAME) . ' using IOFactory with a defined reader type of ' . $inputFileType);
    $reader = IOFactory::createReader($inputFileType);
    //$helper->log('Loading Sheet "' . $sheetname . '" only');
    $reader->setLoadSheetsOnly($sheetname);
    //$helper->log('Loading Sheet using configurable filter');
    $reader->setReadFilter($filterSubset);
    $spreadsheet = $reader->load($inputFileName);

    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    array_shift($sheetData);
    array_shift($sheetData);
    array_shift($sheetData);
    array_shift($sheetData);
    array_shift($sheetData);
    $dataProvider = new ArrayDataProvider([
        'key' => 'A',
        'allModels' =>$sheetData,
        'pagination' => false,
    ]);

    foreach($sheetData as $data){
        echo $data['C'].' : ';
        echo $data['F'];
        echo '<br/>';
    }
    //$OS = $objPHPExcelSAOB->setActiveSheetIndex(0)->toArray(null, true, true, true);

    //echo $_SERVER['SERVER_NAME'];
    //$model = Disbursement::find()->orderBy(['dv_date' => SORT_ASC])->all();
    /*$model = Disbursement::find()->all();
    $requests = [];
    $count = 1;
    
    $table = '<table>
                <tr>
                    <th width="250px;">Orig DV#</th>
                    <th width="350px;">Payee</th>
                    <th width="350px;">Amount</th>
                    <th width="250px;">New Request #</th>
                    <th width="250px;">New OS #</th>
                    <th width="250px;">New DV #</th>
                </tr>';

    ini_set('max_execution_time', 300);
    foreach($model as $dv)
    {
        $table .= '<tr>';
        
        //echo $count.' ::: '.$dv->dv_no.' ::: '.$dv->dv_amount.' ::: '.$dv->dv_date;
        //echo '<br/>';
        $blank = '-';
        $table .= '<td>'.$dv->dv_no.'</td>';
        $table .= '<td>'.$dv->payee.'</td>';
        $table .= '<td>'.$dv->dv_amount.'</td>';
        $table .= '<td>'.$blank.'</td>';
        $table .= '<td>'.$blank.'</td>';
        $table .= '<td>'.$blank.'</td>';
        $os = Obligationrequest::find()->where('dv_no =:dv_no',[':dv_no'=>$dv->dv_no])->one();
//        if($os){
//            echo ' ::: '.$os->os_no.' ::: '.$os->amount;
//        }
//        echo '<br/>';
        
         $table .= '</tr>';
        
        $r = [
            //'request_id' => ,
            'request_number' => Request::generateRequestNumber($dv->dv_date).'  :::::  '.$dv->dv_no,
            'request_date' => $dv->dv_date,
            'request_type_id' =>1,
            'payee_id' => $dv->payee,
            'particulars' => $dv->particulars,
            'amount' => $dv->dv_amount,
            'status_id' => 10,
            'created_by' => $dv->user_id,
        ];
        
        //array_push($requests, $r);
        $count += 1;
    }
    $table .= '</table>';
    echo $table;*/
?>

<?php Pjax::begin(); ?>
      <?php
        /*echo GridView::widget([
            'id' => 'request',
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label'=>'Date',
                    'attribute'=>'B',
                ],
                
                [
                    'label'=>'Payee',
                    'attribute'=>'D',
                ],
                [
                    'label'=>'Particulars',
                    'attribute'=>'E',
                ],
                [
                    'label'=>'Amount',
                    'attribute'=>'F',
                ],
                [
                    'label'=>'OS Number',
                    'attribute'=>'C',
                    'width'=>'100px'
                ],
                [
                    'label'=>'Generated Request Number',
                    'attribute'=>'F',
                    'value'=>function($data){
                        $r = Request::generateRequestNumber($data['B'],$data['A']);
                        return $r;
                    },
                ],
                [
                    'label'=>'Generated OS Number',
                    'attribute'=>'F',
                    'value'=>function($data){
                        return date("Y-m-d",strtotime($data['B']));
                        //return 'OS #';
                    },
                ],
                [
                    'label'=>'Generated DV Number',
                    'attribute'=>'F',
                    'value'=>function(){
                        return 'DV #';
                    },
                ],
            ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
                    'heading' => '',
                    'type' => GridView::TYPE_PRIMARY,
                    'before'=>Html::button('New Request', ['value' => Url::to(['request/create']), 'title' => 'Request', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonCreateRequest']),
                    'after'=>false,
                ],
            // set your toolbar
            'toolbar' => 
                        [
                            [
                                'content'=>'',
                            ],
                            //'{export}',
                            //'{toggleData}'
                        ],
            
            'toggleDataOptions' => ['minCount' => 10],
            //'exportConfig' => $exportConfig,
            'itemLabelSingle' => 'item',
            'itemLabelPlural' => 'items'
        ]);*/
    

        ?>
<?php Pjax::end(); ?>



<pre>
<?php 
print_r($dataProvider); 
//print_r($spreadsheet);
?>
</pre>