<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Examplereport extends ActiveRecord
{
   public $add_sum;
   public $user;

   public function attributeLabels(){
      return[
         'add_sum' => 'Сумма'
      ];
   }


   public function rules(){
      return [
         [['add_sum','user'], 'required'],
         [['add_sum','user'], 'number'],
         ['add_sum', 'trim'],
      ];
   }

}
