<?php

namespace frontend\controllers;

use Yii;
use common\models\procurement\Purchaserequest;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use common\models\procurement\VwPurchaseRequest;
use yii\data\ActiveDataProvider;
use frontend\models\PurchaserequestSearch;




class AjaxController extends \yii\web\Controller
{
    public function actionPurchaserequest()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $con = Yii::$app->procurementdb;
        if(Yii::$app->user->can('access-pr-all-items')){
        //if(Yii::$app->user->identity->username == 'Admin' || (Yii::$app->user->identity->id == 13)){
        //if(Yii::$app->user->identity->id == 13){
            $sql = "SELECT tbl_purchase_request.purchase_request_id,
                tbl_purchase_request.purchase_request_sai_number,
                tbl_purchase_request.purchase_request_number,
                tbl_division.name AS division_name,
                tbl_section.name AS section_name,
                tbl_purchase_request.purchase_request_purpose,
                fnGetAssignatoryName(purchase_request_requestedby_id) AS requested_by,
                fnGetPurchaseNo(tbl_purchase_request.purchase_request_id) AS PONum


                FROM tbl_purchase_request
                INNER JOIN fais.tbl_division
                ON tbl_division.division_id = tbl_purchase_request.division_id
                INNER JOIN fais.tbl_section
                ON tbl_section.section_id = tbl_purchase_request.section_id
                ORDER BY purchase_request_number DESC";
        //}elseif (Yii::$app->user->can('basic-role')) {
        }else{
           $id = yii::$app->user->getId();
            $sql = "SELECT tbl_purchase_request.purchase_request_id,
	    tbl_purchase_request.purchase_request_sai_number,
	    tbl_purchase_request.purchase_request_number,
	    tbl_division.name AS division_name,
	    tbl_section.name AS section_name,
	    tbl_purchase_request.purchase_request_purpose,
	    fnGetAssignatoryName(purchase_request_requestedby_id) AS requested_by,
	    fnGetPurchaseNo(tbl_purchase_request.purchase_request_id) AS PONum
	
	    
	    FROM tbl_purchase_request
	    INNER JOIN fais.tbl_division
	    ON tbl_division.division_id = tbl_purchase_request.division_id
	    INNER JOIN fais.tbl_section
	    ON tbl_section.section_id = tbl_purchase_request.section_id
	    WHERE tbl_purchase_request.user_id = ".$id." OR purchase_request_requestedby_id = ".$id."
	    ORDER BY purchase_request_number DESC";
        }/*else{
            $sql = "SELECT tbl_purchase_request.purchase_request_id,
	    tbl_purchase_request.purchase_request_sai_number,
	    tbl_purchase_request.purchase_request_number,
	    tbl_division.name AS division_name,
	    tbl_section.name AS section_name,
	    tbl_purchase_request.purchase_request_purpose,
	    fnGetAssignatoryName(purchase_request_requestedby_id) AS requested_by,
	    fnGetPurchaseNo(tbl_purchase_request.purchase_request_id) AS PONum
	    FROM tbl_purchase_request
	    INNER JOIN fais.tbl_division
	    ON tbl_division.division_id = tbl_purchase_request.division_id
	    INNER JOIN fais.tbl_section
	    ON tbl_section.section_id = tbl_purchase_request.section_id
	    ORDER BY purchase_request_number DESC";
        }*/
        
    
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }
    
    
    /*public function actionPurchaserequest()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $con = Yii::$app->procurementdb;
        if (Yii::$app->user->can('basic-role')) {
           $id = yii::$app->user->getId();
            $sql = "SELECT tbl_purchase_request.`purchase_request_id`,
	    tbl_purchase_request.`purchase_request_sai_number`,
	    tbl_purchase_request.`purchase_request_number`,
	    tbl_division.`name` AS division_name,
	    tbl_section.`name` AS section_name,
	    tbl_purchase_request.`purchase_request_purpose`,
	    fnGetAssignatoryName(purchase_request_requestedby_id) AS requested_by,
	    fnGetPurchaseNo`(tbl_purchase_request`.`purchase_request_id`) AS PONum
	
	    
	    FROM tbl_purchase_request
	    INNER JOIN fais.`tbl_division`
	    ON tbl_division.`division_id` = tbl_purchase_request.`division_id`
	    INNER JOIN fais.`tbl_section`
	    ON tbl_section.`section_id` = tbl_purchase_request.`section_id`
	    WHERE tbl_purchase_request.`user_id` = ".$id." OR purchase_request_requestedby_id = ".$id."
	    ORDER BY purchase_request_number DESC";
        }else{
            $sql = "SELECT tbl_purchase_request.`purchase_request_id`,
	    tbl_purchase_request.`purchase_request_sai_number`,
	    tbl_purchase_request.`purchase_request_number`,
	    tbl_division.`name` AS division_name,
	    tbl_section.`name` AS section_name,
	    tbl_purchase_request.`purchase_request_purpose`,
	    fnGetAssignatoryName(purchase_request_requestedby_id) AS requested_by,
	    fnGetPurchaseNo`(tbl_purchase_request`.`purchase_request_id`) AS PONum
	    FROM tbl_purchase_request
	    INNER JOIN fais.`tbl_division`
	    ON tbl_division.`division_id` = tbl_purchase_request.`division_id`
	    INNER JOIN fais.`tbl_section`
	    ON tbl_section.`section_id` = tbl_purchase_request.`section_id`
	    ORDER BY purchase_request_number DESC";
        }
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }*/
    
    /*public function actionPurchaserequest()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $con = Yii::$app->procurementdb;
        if (Yii::$app->user->can('basic-role')) {
           $id = yii::$app->user->getId();
            $sql = "SELECT tbl_purchase_request.purchase_request_id,
	    tbl_purchase_request.purchase_request_sai_number,
	    tbl_purchase_request.purchase_request_number,
	    tbl_division.name AS division_name,
	    tbl_section.name AS section_name,
	    tbl_purchase_request.purchase_request_purpose,
	    fnGetAssignatoryName(purchase_request_requestedby_id) AS requested_by,
	    fnGetPurchaseNo(tbl_purchase_request.purchase_request_id) AS PONum
	
	    
	    FROM tbl_purchase_request
	    INNER JOIN fais.tbl_division
	    ON tbl_division.division_id = tbl_purchase_request.division_id
	    INNER JOIN fais.tbl_section
	    ON tbl_section.section_id = tbl_purchase_request.section_id
	    WHERE tbl_purchase_request.user_id = ".$id." OR purchase_request_requestedby_id = ".$id."
	    ORDER BY purchase_request_number DESC";
        }else{
            $sql = "SELECT tbl_purchase_request.purchase_request_id,
	    tbl_purchase_request.purchase_request_sai_number,
	    tbl_purchase_request.purchase_request_number,
	    tbl_division.name AS division_name,
	    tbl_section.name AS section_name,
	    tbl_purchase_request.purchase_request_purpose,
	    fnGetAssignatoryName(purchase_request_requestedby_id) AS requested_by,
	    fnGetPurchaseNo(tbl_purchase_request.purchase_request_id) AS PONum
	    FROM tbl_purchase_request
	    INNER JOIN fais.tbl_division
	    ON tbl_division.division_id = tbl_purchase_request.division_id
	    INNER JOIN fais.tbl_section
	    ON tbl_section.section_id = tbl_purchase_request.section_id
	    ORDER BY purchase_request_number DESC";
        }
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }*/
    
    public function actionDepartments()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * from tbl_department";
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }

    public function actionQuotationbidsandawards()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$Purchase = new Purchaserequest();
        $con = Yii::$app->procurementdb;
        $sql = "SELECT `tbl_purchase_request`.`purchase_request_id`,
	    `tbl_purchase_request`.`purchase_request_sai_number`,
	    `tbl_purchase_request`.`purchase_request_number`,
	    `tbl_division`.`name` AS division_name,
	    `tbl_section`.`name` AS section_name,
	    `tbl_purchase_request`.`purchase_request_purpose`
	    FROM `tbl_purchase_request`
	    INNER JOIN `fais`.`tbl_division`
	    ON `tbl_division`.`division_id` = `tbl_purchase_request`.`division_id`
	    INNER JOIN `fais`.`tbl_section`
	    ON `tbl_section`.`section_id` = `tbl_purchase_request`.`section_id`
	    ORDER BY purchase_request_number DESC";
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }

    public function actionPurchaseorderandobligation()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$Purchase = new Purchaserequest();
        $con = Yii::$app->procurementdb;
        $sql = "SELECT `tbl_purchase_request`.`purchase_request_id`,
	    `tbl_purchase_request`.`purchase_request_sai_number`,
	    `tbl_purchase_request`.`purchase_request_number`,
	    `tbl_division`.`name` AS division_name,
	    `tbl_section`.`name` AS section_name
	    FROM `tbl_purchase_request`
	    INNER JOIN `tbl_division`
	    ON `tbl_division`.`division_id` = `tbl_purchase_request`.`division_id`
	    INNER JOIN `tbl_section`
	    ON `tbl_section`.`section_id` = `tbl_purchase_request`.`section_id`
	    ORDER BY purchase_request_number DESC";
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }
    
    public function actionLineitembudget()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$Lineitembudget = new Lineitembudget();
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * from tbl_line_item_budget";
        $lib = $con->createCommand($sql)->queryAll();
        return $lib;
    }

    public function actionSupplierlist($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $mget = Yii::$app->request->get();
        $out = ['results' => ['id' => '', 'text' => '']];
        $pr_id = $mget["pid"];
        if (!is_null($q)) {
            $con =  Yii::$app->procurementdb;
            $command2 = $con->createCommand("CALL `fais-procurement`.`spGenerateSupplier`(".$pr_id.",'".$q."')");
            $data = $command2->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' =>Supplier::find()->where(['supplier_id'=>$id])->supplier_name];
        }
        return $out;
    }

    public function actionMymethod ($iXcoord , $iYcoord) {
        $iXcoord = 5;
        $iYcoord = 10;
        $x = $iXcoord + 10;
        $iYcoord = $x - 1 + $iYcoord + 10;
        return $x;
    }
    public function actionPurchaserequest2()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$con = Yii::$app->procurementdb;
        //$sql = "SELECT * FROM vw_purchase_request";
        //$porequest2 = $con->createCommand($sql)->queryAll();
        //return $porequest2;

        /*$purchaserequest = VwPurchaseRequest::find();
        $provider = new ActiveDataProvider([
            'query' =>  $purchaserequest,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'purchase_request_id' => SORT_DESC,
                ]
            ],
        ]);*/
        $searchModel = new PurchaserequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(isset($_GET['page'])){
            $model = $dataProvider->getModels();
            return $model;
        }
        //$dataProvider->pagination->page = 2;
        $model = $dataProvider->getModels();
        return $model;
    }
}


