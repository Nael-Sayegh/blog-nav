<?php
function urank($rank, $user='', $er=true) {
	global $tr0;
	$s = '';
	if($user == '') {
		switch($rank) {
			case '0': return '<span class="rk rk1 rk_0">'.tr($tr0,'urank_0').'</span>';break;
			case '1': return '<span class="rk rk1 rk_1">'.tr($tr0,'urank_1').'</span>';break;
			case 'a': return '<span class="rk rk1 rk_a">'.tr($tr0,'urank_a').'</span>';break;
			case 'm': return '<span class="rk rk1 rk_m">'.tr($tr0,'urank_m').'</span>';break;
			case 'i': return '<span class="rk rk1 rk_i">'.tr($tr0,'urank_i').'</span>';break;
			case 'b': return '<span class="rk rk1 rk_b">'.tr($tr0,'urank_b').'</span>';break;
			case 'r': return '<span class="rk rk1 rk_r">'.tr($tr0,'urank_r').'</span>';break;
			case 'r1': return '<span class="rk rk1 rk_s">'.tr($tr0,'urank_s').'</span>';break;
			case 't': return '<span class="rk rk1 rk_t">'.tr($tr0,'urank_t').'</span>';break;
		}
	} else {
		switch($rank) {
			case '0': if($er)$s='<span class="rk2r">&#x20;('.tr($tr0,'urank_0').')</span>';return '<span class="rk rk2 rk_0" title="'.tr($tr0,'urank_0').'">'.$user.'</span>'.$s;break;
			case '1': if($er)$s='<span class="rk2r">&#x20;('.tr($tr0,'urank_1').')</span>';return '<span class="rk rk2 rk_1" title="'.tr($tr0,'urank_1').'">'.$user.'</span>'.$s;break;
			case 'a': if($er)$s='<span class="rk2r">&#x20;('.tr($tr0,'urank_a').')</span>';return '<span class="rk rk2 rk_a" title="'.tr($tr0,'urank_a').'">'.$user.'</span>'.$s;break;
			case 'm': if($er)$s='<span class="rk2r">&#x20;('.tr($tr0,'urank_m').')</span>';return '<span class="rk rk2 rk_m" title="'.tr($tr0,'urank_m').'">'.$user.'</span>'.$s;break;
			case 'i': if($er)$s='<span class="rk2r">&#x20;('.tr($tr0,'urank_i').')</span>';return '<span class="rk rk2 rk_i" title="'.tr($tr0,'urank_i').'">'.$user.'</span>'.$s;break;
			case 'b': if($er)$s='<span class="rk2r">&#x20;('.tr($tr0,'urank_b').')</span>';return '<span class="rk rk2 rk_b" title="'.tr($tr0,'urank_b').'">'.$user.'</span>'.$s;break;
			case 'r': if($er)$s='<span class="rk2r">&#x20;('.tr($tr0,'urank_r').')</span>';return '<span class="rk rk2 rk_r" title="'.tr($tr0,'urank_r').'">'.$user.'</span>'.$s;break;
			case 's': if($er)$s='<span class="rk2r">&#x20;('.tr($tr0,'urank_s').')</span>';return '<span class="rk rk2 rk_s" title="'.tr($tr0,'urank_s').'">'.$user.'</span>'.$s;break;
			case 't': if($er)$s='<span class="rk2r">&#x20;('.tr($tr0,'urank_t').')</span>';return '<span class="rk rk2 rk_t" title="'.tr($tr0,'urank_t').'">'.$user.'</span>'.$s;break;
		}
	}
	return '';
}
?>