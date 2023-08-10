<?php
$PACKAGE_MANAGERS = array(
	'apt' => array(
		'name' => 'APT',
		'platforms' => 'Debian, Ubuntu',
		'package_url' => 'https://packages.debian.org/search?searchon=names&keywords={}',
		'install_cmd' => 'sudo apt update && sudo apt install {}',
	),
	'aur' => array(
		'name' => 'AUR',
		'platforms' => 'ArchLinux',
		'package_url' => 'https://aur.archlinux.org/packages?O=0&K={}',
	),
	'f-droid' => array(
		'name' => 'F-Droid',
		'platforms' => 'Android',
		'package_url' => 'https://f-droid.org/en/packages/{}',
	),
	'firefox' => array(
		'name' => 'Mozilla Firefox',
		'package_url' => 'https://addons.mozilla.org/en-US/firefox/addon/{}',
	),
	'googleplay' => array(
		'name' => 'Google Play',
		'platforms' => 'Android',
		'package_url' => 'https://play.google.com/store/apps/details?id={}',
	),
	'pacman' => array(
		'name' => 'Pacman',
		'platforms' => 'ArchLinux',
		'package_url' => 'https://archlinux.org/packages/?q={}',
		'install_cmd' => 'sudo pacman -S {}',
	),
);
?>
