<?php

namespace frontend\modules\procurement\controllers;

use Yii;
use common\models\procurement\Votes;
use common\models\procurement\Registration;
use common\models\procurement\RegistrationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\filters\VerbFilter;

use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * RegistrationController implements the CRUD actions for Registration model.
 */
class RegistrationController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Registration models.
     * @return mixed
     */
    /*public function actionIndex()
    {
        $searchModel = new RegistrationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }*/
    
    /**
     * Lists all Registration models.
     * @return mixed
     */
    public function actionCheckin($id=NULL, $gender=NULL)
    {
        if($id)
        {
            $model = Registration::find()->where(['id' => $id])->one();
            $model->checked_in = 1;
            $model->save();
        }
        
        $searchModel = new RegistrationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('checkin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionGender($id=NULL, $gender=NULL)
    {
        if($gender)
        {
            $model = Registration::find()->where(['id' => $id])->one();
            $model->gender = $gender;
            $model->save();
        }
        
        $searchModel = new RegistrationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('checkin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionSummary()
    {
        $maleCheckin = Registration::find()
            ->where(['gender' => 1, 'checked_in' => 1])
            ->all();
        
        $femaleCheckin = Registration::find()
            ->where(['gender' => 2, 'checked_in' => 1])
            ->all();
        
        $present = Registration::find()
            ->where(['checked_in' => 1])
            ->all();
        
        $absent = Registration::find()
            ->where(['checked_in' => 0])
            ->all();
        
        return $this->render('summary', [
            'maleCheckin' => $maleCheckin,
            'femaleCheckin' => $femaleCheckin,
            'present' => $present,
            'absent' => $absent,
        ]);
    }
    
    public function actionVotes()
    {
        //$model = Votes::find()->all();
        
        $query = Votes::find()
                ->orderBy([
                    'votes'=> SORT_DESC,
                ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'pagination' => false,
        ]);
        
        
        //$dataProvider = new ActiveDataProvider($model);
        
        return $this->render('votes', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRaffle($id=NULL)
    {
        //$searchModel = new RegistrationSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $winners = Registration::find()
            ->where(['winner' => 1])
            //->orderBy(new Expression('rand()'))
            //->limit(4)
            ->all();
        //$count = count($winners);
        
        return $this->render('raffle', [
            //'searchModel' => $searchModel,
            //'dataProvider' => $dataProvider,
            //'count' => $count,
            'winners' => $winners
        ]);
    }
    
    public function actionDrawnames()
    {
        $this->selectWinners();
        
        $winners = Registration::find()
            ->where(['winner' => 1])
            ->all();
        
        //$count = count($winners);
        
        return $this->render('raffle', [
            //'searchModel' => $searchModel,
            //'dataProvider' => $dataProvider,
            //'count' => $count,
            'winners' => $winners
        ]);
        //echo Json::encode($winners);
        //$this->renderPartial('_ajaxContent', $data, false, true);
    }
    
    public function actionClearnames()
    {
        $this->clearWinners();
        
        $winners = Registration::find()
            ->where(['winner' => 1])
            ->all();
        
        $count = count($winners);
        
        return $this->render('raffle', [
            //'searchModel' => $searchModel,
            //'dataProvider' => $dataProvider,
            'count' => $count,
            'winners' => $winners
        ]);
    }
    
    public function actionReplacename($id)
    {
        //$this->clearWinners();
        
        $model = Registration::find()->where(['id' => $id])->one();
        $model->winner = 0;
        $model->save();
        
        $this->selectWinners();
        
        $winners = Registration::find()
            ->where(['winner' => 1])
            ->all();
        
        $count = count($winners);
        
        return $this->render('raffle', [
            //'searchModel' => $searchModel,
            //'dataProvider' => $dataProvider,
            'count' => $count,
            'winners' => $winners
        ]);
    }
    
    private function selectWinners()
    {
        $count = Registration::find()
            ->where(['winner' => 1])
            ->count();
        
        $selected = Registration::find()
            ->where(['gender' => 1, 'checked_in' => 1, 'winner' => 0])
            ->orderBy(new Expression('rand()'))
            ->limit(16 - $count)
            ->all();
        
        foreach($selected as $winner)
        {
            $model = Registration::find()->where(['id' => $winner->id])->one();
            $model->winner = 1;
            $model->save();
        }
    }
    
    private function clearWinners()
    {
        $count = Registration::find()
            ->where(['winner' => 1])
            ->count();
        
        $selected = Registration::find()
            ->where(['winner' => 1])
            //->orderBy(new Expression('rand()'))
            //->limit(16 - $count)
            ->all();
        
        foreach($selected as $winner)
        {
            $model = Registration::find()->where(['id' => $winner->id])->one();
            $model->winner = 0;
            $model->save();
        }
    }
    /**
     * Displays a single Registration model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Registration model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Registration();
        
        $checkin = ArrayHelper::map([['type_id'=> 1, 'name'=>'Yes'], ['type_id'=> 1, 'name'=>'Yes']], 'type_id', 'name');
        $genderTypes = ArrayHelper::map([['type_id'=> 1, 'name'=>'Male'], ['type_id'=> 2, 'name'=>'Female']], 'type_id', 'name');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'genderTypes' => $genderTypes,
                'checkin' => $checkin,
            ]);
        }
    }

    /**
     * Updates an existing Registration model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Registration model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Registration model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Registration the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Registration::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
