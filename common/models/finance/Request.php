<?php

namespace common\models\finance;

use Yii;
use common\models\cashier\Creditor;
use common\models\finance\Requesttype;
/**
 * This is the model class for table "tbl_request".
 *
 * @property integer $request_id
 * @property string $request_number
 * @property string $request_date
 * @property integer $request_type_id
 * @property integer $payee_id
 * @property string $particulars
 * @property double $amount
 * @property integer $status_id
 * @property integer $created_by
 */
class Request extends \yii\db\ActiveRecord
{
    /***
        Sec. 36. Basic Requirements for Disbursements and the Required Certifications.
        Disbursements of government funds shall comply with the following basic requirements and certifications:
        
            a. Availability of allotment/budget for obligation/utilization certified by the Budget Officer/Head of Budget Unit;
            b. Obligations/Utilizations properly charged against available allotment/budget by the Chief Accountant/Head of Accounting Unit;
            c. Availability of funds certified by the Chief Accountant. The Head of the Accounting Unit shall certify the availability of funds before an Agency Head or his duly authorized representative enter into any contract that involves the expenditure of public funds based on the copy of budget release documents;
            d. Availability of cash certified by the Chief Accountant. The Head of the Accounting Unit shall certify the availability of cash and completeness of the supporting documents in the disbursement voucher and payroll based on the Registry of Allotments and Notice of Cash Allocation/Registry of Allotment and Notice of Transfer of Allocation;
            e. Legality of the transactions and conformity with existing rules and regulations. The requesting and approving officials shall ensure that the disbursements of government funds are legal and in conformity with applicable rules and regulations;
            f. Submission of proper evidence to establish validity of the claim. The Head of the Requesting Unit shall certify on the necessity and legality of charges to allotments under his/her supervision as well as the validity, propriety and legality of supporting documents. All payments of government obligations and payables shall be covered by Disbursement Vouchers (DV)/Payrolls together with the original copy of the supporting documents which will serve as basis in the evaluation of authenticity and authority of the claim. It should be cleared, however, that the submission of the supporting documents does not preclude reasonable questions on the funding, legality, regularity, necessity and/or economy of the expenditures or transactions; and
            g. Approval of the disbursement by the Head of Agency or by his duly authorized representative. Disbursement or disposition of government funds or property shall invariably bear the approval of the proper officials. The DVs/Payrolls shall be signed and approved by the head of the agencies or his duly authorized representatives.
    ***/
    
    // access-finance
    const STATUS_CREATED = 10;   // end user
    const STATUS_SUBMITTED = 20; // end user
    const STATUS_VERIFIED = 30;  // finance verification team
    const STATUS_VALIDATED = 40;  // Head of the Requesting Unit (ARD)
    const STATUS_CERTIFIED_ALLOTMENT_AVAILABLE = 50; // Head of Budget Unit (Budget Officer)
    const STATUS_ALLOTTED = 55; // finance processing team / budgetting staff
    const STATUS_CERTIFIED_FUNDS_AVAILABLE = 60; // Head of the Accounting Unit (Accountant)
    const STATUS_CHARGED = 65; // finance processing team / accounting staff
    const STATUS_APPROVED_FOR_DISBURSEMENT = 70;  // Head of Agency (Regional Director / OIC)
    const STATUS_COMPLETED = 80; // 
    const STATUS_RATED = 90;     // end user
    
    public $user_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_request';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('procurementdb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_number', 'request_date', 'division_id', 'request_type_id', 'obligation_type_id', 'payee_id', 'particulars', 'amount'], 'required'],
            [['request_date'], 'safe'],
            [['request_type_id', 'payee_id', 'status_id', 'created_by'], 'integer'],
            [['particulars'], 'string'],
            [['amount'], 'number'],
            [['request_number'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'request_id' => 'Request ID',
            'request_number' => 'Request Number',
            'request_date' => 'Request Date',
            'division_id' => 'Division',
            'request_type_id' => 'Request Type',
            'obligation_type_id' => 'Obligation Type',
            'project_id' => 'Project',
            'payee_id' => 'Payee',
            'particulars' => 'Particulars',
            'amount' => 'Amount',
            'status_id' => 'Status',
            'created_by' => 'Created By',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasMany(Requestattachment::className(), ['request_id' => 'request_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreditor()
    {
        return $this->hasOne(Creditor::className(), ['creditor_id' => 'payee_id']);
    }
    
    public function getRequesttype()
    {
        return $this->hasOne(Requesttype::className(), ['request_type_id' => 'request_type_id']);
    }
    
    static function generateRequestNumber($date = NULL, $count = NULL)
    {
        if(isset($date)){
            $year = date("Y", strtotime($date));
            $month = date("m", strtotime($date));
        }else{
            $year = date("Y", strtotime("now"));
            $month = date("m", strtotime("now"));
            $count = Request::find()->where(['YEAR(`request_date`)' => $year])->orderBy(['request_date' => SORT_DESC])->count();
            $count += 1;
        }
     
        
        return $year.'-'.$month.'-'.str_pad($count, 4, '0', STR_PAD_LEFT);
    }
    
    function owner()
    {
        $owner = ($this->created_by == Yii::$app->user->identity->user_id) ? true : false;
        return $owner;
    }
    
    public function getVerifiedAttachments()
    {
        $verified = false;
        foreach($this->attachments as $attachment)
        {
            $verified = $attachment->status_id ? true : false;
        }
        return $verified;
    }
    
    
}
