<?php

namespace frontend\modules\procurement\controllers;
use common\models\procurement\Bids;
use common\models\procurement\Bidsdetails;
use common\models\procurement\BidsdetailSearch;
use common\models\procurement\BidsSearch;
use common\models\procurement\Purchaseorder;
use common\models\procurement\Purchaseorderdetails;
use common\models\procurement\Purchaserequest;
use common\models\procurement\Assignatory;
use common\models\procurement\Purchaserequestdetails;
use common\models\procurement\Purchaserequestsearchdetails;
use common\models\procurement\Supplier;
use kartik\grid\EditableColumnAction;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\Controller;
use kartik\mpdf\Pdf;

class BidsController extends Controller
{
    /***
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new Purchaserequestsearchdetails();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [

            'editPrice' => [                                       // identifier for your editable action
                'class' => EditableColumnAction::className(),     // action class name
                'modelClass' => Purchaserequestdetails::className(),                // the update model class
                'outputValue' => function ($model, $attribute, $key, $index) {
                    $fmt = Yii::$app->formatter;
                    $value = $model->$attribute;
                    // your attribute value
                    $mystatus = $model->purchase_request_details_status;
                    if ($mystatus==2) {
                        if ($attribute === 'purchase_request_details_bid_price') {           // selective validation by attribute
                            return $fmt->asDecimal('0.00', 2);       // return formatted value if desired
                        }
                        return '';
                    }else{
                        $model->purchase_request_details_status = 1;
                        $model->save();
                        if ($attribute === 'purchase_request_details_bid_price') {           // selective validation by attribute
                            return $fmt->asDecimal($value, 2);       // return formatted value if desired
                        }
                        return '';
                    }
                                              // empty is same as $value
                },
                'outputMessage' => function ($model, $attribute, $key, $index) {
                    return ''; // any custom error after model save
                },
            ],
            'editRemarks' => [                                       // identifier for your editable action
                'class' => EditableColumnAction::className(),     // action class name
                'modelClass' => Bidsdetails::className(),                // the update model class
                'outputValue' => function ($model, $attribute, $key, $index) {
                    $fmt = Yii::$app->formatter;
                    $value = $model->$attribute;
                    // your attribute value
                    $model->save();
                    if ($attribute === 'bids_remarks') {           // selective validation by attribute
                        return $fmt->asText($value);       // return formatted value if desired
                    }
                    return '';
                    // empty is same as $value
                },
                'outputMessage' => function ($model, $attribute, $key, $index) {
                    return ''; // any custom error after model save
                },
            ],


              'editDescription' => [                                       // identifier for your editable action
            'class' => EditableColumnAction::className(),     // action class name
            'modelClass' => Bidsdetails::className(),                // the update model class
            'outputValue' => function ($model, $attribute, $key, $index) {
                $fmt = Yii::$app->formatter;
                $value = $model->$attribute;
                // your attribute value

                //$model->save();
                if ($attribute === 'bids_item_description') {           // selective validation by attribute
                    return $fmt->asText($value);       // return formatted value if desired
                }
                return '';
                // empty is same as $value
            },
            'outputMessage' => function ($model, $attribute, $key, $index) {
                return ''; // any custom error after model save
            },
            ]

            ,


            'editPrice2' => [                                       // identifier for your editable action
                'class' => EditableColumnAction::className(),     // action class name
                'modelClass' => Bidsdetails::className(),                // the update model class
                'outputValue' => function ($model, $attribute, $key, $index) {
                    $fmt = Yii::$app->formatter;
                    $value = $model->$attribute;
                        //$model->purchase_request_details_status = 1;
                        $model->save();
                        if ($attribute === 'bids_price') {           // selective validation by attribute
                            return $fmt->asDecimal($value, 2);       // return formatted value if desired
                        }
                        return '';
                    // empty is same as $value
                },
                'outputMessage' => function ($model, $attribute, $key, $index) {
                    return ''; // any custom error after model save
                },
            ]



        ]);

    }




    /**
     * Displays a single PurchaseRequest model.
     * @param integer $id
     * @return mixed
     */


    public function actionCreatepo()
    {
        $request = Yii::$app->request;
        $bids = new Bids();
        $bidsdetails = new Bidsdetails();
        $prdetails = new Purchaserequestdetails();
        $supplierid = $request->post('supplierid');
        $array_rows = $request->post('array_rows');
        $data_table = $request->post('tabledata');
        $pID = $request->post('pID');
        $arr = json_decode($data_table, true);
        $data = array();
        $cont = "";
        $prdetailss = $this->getprDetails($pID);
        $b = "";
        $connection = Yii::$app->db;
        $procCon = Yii::$app->procurementdb;
        $transaction = $connection->beginTransaction();
        try {
            $bids->purchase_request_id = $pID;
            $bids->supplier_id = $supplierid;
            $bids->save();
            $data = array();
            foreach ($prdetailss as $pr) {
                $unit = "Units";
                $itemdescription = $pr["purchase_request_details_item_description"];
                $quantity = $pr["purchase_request_details_quantity"];
                $price = $pr["purchase_request_details_bid_price"];
                $stats = $pr["purchase_request_details_status"];
                $requestID = $pr["purchase_request_id"];
                $prdetailID = $pr["purchase_request_details_id"];
                if ($stats == 1) {
                    $data[] = [$bids->bids_id, $unit, $itemdescription, $quantity, $price, $requestID, $prdetailID];
                }
            }
            $updateStatus = "UPDATE tbl_purchase_request_details SET purchase_request_details_status=0 , purchase_request_details_bid_price=0 WHERE `purchase_request_id`=" . $pID ." AND purchase_request_details_status<>2";
            $procCon->createCommand($updateStatus)->query();
            $procCon->createCommand()->batchInsert
            ('tbl_bids_details', ['bids_id', 'bids_unit', 'bids_item_description', 'bids_quantity', 'bids_price', 'purchase_request_id', 'purchase_request_details_id'], $data)
                ->execute();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }


    }

