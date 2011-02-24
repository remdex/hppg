<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Logged user');?> - <? echo $user->username?></h1>
</div>

<div class="articlebody">

<? if (isset($errArr)) : ?>
    <? foreach ((array)$errArr as $error) : ?>
    	<div class="error">*&nbsp;<?=$error;?></div>
    <? endforeach; ?>
<? endif;?>

<? if (isset($account_updated) && $account_updated == 'done') : ?>
	<div class="dataupdate"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Account updated');?></div>
<? endif; ?>


<div class="explain"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Do not enter password unless you want to change it');?></div>
	<div><br />
		<form action="<?=erLhcoreClassDesign::baseurl('user/account')?>" method="post">
			<table>
				<tr><td colspan="2"><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Login information')?></strong></td></tr>
				<tr>
					<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Username');?></td><td><input class="inputfield" type="text" name="Username" value="<?=htmlspecialchars($user->username);?>" /></td>
				</tr>
				<tr>
					<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Password');?></td>
					<td><input type="password" class="inputfield" name="Password" value=""/></td>
				</tr>
				<tr>
					<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Repeat password');?></td>
					<td><input type="password" class="inputfield" name="Password1" value=""/></td>
				</tr>
				<tr><td colspan="2"><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Contact information');?></strong></td></tr>
				<tr>
					<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','E-mail');?></td>
					<td><input type="text" class="inputfield" name="Email" value="<?=$user->email;?>"/></td>
				</tr>									
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" class="default-button" name="Update_account" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Update');?>"/></td>
				</tr>
			</table>		
		</form>
	</div>
</div>
<br />


<div class="open-id-item">
<h2>Open ID - Google authentification</h2>
<?php $list = erLhcoreClassModelOidMap::getList(array('filter' => array('user_id' => $user->id,'open_id_type' => erLhcoreClassModelOidMap::OPEN_ID_GOOGLE)));
if (count($list) > 0) : ?>
<ul>
<?php foreach ($list as $open_id) : ?>
<li><img src="<?=erLhcoreClassDesign::design('images/icons/open_id_1.png')?>" alt="" title="<?=htmlspecialchars($open_id->open_id);?>" />
<span title="<?=htmlspecialchars($open_id->open_id);?>">(<?=$open_id->email?>)</span> <a href="<?=erLhcoreClassDesign::baseurl('user/removeopenid')?>/<?=$open_id->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myfavorites','Remove Open ID');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myfavorites','Remove Open ID');?>"></a>
</li>
<?php endforeach; ?>
</ul>
<?php else :?>
<br />
<a href="<?=erLhcoreClassDesign::baseurl('user/loginwithgoogle')?>">Add google account &raquo;</a>
<?php endif;?>
</div>