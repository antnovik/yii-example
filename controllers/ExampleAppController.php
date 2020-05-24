<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;
use app\models\Exampleusers;
use app\models\Examplereport;

class ExampleAppController extends Controller {
    
    //определение шаблона
    public $layout = 'example-app';

    //страница списка пользователей
    public function actionUserlist(){
        $obNewUser = new Exampleusers();
        $obAddBalance = new Examplereport();

        //обработка запроса на добавление пользователя
        if(Yii::$app->request->isPost && $_POST['send-type']=='add-user'){
            if($obNewUser->load(Yii::$app->request->post()) && $obNewUser->validate()){
                $obNewUser->name = $obNewUser->new_name;
                $obNewUser->sec_name = $obNewUser->new_sec_name;
                $obNewUser->patronim = $obNewUser->new_patronim;
                $obNewUser->phone = $obNewUser->new_phone;
                $obNewUser->status = 'active';
                $data = $obNewUser;
                if($obNewUser->save()){
                    $this->refresh();
                }else Yii::$app->session->setFlash('error', 'Ошибка записи');
            }
        }

        //обработка запроса на зачисление суммы на счет
        if(Yii::$app->request->isPost && $_POST['send-type']=='add-balance'){
            if($obAddBalance->load(Yii::$app->request->post()) && $obAddBalance->validate()){
                $user = Exampleusers::findOne($obAddBalance->user);
                $addSum = round((float)$obAddBalance->add_sum, 2);

                $user->balance = round($user->balance + $addSum,2);

                $obAddBalance->user_id = $user->id;
                $obAddBalance->sum = $addSum;
                $obAddBalance->timestampunix = time();
                if($obAddBalance->save() && $user->update(true, ['balance'])){
                    $this->refresh();
                }else Yii::$app->session->setFlash('error', 'Ошибка записи');
             }
        }

        //Получение списка пользвателей, отсортированого по id
        $arUsers = Exampleusers::find()->orderBy(['id' => SORT_ACS])->asArray()->all();
        return	$this->render('userlist', ['users'=> $arUsers, 'newUser' => $obNewUser, 'addBalance' => $obAddBalance]);
    }



    //страница списка зачислений на счет
    public function actionReports(){
        
        //функция выборки пользователей из базы
        function getUsersByReports($arReports){
            $usersIdList = array();
            foreach($arReports as $report) $usersIdList[] = $report['user_id'];
            $usersIdList = array_unique($usersIdList);
            $arUsers = array();
            foreach (Exampleusers::find()->where(['id'=>$usersIdList])->asArray()->all() as $user) $arUsers[$user['id']] = $user;
            return $arUsers;
        }
            
        if(Yii::$app->request->isPost){
            //Фильтрация записей
            if($_POST['send-type']=='filter'){
                 $reguest = Examplereport::find()->orderBy(['timestamp' => SORT_DESC]);

                //Запрос на фильтрацию по id пользователя
                if(!empty($_POST['filter-user-id']))  $reguest->orWhere(['=', 'user_id', (int)$_POST['filter-user-id']]);

                //Запрос на фильтрацию по телефону пользователя
                if(!empty($_POST['filter-phone'])) {
                    $getUsers = Exampleusers::find()->select('id')->where(['phone' => (string)(int)$_POST['filter-phone']])->asArray()->all();
                    $usersIdList = array();
                    foreach($getUsers as $userID) $usersIdList[] = $userID['id'];
                    $reguest->orWhere(['user_id'=>$usersIdList]);
                }

                //Запрос на фильтрацию по дате-времени с
                if(!empty($_POST['filter-date-from']) || !empty($_POST['filter-time-from'])) {
                    if(!empty($_POST['filter-date-from'])){
                        $datetime = $_POST['filter-date-from'].' '.$_POST['filter-time-from'];
                        
                    }else{
                        $datetime = date('Y-m-d').' '.$_POST['filter-time-from'];
                    }
                    $reguest->andWhere(['>=','timestamp', $datetime]);
                }
                
                 //Запрос на фильтрацию по дате-времени по
                if(!empty($_POST['filter-date-until']) || !empty($_POST['filter-time-until'])) {
                   if(!empty($_POST['filter-date-until'])){
                       $datetime = $_POST['filter-date-until'].' '.$_POST['filter-time-until'];
                        
                   }else{
                        $datetime = date('Y-m-d').' '.$_POST['filter-time-until'];
                    }
                    $reguest->andWhere(['<=','timestamp', $datetime]);
                }
                $arAddSum = $reguest->asArray()->all();

            //Очистка фильтра
            //приходит $_POST['send-type']=='filter-clear'
            }else{
                $arAddSum = Examplereport::find()->orderBy(['timestamp' =>  SORT_DESC])->asArray()->all();
                $this->refresh();
            }
        }else $arAddSum = Examplereport::find()->orderBy(['timestamp' =>  SORT_DESC])->asArray()->all();
  

        $arUsers = getUsersByReports($arAddSum);
        return	$this->render('reports', ['reportList'=>$arAddSum, 'usersList'=>$arUsers, 'data'=>$_POST]);
    }

    //обработка ajax запроса на изменение статуса
    public function actionChangestatus(){
        if(Yii::$app->request->isAjax && $_POST['send_type']=='change-status'){
            $user = Exampleusers::findOne((int)$_POST['user_id']);
            $user->status = $_POST['new_status'];
            return $user->update(true, ['status']);
        }
     }

     //обработка ajax запроса на отмену зачисления
    public function actionA(){
        if(Yii::$app->request->isAjax && $_POST['send_type']=='del-report'){
            $report = Examplereport::findOne((int)$_POST['report_id']);
            $user = Exampleusers::findOne($report->user_id);
            $user->balance = round($user->balance - $report->sum,2);
            return  ($user->update(true, ['balance'])  &&  $report->delete());
        }
     }
}