    public function GeneratePONumber()
    {
        $characters = "PO";
        $yr = date('y');
        $mt = date('m');
        $con = Yii::$app->db;
        $command = $con->createCommand("SELECT COUNT(`tbl_purchase_order`.`purchase_order_id`) + 1 AS NextNumber FROM `fais-procurement`.`tbl_purchase_order`");
        $nextValue = $command->queryAll();
        foreach ($nextValue as $bbb) {
            $a = $bbb['NextNumber'];
        }
        $nextValue = $a;
        $documentcode = $characters . "-" . $yr . "-" . $mt . "-";
        $documentcode = $documentcode . str_pad($nextValue, 4, '0', STR_PAD_LEFT);
        return $documentcode;
    }


    public function actionCreatepurchase()
    {
        $request = Yii::$app->request;
        $array_rows = $request->post('array_rows');
        $data_table = $request->post('tabledata');
        $arr2 = json_decode($array_rows, true);
        $arr = json_decode($data_table, true);
        $pOrder = new PurchaseOrder();
        $s = 0;
        $mstat="";
        $PoID = $this->GeneratePONumber();
        $data = $arr2;
        $sizess = count($data) - 1;
        $connection = Yii::$app->db;
        $procCon = Yii::$app->procurementdb;
        $transaction = $procCon->beginTransaction();
        try {
            $curdate = date('Y-m-d');
            $pOrder->purchase_order_number = $PoID;
            $pOrder->purchase_order_date = $curdate;
            $pOrder->save();
            do {
                $mdata = $data[$s];
                $biddetails = $this->getbidDetails2($mdata);
                foreach ($biddetails as $bid) {
                    $purchase_request_details_id = $bid["purchase_request_details_id"];
                    Purchaserequestdetails::updateAll(['purchase_request_details_status' => 2], 'purchase_request_details_id = ' . $purchase_request_details_id);
                    Bidsdetails::updateAll(['bids_details_status' => 4], 'purchase_request_details_id = ' . $purchase_request_details_id);
                    $stats = $this->checkbidDetailStatus($mdata);
                    foreach ($stats as $stt) {
                        $mstat = $stt["ifexist"];
                    }
                    if ($mstat==0) {
                        $procCon->createCommand()->insert
                        ('tbl_purchase_order_details', ['purchase_order_id' => $pOrder->purchase_order_id, 'bids_details_id' => $mdata])
                            ->execute();
                    }
                }
                Bidsdetails::updateAll(['bids_details_status' => 1], 'bids_details_id = ' . $mdata);
                    $s++;
            } while ($s <= $sizess);
            $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        return $mstat;
    }
    
    public function actionCancelpo(){
        if(Yii::$app->request->isPost){
            $po_num = $_POST['po_num'];
            $con = Yii::$app->procurementdb;
            $purchase_order = Purchaseorder::find()->where(['purchase_order_number' => $po_num])->one();
            $purchase_order->purchase_order_status = 2;
    
            $po_details = Purchaseorderdetails::find()->where(['purchase_order_id' => $purchase_order->purchase_order_id])->all();
            $sql_update_bids_detail_string = '';
            $sql_update_pr_deatail_string = '';
            foreach ($po_details as $po_detail){
                $bids_detail = Bidsdetails::find()->where(['bids_details_id' => $po_detail->bids_details_id])->one();
                $sql_update_bids_detail = 'UPDATE tbl_bids_details SET bids_details_status = 0 WHERE purchase_request_details_id  = ' . $bids_detail->purchase_request_details_id . ';';
                $sql_update_bids_detail_string .= $sql_update_bids_detail;
    
                $sql_update_pr_deatail ='UPDATE tbl_purchase_request_details SET purchase_request_details_status = 0 WHERE purchase_request_details_id = ' . $bids_detail->purchase_request_details_id . ';';
                $sql_update_pr_deatail_string .= $sql_update_pr_deatail;
            }
            $purchase_order->save(false);
            $con->createCommand($sql_update_pr_deatail_string)->execute();
            $con->createCommand($sql_update_bids_detail_string)->execute();
            $response['id'] = $po_num;
            $response['success'] = true;
            $response['message'] = 'PO Canceled...';
            
    
            return json_encode($response);
        }else{
           throw new yii\web\ForbiddenHttpException('You are not allowed to perform this action...');
        }
    }

    public function actionCheckselected()
    {
        $session = Yii::$app->session;
        $request = Yii::$app->request->post();
        $id = $request["chkRow"];
        $old_bids_id = $session["temp_bids_id"];
        $checkboxStatus = $request["chkStatus"];
        $chkLotBidStatus = $request["chkBids"];
        $bidsdetail = new Bidsdetails();
        $bidsdetail = $this->findbidsdetail($id);
        $biddetails = $this->getpbidDetails($bidsdetail->purchase_request_details_id);
        $checkStatus = $this->actionCheckbidstatus($bidsdetail->purchase_request_id);
        $bID = $bidsdetail->bids_details_id;
        $bbID = $bidsdetail->bids_id;
        foreach ($biddetails as $bid) {
            $bids_details_status = $bid["bids_details_status"];
            $bids_ids = $bid["bids_id"];
            $purchase_request_details_id = $bid["purchase_request_details_id"];
            if ($chkLotBidStatus == 1) {
                if ($checkboxStatus == 1) {
                    Bidsdetails::updateAll(['bids_details_status' => 3], 'bids_details_id = ' . $bID);
                } else {
                    Bidsdetails::updateAll(['bids_details_status' => 0], ['and', ['purchase_request_details_id' => $purchase_request_details_id], ['<>', 'bids_details_status', 1], ['<>', 'bids_details_status', 4]]);
                }
                if ($checkStatus == 0) {
                    $session["temp_bids_id"] = $bbID;
                } else {
                    if ($old_bids_id <> $bbID) {
                        if ($bids_details_status <> 0 || $bids_details_status <> 1 || $bids_details_status <> 4) {
                            Bidsdetails::updateAll(['bids_details_status' => 0], ['and', ['bids_id' => $old_bids_id], ['<>', 'bids_details_status', 1], ['<>', 'bids_details_status', 4]]);
                            Bidsdetails::updateAll(['bids_details_status' => 0], ['and', ['bids_id' => $bbID], ['<>', 'bids_details_status', 1], ['<>', 'bids_details_status', 4]]);
                        }
                    } else {
                        $session["temp_bids_id"] = $bbID;
                    }
                    $session["temp_bids_id"] = $bbID;
                }
            } else {
                $bids_details_status = $bid["bids_details_status"];
                $bids_ids = $bid["bids_id"];
                $purchase_request_details_id = $bid["purchase_request_details_id"];
                if ($checkboxStatus == 1) {
                    Bidsdetails::updateAll(['bids_details_status' => 0], ['and', ['purchase_request_details_id' => $purchase_request_details_id], ['=', 'bids_details_status', 3]]);
                    Bidsdetails::updateAll(['bids_details_status' => 3], 'bids_details_id = ' . $bID);
                } else {
                    Bidsdetails::updateAll(['bids_details_status' => 0], ['and', ['purchase_request_details_id' => $purchase_request_details_id], ['<>', 'bids_details_status', 1], ['<>', 'bids_details_status', 4]]);
                }
            }
        }
        return $checkStatus;
    }

    public function actionChecksupplier()
    {
        $request = Yii::$app->request;
        $supplierid = $request->post('supplierid');
        $pID = $request->post('pID');
        $con = Yii::$app->procurementdb;
        $sql = "SELECT COUNT(`tbl_bids`.`bids_id`) AS ifExist FROM `tbl_bids` WHERE `tbl_bids`.`supplier_id`='" . $supplierid . "' AND `tbl_bids`.`purchase_request_id`='" . $pID . "'";
        $CheckIfExist = $con->createCommand($sql)->queryAll();
        foreach ($CheckIfExist as $Exist) {
            $x = $Exist["ifExist"];
        }
        return $sql ;

    }

    public function actionCheckbidstatus($purchase_id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT COUNT(*) AS bidExists FROM `tbl_bids_details` WHERE bids_details_status=3 AND purchase_request_id=" . $purchase_id;
        $CheckIfExist = $con->createCommand($sql)->queryAll();
        foreach ($CheckIfExist as $Exist) {
            $x = $Exist["bidExists"];
        }
        return $x;
    }

    public function actionCancelbids()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $bid = $request->get('bid');
        $pid = $request->get('pid');
        $pStatus = $request->get('bid_status');

        if ($pStatus==1) {
            Bidsdetails::updateAll( 
                [
                'bids_details_status'=> '0' , 
                ],'bids_id = ' . $bid);
            Purchaserequestdetails::updateAll(
                [
                'purchase_request_details_status'=> '0' , 
                ],'purchase_request_id = ' . $pid);; 
        }else{
            Bidsdetails::updateAll( 
                [
                'bids_details_status'=> '0' , 
                ],'bids_id = ' . $bid);
            Purchaserequestdetails::updateAll(
                [
                'purchase_request_details_status'=> '0' , 
                ],'purchase_request_id = ' . $pid);

            /* 
            foreach (Bids::find()->where('bids_id='.$bid)->all() as $user) {
                $user->delete();
            }
            foreach (Bidsdetails::find()->where('bids_details_id='.$bid)->all() as $user) {
                $user->delete();
            }
            */
        }
        $model = $this->findModel($pid);
        $m = $this->findSupplier();
        $prdetails = $this->getprDetails($pid);
        $biddetails = $this->getbidDetails($pid);
        $ListPOprovider = $this->getpoDetails($pid);
        $searchModel = new Purchaserequestsearchdetails();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModelBid = new BidsSearch();
        $bidsProvider = $searchModelBid->search(Yii::$app->request->queryParams);
        $dataProvider->query->where('purchase_request_id=' . $pid);

        return $this->renderAjax('_bids', [
            'model' => $model, 'prdetails' => $prdetails, 'biddetails' => $biddetails, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
            'searchModelBid' => $searchModelBid, 'bidsProvider' => $bidsProvider, 'ListPOprovider' => $ListPOprovider , 'supp' => $m
        ]);

    }


