<div id="loginbox">
<?php include_once('user_rank.php');
if(isset($logged) and $logged) {
	$req = $bdd->prepare('SELECT `id` FROM `notifs` WHERE `account`=? AND `unread`=1');
	$req->execute(array($login['id']));
	$n_notifs = 0;
	while($req->fetch()) {$n_notifs ++;} ?>
	<div id="boutonjs2" style="display:none;">
		<button type="button" id="menu_user_popup" onclick="rdisp('menu_user','menu_user_popup')" aria-haspopup="true" aria-expanded="false"><?php echo ($login['rank']=='a')? $nom : htmlentities($login['username']); $date=getdate(); if(isset($settings['bd_m']) and isset($settings['bd_d']) and (($settings['bd_m']==$date['mon'] and $settings['bd_d']==$date['mday']) or ($settings['bd_m']==2 and $date['mon']==3 and $settings['bd_d']==29 and $date['mday']==1 and $date['year']%4==0))) echo ' &#127874;'; ?></button>
		<div id="menu_user" style="display: none;" role="menu" aria-label="<?php echo tr($tr0,'loginbox_arialab_menu'); ?>">
		<?php if($login['rank'] == 'a') {
$req = $bdd->prepare('SELECT `works` FROM `team` WHERE `account_id`=? LIMIT 1');
				$req->execute(array($login['id']));
				if($data = $req->fetch()) {
					$worksnum1 = $data['works'];
				}
				if(!strstr($_SERVER['PHP_SELF'], '/admin/accueil.php') && $worksnum1 == '1' or $worksnum1 == '2') { ?>
<a role="menuitem" class="hlink" href="/admin"><?php echo tr($tr0,'loginbox_adminlink').' ('.$nomdusite.')'; ?></a><br />
				<?php }
				if($worksnum1 == '0' or $worksnum1 == '2') { ?>
					<a role="menuitem" class="hlink" href="https://www.nvda-fr.org/admin?cid=<?php print $_COOKIE['connectid']; ?>&ses=<?php print $_COOKIE['session']; ?>"><?php echo tr($tr0,'loginbox_adminlink').' (NVDA-FR)'; ?></a><br />
				<?php } ?>
				<a role="menuitem" class="hlink" href="/alist.php"><?php echo tr($tr0,'loginbox_alistlink'); ?></a><br />
				<?php } ?>
			<a role="menuitem" class="hlink" href="/home.php"><?php echo tr($tr0,'loginbox_profilelink'); ?></a><br />
			<?php echo '<a role="menuitem" href="/home.php#notifs">'.($n_notifs>0? '<strong>'.tr($tr0,'loginbox_notifs_'.($n_notifs>1?'pl':'sg'), array('n'=>$n_notifs)).'</strong>':tr($tr0,'loginbox_notifs')).'</a><br />'; ?>
						<a role="menuitem" class="hlink" href="/logout.php?token=<?php echo $login['token']; ?>"><?php echo tr($tr0,'loginbox_logoutlink').' ('; if($login['rank'] == 'a') {echo $nom.')'; } else { echo htmlentities($login['username']).')'; } ?></a>
		</div>
	</div>
	<script>document.getElementById("boutonjs2").style.display="block";</script>
	<noscript>
		<details>
			<summary><?php echo ($login['rank']=='a')? $nom : $login['username']; $date=getdate(); if(isset($settings['bd_m']) and isset($settings['bd_d']) and (($settings['bd_m']==$date['mon'] and $settings['bd_d']==$date['mday']) or ($settings['bd_m']==2 and $date['mon']==3 and $settings['bd_d']==29 and $date['mday']==1 and $date['year']%4==0))) echo ' &#127874;'; ?></summary>
			<div id="menu_user2" style="display: block;" role="menu" aria-label="<?php echo tr($tr0,'loginbox_arialab_menu'); ?>">
		<?php if($login['rank'] == 'a') {
$req = $bdd->prepare('SELECT `works` FROM `team` WHERE `account_id`=? LIMIT 1');
				$req->execute(array($login['id']));
				if($data = $req->fetch()) {
					$worksnum1 = $data['works'];
				}
				if(!strstr($_SERVER['PHP_SELF'], '/admin/accueil.php') && $worksnum1 == '1' or $worksnum1 == '2') { ?>
<a role="menuitem" class="hlink" href="/admin"><?php echo tr($tr0,'loginbox_adminlink').' ('.$nomdusite.')'; ?></a><br />
				<?php }
				if($worksnum1 == '0' or $worksnum1 == '2') { ?>
					<a role="menuitem" class="hlink" href="https://www.nvda-fr.org/admin?cid=<?php print $_COOKIE['connectid']; ?>&ses=<?php print $_COOKIE['session']; ?>"><?php echo tr($tr0,'loginbox_adminlink').' (NVDA-FR)'; ?></a><br />
				<?php } ?>
				<a role="menuitem" class="hlink" href="/alist.php"><?php echo tr($tr0,'loginbox_alistlink'); ?></a><br />
				<?php } ?>
				<a role="menuitem" class="hlink" href="/home.php"><?php echo tr($tr0,'loginbox_profilelink'); ?></a><br />
				<?php 	echo '<a role="menuitem" href="/home.php#notifs">'.($n_notifs>0? '<strong>'.tr($tr0,'loginbox_notifs_'.($n_notifs>1?'pl':'sg'), array('n'=>$n_notifs)).'</strong>':tr($tr0,'loginbox_notifs')).'</a><br />'; ?>
				<a role="menuitem" class="hlink" href="/logout.php?token=<?php echo $login['token']; ?>"><?php echo tr($tr0,'loginbox_logoutlink').' ('; if($login['rank'] == 'a') {echo $nom.')'; } else { echo $login['username'].')'; } ?></a>
			</div>
		</details>
	</noscript>
		<?php } else { ?>
<div id="boutonjs205" style="display:none;">
	<button type="button" id="loginbox_form_popup" onclick="rdisp('loginbox_form','loginbox_form_popup')" aria-haspopup="true" aria-expanded="false"><?php echo tr($tr0,'loginbox_memberarea'); ?></button>
	<form id="loginbox_form" action="/login.php?a=form" method="post" aria-label="<?php echo tr($tr0,'loginbox_loginlabel'); ?>" style="display: none;">
		<label for="login_username" style="position:absolute; top:-999px; left:-9999px;"><?php echo tr($tr0,'loginbox_username'); ?></label>
		<input type="text" id="login_username" name="username" placeholder="<?php echo tr($tr0,'loginbox_username'); ?>" maxlength="32" aria-label="<?php echo tr($tr0,'loginbox_username'); ?>" /><br />
		<label for="login_psw" style="position:absolute; top:-999px; left:-9999px;"><?php echo tr($tr0,'loginbox_password'); ?></label>
		<input type="password" id="login_psw" name="psw" placeholder="<?php echo tr($tr0,'loginbox_password'); ?>" maxlength="64" aria-label="<?php echo tr($tr0,'loginbox_password'); ?>" /><br />
		<input type="submit" id="login_submit" value="<?php echo tr($tr0,'loginbox_loginlabel'); ?>" />
		<a id="login_mdp" class="hlink" href="/mdp_demande.php"><?php echo tr($tr0,'loginbox_forgotpsw'); ?></a>
		<a id="login_signup" class="hlink" href="/signup.php"><?php echo tr($tr0,'loginbox_signup'); ?></a>
	</form>
</div>
<script>document.getElementById("boutonjs205").style.display="block";</script>
<noscript>
	<details>
		<summary><?php echo tr($tr0,'loginbox_memberarea'); ?></summary>
		<form id="loginbox_form2" action="/login.php?a=form" method="post" aria-label="<?php echo tr($tr0,'loginbox_loginlabel'); ?>" style="display: block;">
			<label for="login_username2" style="position:absolute; top:-999px; left:-9999px;"><?php echo tr($tr0,'loginbox_username'); ?></label>
			<input type="text" id="login_username2" name="username" placeholder="<?php echo tr($tr0,'loginbox_username'); ?>" maxlength="32" aria-label="<?php echo tr($tr0,'loginbox_username'); ?>" /><br />
			<label for="login_psw2" style="position:absolute; top:-999px; left:-9999px;"><?php echo tr($tr0,'loginbox_password'); ?></label>
			<input type="password" id="login_psw2" name="psw" placeholder="<?php echo tr($tr0,'loginbox_password'); ?>" maxlength="64" aria-label="<?php echo tr($tr0,'loginbox_password'); ?>" /><br />
			<input type="submit" id="login_submit2" value="<?php echo tr($tr0,'loginbox_loginlabel'); ?>" />
			<a id="login_mdp2" class="hlink" href="/mdp_demande.php"><?php echo tr($tr0,'loginbox_forgotpsw'); ?></a>
			<a id="login_signup2" class="hlink" href="/signup.php"><?php echo tr($tr0,'loginbox_signup'); ?></a>
		</form>
	</details>
</noscript>
<!-- <span id="loginbox_link"><a class="hlink" href="/login.php">S'identifier</a> - <a class="hlink" href="/signup.php">S'inscrire</a></span> -->
<?php } ?>
</div>
