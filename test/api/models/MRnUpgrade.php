<?php

/**
 * This is the model class for table "m_rn_upgrade".
 *
 * The followings are the available columns in table 'm_rn_upgrade':
 * @property string $cu_id
 * @property integer $update_time
 * @property string $update_user
 * @property string $cliname
 * @property string $org_id
 * @property string $create_user
 * @property integer $create_time
 * @property string $cu_url
 * @property string $cu_ver
 * @property integer $cu_type
 * @property string $cu_ver_type
 * @property string $cu_message
 * @property integer $cu_source
 * @property string $cu_market
 */
class MRnUpgrade extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'm_rn_upgrade';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cliname, cu_market', 'required'),
			array('update_time, create_time, cu_type, cu_source', 'numerical', 'integerOnly'=>true),
			array('update_user, org_id, create_user', 'length', 'max'=>19),
			array('cliname, cu_ver', 'length', 'max'=>40),
			array('cu_url', 'length', 'max'=>400),
			array('cu_ver_type', 'length', 'max'=>50),
			array('cu_message', 'length', 'max'=>4000),
			array('cu_market', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cu_id, update_time, update_user, cliname, org_id, create_user, create_time, cu_url, cu_ver, cu_type, cu_ver_type, cu_message, cu_source, cu_market', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cu_id' => 'Cu',
			'update_time' => 'Update Time',
			'update_user' => 'Update User',
			'cliname' => 'Cliname',
			'org_id' => 'Org',
			'create_user' => 'Create User',
			'create_time' => 'Create Time',
			'cu_url' => 'Cu Url',
			'cu_ver' => 'Cu Ver',
			'cu_type' => 'Cu Type',
			'cu_ver_type' => 'Cu Ver Type',
			'cu_message' => 'Cu Message',
			'cu_source' => 'Cu Source',
			'cu_market' => 'Cu Market',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cu_id',$this->cu_id,true);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('update_user',$this->update_user,true);
		$criteria->compare('cliname',$this->cliname,true);
		$criteria->compare('org_id',$this->org_id,true);
		$criteria->compare('create_user',$this->create_user,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('cu_url',$this->cu_url,true);
		$criteria->compare('cu_ver',$this->cu_ver,true);
		$criteria->compare('cu_type',$this->cu_type);
		$criteria->compare('cu_ver_type',$this->cu_ver_type,true);
		$criteria->compare('cu_message',$this->cu_message,true);
		$criteria->compare('cu_source',$this->cu_source);
		$criteria->compare('cu_market',$this->cu_market,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MRnUpgrade the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