    public function actionRemovebids()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $bid = $request->get('bid');
        $pid = $request->get('pid');
        $pStatus = $request->get('bid_status');

        foreach (Bidsdetails::find()->where('bids_details_id='.$id)->all() as $user) {
            $user->delete();
        }

        $model = $this->findModel($pid);
        $m = $this->findSupplier();
        $prdetails = $this->getprDetails($pid);
        $biddetails = $this->getbidDetails($pid);
        $ListPOprovider = $this->getpoDetails($pid);
        $searchModel = new Purchaserequestsearchdetails();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModelBid = new BidsSearch();
        $bidsProvider = $searchModelBid->search(Yii::$app->request->queryParams);
        $dataProvider->query->where('purchase_request_id=' . $pid);

        return $this->renderAjax('_bids', [
            'model' => $model, 'prdetails' => $prdetails, 'biddetails' => $biddetails, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
            'searchModelBid' => $searchModelBid, 'bidsProvider' => $bidsProvider, 'ListPOprovider' => $ListPOprovider , 'supp' => $m
        ]);

    } 
    
    public function actionReport()
{
    $request = Yii::$app->request;
    $id = $request->post('id');
    $supplier = $request->post('cbosupplier');
    $address = $request->post('txtaddress');
    $sub = $request->post('txtsubmission');
    $employee = $request->post('cboemployees');
    $employee = explode("|",$employee);
    $sub = date("F j, Y", strtotime($sub) );
    $model = $this->findModel($id);
    $prdetails = $this->getprDetails($model->purchase_request_id);
    $content = $this->renderPartial('_report', ['prdetails' => $prdetails, 'model' => $model]);
    $assig =$this->findAssignatory(3);
    $pdf = new Pdf();
    $pdf->format = pdf::FORMAT_A4;
    $pdf->orientation = Pdf::ORIENT_PORTRAIT;
    $pdf->destination = $pdf::DEST_BROWSER;
    //$sub = date('F j, Y',$sub);
    $pdf->content = $content;
    $pdf->cssFile = '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css';
    $pdf->cssInline = '.kv-heading-1{font-size:18px}.nospace-border{border:0px;}.no-padding{ padding:0px;}.print-container{font-size:11px;font-family:Arial,Helvetica Neue,Helvetica,sans-serif;} h1 {border-bottom: 2px solid blackfont-weight:normal;margin-bottom:5px;width: 140px;}';
    $pdf->marginTop = 157;
    $pdf->marginBottom = 50;
    $headers = '
    <table width="100%">
<tbody>
<tr style="height: 43.6667px;">
<td style="width: 82.4103%; height: 43.6667px;">
<p>&nbsp;</p>
</td>
<td style="width: 12.5897%; height: 43.6667px;">
<table border="1" width="100%" style="border-collapse: collapse;">
<tbody>
<tr>
<td>
<p><h6><strong>FASS-PUR F06</strong>&nbsp; Rev. 0/ 08-16-07</h6></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>

<table style="width: 100%;">
<tbody>
<tr>
<td style="text-align: center;">Republic of the Philippines</td>
</tr>
<tr>        
<td style="text-align: center;"><strong>'.$assig->CompanyTitle.'</strong></td>
</tr>
<tr>
<td style="text-align: center;">'.$assig->RegionOffice.'</td>
</tr>
<tr>
<td style="text-align: center;">'.$assig->Address.'</td>
</tr>
<tr>
<td style="text-align: center;">&nbsp;</td>
</tr>
</tbody>
</table>

<table style="width: 100%;">
<tbody>
<tr style="height: 12px;">
<td style="height: 12px; width: 50%;font-size:11px;">Standard Form Number&nbsp;: SF-GOOD-60</td>
<td style="height: 12px; width: 50%;font-size:11px;">Project Ref.No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
if($model->purchase_request_referrence_no=='') {
    $headers=$headers.'_______________________________________';
}else{
    $headers=$headers.'<span style="text-decoration:underline;">'.$model->purchase_request_referrence_no.'</span>';
}

$headers=$headers.'</td>
</tr>
<tr style="height: 12.6364px;">
<td style="height: 12.6364px; width: 50%;font-size:11px;">Revised on&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: May 24, 2004&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
<td style="height: 12.6364px;font-size:11px;">Name of the Project&nbsp; &nbsp; &nbsp; &nbsp;: ';
if($model->purchase_request_project_name=='') {
    $headers=$headers.'_______________________________________';
}else{
    $headers=$headers.'<span style="text-decoration:underline;">'.$model->purchase_request_project_name.'</span>';
}

$headers=$headers.'</td>
</tr>
<tr style="height: 12px;">
<td style="width: 50%; height: 12px;font-size:11px;">Standard Form Title&nbsp; &nbsp; &nbsp; &nbsp;: <span style="text-decoration: underline;">REQUEST FOR QUOTATION</span></td>
<td style="height: 12px;font-size:11px;">Location of the Project&nbsp; &nbsp;: ';
if($model->purchase_request_location_project=='') {
    $headers=$headers.'_______________________________________';
}else{
    $headers=$headers.'<span style="text-decoration:underline;">'.$model->purchase_request_location_project.'</span>';
}

$headers=$headers.'</td>
</tr>
<tr style="height: 12px;">
<td style="height: 12px;">&nbsp;</td>
<td style="height: 12px;">&nbsp;</td>
</tr>
<tr style="height: 12px;">
<td style="height: 12px;font-size:11px;">';

if($supplier=='') {
    $headers=$headers.'_______________________________________';
}else{
     $headers=$headers.'<span style="text-decoration:underline;">'.strtoupper($supplier).'</span>';
}

$headers=$headers.'</td>


<td style="height: 12px;">&nbsp;</td>
</tr>
<tr style="height: 12px;">
<td style="height: 12px;font-size:11px;">';

if($address=='') {
    $headers=$headers.'_______________________________________';
}else{
    $headers=$headers.'<span style="text-decoration:underline;">'.$address.'</span>';
}

$headers=$headers.'</td>

<td style="height: 12px;">&nbsp;</td>
</tr>
<tr style="height: 12px;">
<td style="height: 12px;font-size:11px;" colspan="2"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please quote  your  lowest   price  on  the   item/s  listed   below,  subject  to  the   General   Conditions   on   the  last page,  stating  the   shortest   time  of   delivery
  and   submit   your  quotation  duly   signed  by  your  representative   not   later than <span style="text-decoration:underline;">'.$sub.'</span> in an envelope.</p></td>
</tr>
</tbody>
</table>
<div style="height:30px;"></div>
<table style="width:100%;">
<tr>
<td style="width:80%;"></td>
<td style="width:20%;text-align:center;"><td>
</tr>
    <tr>
        <td style="width:80%;"></td>
        <td style="width:35%;font-size:13px;text-align:center;"><b>Ronnel B. Gundoy</b><br/>Supply Officer<td>
    </tr>
</table>
<div style="height:25px;"></div>
<table style="width: 100%;">
<tbody>
<tr>
<td style="width: 10%;font-size:10px; vertical-align: top; text-align: left;">Note:</td>
<td style="width: 90%;font-size:10px;line-space:11px; vertical-align: top;">
<p>&nbsp;<strong>1</strong><strong>. ALL ENTRIES MUST BE TYPEWRITTEN</strong></p>
<p><strong>&nbsp;2</strong><strong>.</strong><strong> DELIVERY PERIOD WITHIN _________ CALENDAR DAYS</strong></p>
<p><strong>&nbsp;3</strong><strong>. WARRANTY SHALL</strong> <strong>B</strong><strong>E</strong> <strong>F</strong><strong>OR</strong> <strong>A</strong> <strong>P</strong><strong>ERIOD</strong> <strong>O</strong><strong>F</strong><strong> SIX (6) MONTHS FOR SUPPLIES &amp; MATERIALS, ONE(1) YEAR FOR EQUIPMENT, FROM DATE OF&nbsp; &nbsp; &nbsp;<strong>ACCEPTANCE BY THE PROCURING ENTITY.</strong></strong></p>
<p><strong>&nbsp;4. PRICE VALIDITY</strong> <strong>S</strong><strong>HALL BE FOR</strong><strong> A PERIOD OF _________ CALENDAR DAYS.</strong></p>
<p><strong>&nbsp;5. G-EPS</strong> <strong>R</strong><strong>EGISTRATION</strong><strong> CERTIFICATE SHALL BE ATTACHED UPON SUBMISSION OF THE QUOTATION</strong></p>
<p><strong>&nbsp;6</strong><strong>. BIDDERS</strong> <strong>SH</strong><strong>A</strong><strong>LL SUBMIT</strong><strong> ORIGINAL BROCHURES SHOWING CERTIFICATIONS OF THE PRODUCT BEING OFFERED</strong></p>
</td>
</tr>
</tbody>
</table>

<div style="height:35px;"></div>
<table style="width:100%;border-collapse:collapse;" border="1">
<thead>
    <tr>
        <th style="text-align:center;font-size:11px;"> Item/s No.</th>
        <th style="text-align:center;font-size:11px;"> Item/s Description.</th>
        <th style="text-align:center;font-size:11px;"> Qty.</th>
        <th style="text-align:center;font-size:11px;"> Unit Price</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td width="10%" style="height:375px;"></td>
        <td width="60%" style="height:375px;"></td>
        <td width="15%" style="height:375px;"></td>
        <td width="15%" style="height:375px;"></td>
    </td>
</tbody>
</table>

<div style="height:10px"></div>

<table style="width: 100%;">
<tbody>
<tr style="height: 12px;">
<td style="width: 45.0699%; height: 12px;font-size:9px;">Brand and Model&nbsp;:  __________________________________________</td>
<td style="width: 53.5839%; height: 12px;font-size:9px;">&nbsp;</td>
</tr>
<tr style="height: 12px;">
<td style="width: 45.0699%; height: 12px;font-size:9px;">Delivery Period &nbsp;&nbsp;&nbsp;: __________________________________________</td>
<td style="width: 53.5839%; height: 12px;font-size:9px;">&nbsp;</td>
</tr>
<tr style="height: 12px;">
<td style="width: 45.0699%; height: 12px;font-size:9px;">Warranty &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: __________________________________________ </td>                                                                                                                                                           </td>
<td style="width: 53.5839%; height: 12px;font-size:9px;">&nbsp;</td>
</tr>
<tr style="height: 12px;">
<td style="width: 45.0699%; height: 12px;font-size:9px;">Price Validity&nbsp; &nbsp; &nbsp; &nbsp;: __________________________________________</td>
<td style="width: 53.5839%; height: 12px;font-size:9px; text-align: right;">&nbsp;</td>
</tr>
<tr style="height: 12.7273px;">
<td style="width: 45.0699%; height: 12.7273px;font-size:9px;">&nbsp;</td>
<td style="width: 53.5839%; height: 12.7273px;font-size:9px; text-align: right;">___________________________________________________</td>
</tr>
<tr style="height: 12.7273px;">
<td style="width: 45.0699%; height: 12.7273px;font-size:9px;">&nbsp;</td>
<td style="width: 53.5839%; height: 12.7273px;font-size:9px; text-align: center;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Supplier</td>
</tr>
</tbody>
</table>


    ';


    $LeftFooterContent = '<div style="text-align: left;font-weight: bold;font-size:11px;">' . $model->purchase_request_number . '</div><div style="text-align: left;font-size:11px;font-weight: lighter"><span style="font-size:11px;">'.date("F j, Y").'</span></div>';
    $RightFooterContent = '<div style="text-align: right;padding-top:-50px;font-size:11px;">Page {PAGENO} of {nbpg}</div>';
    $oddEvenConfiguration =
        [
            'L' => [ // L for Left part of the header
                'content' => $LeftFooterContent,
            ],
            'C' => [ // C for Center part of the header
                'content' => '',
            ],
            'R' => [
                'content' => $RightFooterContent,
            ],
            'line' => 0, // That's the relevant parameter
        ];
    $headerFooterConfiguration = [
        'odd' => $oddEvenConfiguration,
        'even' => $oddEvenConfiguration
    ];
    $pdf->options = [
        'title' => 'Report Title',
        'defaultheaderline' => 0,
        'defaultfooterline' => 0,
        'subject' => 'Report Subject'];

    $pdf->methods = [
        'SetHeader' => [$headers],
        'SetFooter' => [$headerFooterConfiguration],
    ];

    return $pdf->render();
}


    public function actionReportabstract()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $prdetails = $this->getabstractofbids($model->purchase_request_id);
        $con = Yii::$app->procurementdb;
        $columns = [];
        if (empty($queryres)) {
            $columns = $con->getTableSchema('tmpheader')->getColumnNames();
        }
        $assig = $this->getassig();
        $content = $this->renderPartial('_report_abstract', ['prdetails' => $prdetails, 'model' => $model , 'columns' => $columns]);
        $pdf = new Pdf();
        $pdf->format = [216,330];
        $pdf->orientation = Pdf::ORIENT_LANDSCAPE;
        //$pdf->format = Pdf::FORMAT_LEGAL;
        $pdf->destination = Pdf::DEST_BROWSER;
        $pdf->marginLeft=10;
        $pdf->marginHeader=5;
        $pdf->marginTop=55;
        $pdf->marginBottom=65;
        $pdf->defaultFontSize=11;
        $pdf->content = $content;
        $pdf->cssFile = '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css';
        $pdf->cssInline = '.kv-heading-1{font-size:18px}.nospace-border{border:0px;}.no-padding{ padding:0px;}.print-container{font-size:11px;font-family:Arial,Helvetica Neue,Helvetica,sans-serif; }';
       if($model->purchase_request_referrence_no==''){$ref='__________________';}else{$ref=$model->purchase_request_referrence_no;}
       if($model->purchase_request_project_name==''){$pname='__________________';}else{$pname=$model->purchase_request_project_name;}
       if($model->purchase_request_location_project==''){$pproject='__________________';}else{$pproject=$model->purchase_request_location_project;}
       if(strlen($pname) > 40){$pname = '<span style="font-size: 6px;">'.$pname.'</span>';}
       $assigs = $this->findAssignatory(4);
       $headers='
       <div style="width: 100px;border: 1px solid black;margin:10px;font-weight:bold;text-align:center;float:right;padding:0px;">FASS-PUR F07 <br> <span style="font-weight:lighter;">Rev. 0/ 08-16-07</span></div>
       <table border="0" style="width: 100%; table-collapsed: collapsed;">
       <tbody>
       <tr style="height: 12px;">   
       <td style="width: 27%; height: 12px;">&nbsp;</td>
       <td style="text-align: center; width: 50%; height: 12px;">Republic of The Philippines</td>
       <td style="text-align: center; width: 10%; height: 12px;">&nbsp;</td>
       <td style="width: 17%; height: 12px;">&nbsp;</td>
       </tr>
       <tr style="height: 12px;">
       <td style="width: 27%; height: 12px;">&nbsp;</td>
       <td style="text-align: center; width: 50%; height: 12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$assigs->CompanyTitle.'</strong></td>
       <td style="text-align: right; width: 10%; height: 12px;">&nbsp;</td>
       <td style="text-align:right;width: 17%;font-size:11px;padding:2px;padding-right:10px; height: 50px;vertical-align:top;border:none;" rowspan="2"></td>
       </tr>
       <tr style="height: 12px;">
       <td style="width: 27%; height: 12px;">&nbsp;</td>
       <td style="text-align: left; width: 45%; height: 12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$assigs->RegionOffice.'</td>
       <td style="text-align: center; width: 10%; height: 12px;">&nbsp;</td>
     
       </tr>            
       <tr style="height: 12px;">
       <td style="width: 27%;font-size:11px; height: 12px;">&nbsp;Standard Form Number : SF-GOOD-40</td>
       <td style="text-align: center; width: 40.5332%; height: 12px;">&nbsp;</td>
       <td style="width: 27%;font-size:11px;  height: 12px; text-align: right;" colspan="2">Project Reference No. : '.$ref.'</td>
       </tr>
       <tr style="height: 12px;">
       <td style="width: 27%;font-size:11px; height: 12px;">&nbsp;Revised&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : May 24, 2004</td>
       <td style="text-align: left;font-size:16px; width: 40.5332%; height: 12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>ABSTRACT OF BIDS</strong></td>
       <td style="width: 10%;font-size:11px;  height: 12px; text-align: right;" colspan="2">Name of the Project.&nbsp; :'. $pname .'</td>
       </tr>
       <tr style="height: 12.2727px;">
       <td style="width: 27%; height: 12.2727px;">&nbsp;</td>
       <td style="text-align: center; width: 40.5332%; height: 12.2727px;">&nbsp;</td>
       <td style="width: 10%;font-size:11px; height: 12.2727px; text-align: right;" colspan="2">Location of The Project : '.$pproject.'</td>
       </tr>
       </tbody>
       </table>
       <div style="height:0px;"></div>';
        $fin="";
        $x=0;
        $munit="";
        $tempid ="";
        $headerd = "";
        $itemno = "";
        $qty = "";
        $unit = "";
        $item_decription = "";
        $tablecount=count($columns);
        $headers = $headers.'<table border="1" width=100% style="border-collapse:collapse;"><thead><tr class="">';
            $max = 13;//$tablecount;
                $count = $tablecount;
                $i=3;
                $h=1;
                while($i<$max) {
     
                    if($i<7) {
                         //$headers = $headers.'<th></th>';
                         if ($h==1) {
                            $headers = $headers.'<th style="font-size:7px;text-align:center;">Item No.</th>';
                         }
                         elseif($h==2) {
                            $headers = $headers.'<th style="font-size:7px;text-align:center;">QTY</th>';
                         }
                         elseif($h==3) {
                            $headers = $headers.'<th style="font-size:7px;text-align:center;">UNIT</th>';
                         }
                         elseif($h==4) {
                            $headers = $headers.'<th style="font-size:7px;text-align:center;">DESCRIPTIONS</th>';
                         }else{
                            $headers = $headers.'<th></th>';
                         }
                    }else{
                        if ($i>$count - 1) {
                            $headers = $headers.'<th style="font-size: 9px;text-align:center">N/A</th>';
                        }else{
                            if ($i>6) {
                                $headers = $headers.'<th style="font-size: 9px;text-align:center;">'.$columns[$i].'</th>';
                            }else{
                                $headers = $headers.'<th>'.$columns[$i].'</th>';
                            }
                        }
                    }
                    $i++;
                    $h++;
                }
                $headers = $headers.'</tr>';
        $headers = $headers.'</thead>
        <tr>
        <td style="height:380px;font-size: 9px; width: 5%; text-align: center;vertical-align: top;"></td>
        <td style="height:380px;font-size: 9px; width: 5%; text-align: center;vertical-align: top;"></td>
        <td style="height:380px;font-size: 9px; width: 5%; text-align: center;vertical-align: top;"></td>
        <td style="height:380px;font-size: 9px; width: 25%; text-align: center;vertical-align: top;"></td>
        <td style="height:380px;font-size: 9px; width: 10%; text-align: center;vertical-align: top;"></td>
        <td style="height:380px;font-size: 9px; width: 10%; text-align: center;vertical-align: top;"></td>
        <td style="height:380px;font-size: 9px; width: 10%; text-align: center;vertical-align: top;"></td>
        <td style="height:380px;font-size: 9px; width: 10%; text-align: center;vertical-align: top;"></td>
        <td style="height:380px;font-size: 9px; width: 10%; text-align: center;vertical-align: top;"></td>
        <td style="height:380px;font-size: 9px; width: 10%; text-align: center;vertical-align: top;"></td>
        </tr>
        </table>';

        foreach ($assig as $sg) {
           $assig1 =  $sg["Assig1"];
           $assig2 =  $sg["Assig2"];
           $assig3 =  $sg["Assig3"];
           $assig4 =  $sg["Assig4"];
           $assig5 =  $sg["Assig5"];
           $assig6 =  $sg["Assig6"];
           $assig7 =  $sg["Assig7"];
           $Assig1Position =  $sg["Assig1Position"];
           $Assig2Position =  $sg["Assig2Position"];
           $Assig3Position =  $sg["Assig3Position"];
           $Assig4Position =  $sg["Assig4Position"];
           $Assig5Position =  $sg["Assig5Position"];
           $Assig6Position =  $sg["Assig6Position"];
           $Assig7Position =  $sg["Assig7Position"];
        }
        $LeftFooterContent = '
