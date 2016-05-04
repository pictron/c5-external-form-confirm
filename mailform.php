<?php
defined('C5_EXECUTE') or die("Access Denied.");
use Concrete\Core\Validation\CSRF\Token;
$form = Core::make('helper/form');
$val = Loader::helper('validation/form');
$token = Loader::helper('validation/token');
$crsftag = $token->generate($bid.'ask');
?>
<?php if ($section == 'edit') { ?>
<h2>お問い合わせ</h2>
<form method="post" action="<?php echo $view->action('confirm')?>">
	<div class="form-group">
	<?php echo $form->label('name', '名前'); ?>
	<?php echo $form->text('name',$input['name'])?>
	<?php echo(isset($errors['name']) ? '<font color="#ff0000">'.$errors['name'].'</font>' : ''); ?>
	</div>
	<div class="form-group">
	<?php echo $form->label('email', 'メールアドレス'); ?>
	<?php echo $form->text('email',$input['email'])?>
	<?php echo(isset($errors['email']) ? '<font color="#ff0000">'.$errors['email'].'</font>' : ''); ?>
	</div>
	<div class="form-group">
	<?php echo $form->label('message', 'メッセージ'); ?>
	<?php echo $form->textarea('message',$input['message'])?>
	<?php echo(isset($errors['message']) ? '<font color="#ff0000">'.$errors['message'].'</font>' : ''); ?>
	</div>
	<?php echo $form->hidden('ccm_token',$crsftag);?>
	<?php echo $form->submit('submit','確認')?>
</form>
<?php } ?>
<?php if ($section == 'confirm'){ ?>
<h2>お問い合わせ：確認</h2>
<table class="table">
	<tr>
		<td>名前</td>
		<td><?php echo $input['name']; ?></td>
	</tr>
	<tr>
		<td>メールアドレス</td>
		<td><?php echo $input['email']; ?></td>
	</tr>
	<tr>
		<td>メッセージ</td>
		<td><?php echo $input['message']; ?></td>
	</tr>
</table>
<form method="post" id="form_confirm" action="<?php echo $view->action('send')?>">
<div class="form-group">
    <?php echo $form->hidden('name')?>
    <?php echo $form->hidden('email')?>
    <?php echo $form->hidden('message')?>
    <?php echo $form->hidden('ccm_token',$crsftag);?>
    <?php echo $form->submit('submit','送信')?>
    <a href="#" onclick="document.form_back.submit()" class="btn btn-default">戻る</a>
</div>
</form>
<form method="post" id="form_back" name="form_back" action="<?php echo $view->action('back')?>">
	<?php echo $form->hidden('name')?>
    <?php echo $form->hidden('email')?>
    <?php echo $form->hidden('message')?>
    <?php echo $form->hidden('ccm_token',$crsftag);?>
</form>
<?php } ?>
<?php if ($section == 'complete'){ ?>
<h2>お問い合わせ：完了</h2>
<p>お問い合わせありがとうございました。</p>
<?php } ?>