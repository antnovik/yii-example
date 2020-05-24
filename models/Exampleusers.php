<?php

namespace app\models;

use Yii;
//use yii\base\Model;
use yii\db\ActiveRecord;

class Exampleusers extends ActiveRecord
{
   //public $test;
   public $new_sec_name;
   public $new_name;
   public $new_patronim;
   public $new_phone;
   //public $new_balance;
   //public $set_status;
   
   //public $showFiles;
    //public $delFiles;
	//public $descrFiles;
   // public $requestType;

   public function attributeLabels(){
      return[
         'new_sec_name' => 'Фамилия',
         'new_name' => 'Имя',
         'new_patronim' => 'Отчество',
         'new_phone' => 'Телефон'
      ];
   }


   public function rules(){
      return [
         [['new_sec_name', 'new_name', 'new_patronim', 'new_phone'], 'required'],
         ['new_phone', 'number'],
         [['new_name', 'new_name', 'new_patronim'], 'string'],
         [['new_sec_name', 'new_name', 'new_patronim', 'new_phone'], 'trim'],
        // [['sec_name', 'name', 'patronim', 'phone',], 'required'],
      ];
   }

}