<table width="100%">
<tr>
<td style="font-size: 11px;text-align: left; width=100%" colspan="12"><b>Awarding Commitee</b></td>
</tr>
    <tr>
        <td style="font-size: 11px;text-align: center; width=16.67"><b>'.$assig1.'</b><br/>Chairperson</td>
        <td style="width: 50px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"><b>'.$assig2.'</b><br/>Member</td>
        <td style="width: 50px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"><b>'.$assig3.'</b><br/>Member</td>
        <td style="width: 50px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"><b>'.$assig4.'</b><br/>Member</td>
        <td style="height: 100px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"><b>'.$assig6.'</b><br/>Member</td>
        <td style="height: 100px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"><b>'.$assig7.'</b><br/>'.$Assig7Position.'</td>
        <td style="height: 100px;"></td>
    </tr>
    <tr>
<td style="font-size: 11px;text-align: left; width=100%" colspan="12">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$model->purchase_request_number .'</td>
</tr>
        <tr>
        <td style="font-size: 11px;text-align: center; width=16.67">'.date("F j, Y").'</td>
        <td style="width: 50px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"></td>
        <td style="width: 50px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"></td>
        <td style="width: 50px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"></td>
        <td style=""></td>
        <td style="font-size: 11px;text-align: center; width=16.67"></td>
        <td style=""></td>
        <td style="font-size: 11px;text-align: center; width=16.67">Page {PAGENO} of {nbpg}</td>
        <td style=""></td>
    </tr>
