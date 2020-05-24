<?
namespace app\controllers;
use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerJsFile(
    '@web/js/example-app.js', 
    ['depends' => ['yii\web\YiiAsset','yii\bootstrap\BootstrapPluginAsset']]
);

$this->registerCssFile(
    '@web/css/custom-styles.css',
    ['depends' => ['yii\web\YiiAsset','yii\bootstrap\BootstrapPluginAsset']]
);
?>
<?//= Html::tag('p', Html::encode($user->name), ['class' => 'username']) ?>
<?=Html::beginForm(['/example-app/reports'], 'post')?>
<table class="filter-table">
    <thead>
		<tr>
			<th scope="col"><label for="filter-date-from">Дата с</label></th>
            <th scope="col"><label for="filter-time-from">Время с</label></th>
            <th scope="col"><label for="filter-date-until">Дата по </label></th>
            <th scope="col"><label for="filter-time-until">Время по</label></th>
            <th scope="col"> <label for="filter-phone">Телефон пользователя</label></th>
            <th scope="col"><label for="filter-user-id">ID пользователя</label></th>
            <th scope="col">Действие</th>
            <th scope="col"></th>
		</tr>
	</thead>

    <tbody>
        <tr>
            <td><input type="date" class="form-control form-input" name="filter-date-from" value="<?=$data['filter-date-from'];?>"></td>
            <td><input type="time" class="form-control form-input" name="filter-time-from" value="<?=$data['filter-time-from'];?>"></td>
            <td><input type="date" class="form-control form-input" name="filter-date-until" value="<?=$data['filter-date-until'];?>"></td>
            <td><input type="time" class="form-control form-input" name="filter-time-until" value="<?=$data['filter-time-until'];?>"></td>
            <td><input type="phone" class="form-control form-input" name="filter-phone" value="<?=$data['filter-phone'];?>"></td>
            <td><input type="text" class="form-control form-input" name="filter-user-id" value="<?=$data['filter-user-id'];?>"></td>
            <td><button type="submit" class="btn btn-primary">Фильтр</button></td>
            <td><button type="button" id="btn-filter-clear" class="btn btn-secondary">Очистить</button></td>
        </tr>  
    </tbody>
    <input type="hidden" id="filter-send-type" class="form-control" name="send-type" value="filter">
</table> 
<?Html::endForm()?>

<table class="table">
	<thead>
		<tr>
			<th scope="col">Дата, время</th>
			<th scope="col">Пользователь</th>
			<th scope="col">Сумма</th>
            <th scope="col">Действие</th>
		</tr>
	</thead>
	<tbody>
        

    <?foreach($reportList as $arReport):?>
        <?$curUser=$usersList[$arReport['user_id']];?>
        <?$allSum+=(float)$arReport['sum'];?>
        <?$userDescribe = 
            '('.$curUser['id'].') '
            .$curUser['sec_name'].' '
            .$curUser['name'].' '
            .$curUser['patronim'].', тел: '
            .$curUser['phone'];
        ?>
 		<tr>
			<td><?=$arReport['timestamp'];?></td>
			<td><?=$userDescribe;?></td>
            <td><?=$arReport['sum'];?></td>
            <td><button type="button"
                class="btn btn-danger btn-del-report" 
                attr-report-id="<?=$arReport['id'];?>">
                Отменить зачисление
                </button>
            </td>
		</tr>
    <?endforeach;?>
        <tr>
            <td><b>ИТОГО</b></td>
            <td></td>
            <td><?=round($allSum,2);?></td>
            <td></td>
		</tr>

    </tbody>
</table>