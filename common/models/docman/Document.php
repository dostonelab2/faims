<?php

namespace common\models\docman;

use Yii;

/**
 * This is the model class for table "tbl_document".
 *
 * @property integer $document_id
 * @property string $subject
 * @property string $filename
 * @property string $document_code
 * @property integer $category_id
 * @property integer $functional_unit_id
 * @property string $content
 * @property integer $revision_number
 * @property string $effectivity_date
 * @property integer $user_id
 * @property integer $active
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_document';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dmsdb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject', 'filename', 'document_code', 'category_id', 'functional_unit_id', 'content', 'revision_number', 'effectivity_date', 'user_id'], 'required'],
            [['category_id', 'functional_unit_id', 'revision_number', 'user_id', 'active'], 'integer'],
            [['content'], 'string'],
            [['effectivity_date'], 'safe'],
            [['subject'], 'string', 'max' => 200],
            [['filename'], 'string', 'max' => 100],
            [['document_code'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'document_id' => 'Document ID',
            'subject' => 'Subject',
            'filename' => 'Filename',
            'document_code' => 'Document Code',
            'category_id' => 'Category ID',
            'functional_unit_id' => 'Functional Unit ID',
            'content' => 'Content',
            'revision_number' => 'Revision Number',
            'effectivity_date' => 'Effectivity Date',
            'user_id' => 'User ID',
            'active' => 'Active',
        ];
    }
}