</table>
';
        $pdf->options = [
            'title' => 'ABSTRACT OF BIDS',
            'defaultheaderline' => 0,
            'defaultfooterline' => 0,
            'subject' => 'Report Abstract'];
        $pdf->methods = [
            'SetHeader' => [$headers],
            'SetFooter' => [$LeftFooterContent],
        ];

        return $pdf->render();
    }

    public function actionMtest()
    {
        $searchModel = new Purchaserequestsearchdetails();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('_test', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCheckimportid()
    {
        $request = Yii::$app->request;
        $sup_id = $request->post('sup_id');
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM `tbl_supplier` WHERE supplier_name= '".$sup_id."';";
        $checkxml = $con->createCommand($sql)->queryAll();
        return json_encode($checkxml);
    }

    public function actionView()
    {
        $request = Yii::$app->request->get();
        if ($request['id'] && $request['view']) {
            $id = $request['id'];
            $returns = $request['view'];
            $model = $this->findModel($id);
            $m = $this->findSupplier();
            $prdetails = $this->getprDetails($model->purchase_request_id);
            $biddetails = $this->getbidDetails($model->purchase_request_id);
            $ListPOprovider = $this->getpoDetails($model->purchase_request_id);
            $searchModel = new Purchaserequestsearchdetails();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $searchModelBid = new BidsSearch();
            $bidsProvider = $searchModelBid->search(Yii::$app->request->queryParams);
            $dataProvider->query->where('purchase_request_id=' . $model->purchase_request_id);
            switch ($returns) {
                case 'quotation':
                    if (Yii::$app->request->isAjax) {
                        return $this->renderAjax('_quotation', [
                            'model' => $model, 'prdetails' => $prdetails
                        ]);
                    } else {
                        return $this->render('_quotation', [
                            'model' => $model, 'prdetails' => $prdetails
                        ]);
                    }
                    break;
                case 'bids':
                    if (Yii::$app->request->isAjax) {
                        $dataProvider->sort = false;
                        return $this->renderAjax('_bids', [
                            'model' => $model, 'prdetails' => $prdetails, 'biddetails' => $biddetails, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
                            'searchModelBid' => $searchModelBid, 'bidsProvider' => $bidsProvider, 'ListPOprovider' => $ListPOprovider , 'supp' => $m
                        ]);
                    } else {
                        return $this->render('_bids', [
                            'model' => $model, 'prdetails' => $prdetails, 'biddetails' => $biddetails, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
                            'searchModelBid' => $searchModelBid, 'bidsProvider' => $bidsProvider, 'ListPOprovider' => $ListPOprovider , 'supp' => $m
                        ]);
                    }
                    break;
            }
        }
    }


    function actionRegeneratesupplier () {
        $session = Yii::$app->session;
        $request = Yii::$app->request->post();
        $id = $request["chkRow"];
        $old_bids_id = $session["temp_bids_id"];
    }

    /**
     * Finds the PurchaseRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PurchaseRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PurchaseRequest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findSupplier()
    {
        if (($model = Supplier::find()->all()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findbidsdetail($id)
    {
        if (($model = BidsDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    function getprDetails($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM `fais-procurement`.`tbl_purchase_request_details`
                INNER JOIN `fais`.`tbl_unit_type` 
                ON `tbl_purchase_request_details`.`unit_id` = `tbl_unit_type`.`unit_type_id`
                WHERE `purchase_request_id`=" . $id;
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }

    function getpr($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM `tbl_purchase_request_details` WHERE `purchase_request_id`=" . $id;
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }

    function getbidDetails($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM `tbl_bids_details` WHERE `purchase_request_id`=" . $id;
        $pordetails = $con->createCommand($sql)->queryAll();
        return $pordetails;
    }

    function getbidDetails2($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM `tbl_bids_details` WHERE `bids_details_id`='" . $id . "' AND bids_details_status<>1";
        $pordetails = $con->createCommand($sql)->queryAll();
        return $pordetails;
    }

    function getpbidDetails($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM `tbl_bids_details` WHERE `purchase_request_details_id`=" . $id;
        $pordetails = $con->createCommand($sql)->queryAll();
        return $pordetails;
    }

    function getabstractofbids($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "CALL `fais-procurement`.spGenerateAbstractOfBids(" . $id . ",1)";
        $pordetails = $con->createCommand($sql)->queryAll();
        return $pordetails;
    }

    function getassig()
    {
        $con = Yii::$app->db;
        $sql = "SELECT `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_1`) AS Assig1 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_1`) AS Assig1Position,
	       `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_2`) AS Assig2 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_2`) AS Assig2Position,
	       `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_3`) AS Assig3 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_3`) AS Assig3Position,
	       `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_4`) AS Assig4 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_4`) AS Assig4Position,
	       `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_5`) AS Assig5 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_5`) AS Assig5Position,
	       `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_6`) AS Assig6 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_6`) AS Assig6Position,
           `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_7`) AS Assig7 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_7`) AS Assig7Position
	FROM `tbl_assignatory`
	WHERE `tbl_assignatory`.`assignatory_id` = 4
";
        $pordetails = $con->createCommand($sql)->queryAll();
        return $pordetails;
    }



    function getpoDetails($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "CALL `fais-procurement`.spGenerateListPO(" . $id . ",0)";
        $pordetails = $con->createCommand($sql)->queryAll();
        $x = 0;
        foreach ($pordetails as $pr) {
            $x++;
            $data[] = ['bids_id' => $pr["bids_id"],
                'purchase_request_id' => $pr["purchase_request_id"],
                'supplier_id' => $pr["supplier_id"],
                'SupplierName' => $pr["SupplierName"],
                'purchase_order_number' => $pr["purchase_order_number"],
                'bids_unit' => $pr["bids_unit"],
                'bids_item_description' => $pr["bids_item_description"],
                'bids_quantity' => $pr["bids_quantity"],
                'bids_price' => $pr["bids_price"],
                'purchase_order_status' => $pr["purchase_order_status"]
            ];
        }
        if ($x == 0) {
            $data[] = ['bids_id' => '',
                'purchase_request_id' => '',
                'supplier_id' => '',
                'SupplierName' => '',
                'purchase_order_number' => '',
                'bids_unit' => '',
                'bids_item_description' => '',
                'bids_quantity' => '',
                'bids_price' => '',
                'purchase_order_status' => ''
            ];
        }

        $provider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['bids_id',
                    'supplier_id',
                    'SupplierName',
                    'purchase_order_number',
                    'bids_unit',
                    'bids_item_description',
                    'bids_quantity',
                    'bids_price',
                ],
            ],
        ]);
        $pordetails = $provider;
        return $pordetails;
    }


    function checkbidDetailStatus ($id) {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT count(*) AS ifexist FROM `tbl_bids_details` WHERE `tbl_bids_details`.`bids_details_status` =3 AND `tbl_bids_details`.`bids_details_id`=".$id;
        $pordetails = $con->createCommand($sql)->queryAll();
        return $pordetails;
    }

    protected function findAssignatory($id)
    {
        if (($model = Assignatory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}