<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "subject_groups".
 *
 * @property int $id
 * @property string $name ชื่อสาขาตามโครงสร้าง
 * @property int|null $status สถานะ (1=ใช้งาน, 0=ยกเลิก)
 *
 * @property Personnel[] $personnels
 */
class SubjectGroup extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%subject_groups}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ชื่อสาขาตามโครงสร้าง',
            'status' => 'สถานะ',
        ];
    }

    /**
     * Gets query for [[Personnels]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersonnels()
    {
        return $this->hasMany(Personnel::className(), ['subject_group_id' => 'id']);
    }
}
