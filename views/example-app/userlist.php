<?
namespace app\controllers;
use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerJsFile(
   '@web/js/bootstrap4-toggle.js', 
   ['depends' => ['yii\web\YiiAsset','yii\bootstrap\BootstrapPluginAsset']]
);
$this->registerJsFile(
    '@web/js/example-app.js', 
    ['depends' => ['yii\web\YiiAsset','yii\bootstrap\BootstrapPluginAsset']]
);
$this->registerCssFile(
    '@web/css/bootstrap4-toggle.css',
    ['depends' => ['yii\web\YiiAsset','yii\bootstrap\BootstrapPluginAsset']]
);
?>


<table class="table">
	<thead>
		<tr>
			<th scope="col">ID</th>
			<th scope="col">Телефон</th>
			<th scope="col">ФИО</th>
            <th scope="col">Баланс</th>
            <th scope="col"></th>
			<th scope="col">Статус</th>
		</tr>
	</thead>
	<tbody>

    <?foreach($users as $arUser):?>
        <?$fullName = $arUser['sec_name'].' '.$arUser['name'].' '.$arUser['patronim'];?>
        <?if($arUser['status'] =='active') {
            $swtchStatus = 'checked ';
            $buttonVisibility = 'visible';
        } else{
            $swtchStatus = '';
            $buttonVisibility = 'hidden';
        }?>
		<tr>
			<th scope="row"><?=$arUser['id'];?></th>
			<td><?=$arUser['phone'];?></td>
			<td><?=$fullName;?></td>
            <td><?=$arUser['balance'];?></td>
            <td><button 
                class="btn btn-primary add-balance" 
                attr-user="<?=$arUser['id'];?>"
                style="visibility:<?=$buttonVisibility?>">
                Пополнить счет
                </button>
            </td>
            <td>
                <input type="checkbox" 
                <?=$swtchStatus;?>
                data-toggle="toggle" 
                data-width="110" 
                data-on="Активный" 
                data-off="Заблокирован" 
                data-onstyle="success" 
                data-size="sm"
                attr-user="<?=$arUser['id'];?>"
                >
            </td>
		</tr>
    <?endforeach;?>
    </tbody>
</table>
<div class="raw" style="text-align:center; margin-top:50px;">
    <button id="add-user" class="btn btn-primary">Добавить пользователя</button>
</div>

<!--MODAL WINDOWS-->
<div class="modal" tabindex="-1" role="dialog" id="add-user-window">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
		 <div class="modal-header">
			<h5 class="modal-title">Новый пользователь</h5>
			<button type="button" class="close add-window-close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
        </div>
        
        <?$form = ActiveForm::begin();?>
			<div class="modal-body">
                <div class="form-group">
					<label for="new_sec_name">Фамилия</label>
                      <?=$form->field($newUser, 'new_sec_name')->label(false);?>
				</div>
					
				<div class="form-group">
                    <label for="new_name">Имя</label>
                    <?=$form->field($newUser, 'new_name')->label(false);?>
				</div>
					
				<div class="form-group">
					<label for="new_patronim">Отчество</label>
                    <?=$form->field($newUser, 'new_patronim')->label(false);?>
				</div>
					
				<div class="form-group">
					<label for="new_phone">Телефон</label>
                    <?=$form->field($newUser, 'new_phone')->label(false);?>
                </div>
				<input type="hidden" name="send-type" value="add-user">

			</div>
			
			<div class="modal-footer">
				<button type="submit" id="add-user-save" class="btn btn-primary">Запомнить пользователя</button>
				<button type="button" class="btn btn-secondary add-window-close" data-dismiss="modal">Отмена</button>
			</div>
        <?php ActiveForm::end();?>
    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id="add-balance-window">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
		 <div class="modal-header">
			<h5 class="modal-title">Пополнить счет</h5>
			<button type="button" class="close add-window-close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		</div>
        <?$secForm = ActiveForm::begin();?>
			<div class="modal-body">
				<div class="form-group">
                    <label for="add_sum">Сумма</label>
                    <?=$secForm->field($addBalance, 'add_sum')->label(false);?>
                </div>
                <?=$secForm->field($addBalance, 'user')->hiddenInput()->label(false);?>
                <input type="hidden" name="send-type" value="add-balance">
			</div>
			
			<div class="modal-footer">
				<button type="submit" id="balance-window-save" class="btn btn-primary">Пополнить баланс</button>
				<button type="button" class="btn btn-secondary add-window-close" data-dismiss="modal">Отмена</button>
            </div>          
        <?ActiveForm::end();?>
    </div>
  </div>
</div>