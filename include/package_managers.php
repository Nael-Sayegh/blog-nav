<?php
$PACKAGE_MANAGERS = array(
	'apk' => array(
		'name' => 'APK',
		'platforms' => 'Alpine',
		'package_url' => 'https://pkgs.alpinelinux.org/packages?name={}&branch=edge&repo=&arch=&maintainer=',
		'install_cmd' => 'sudo apk update && sudo apk add {}',
	),
	'apt' => array(
		'name' => 'APT',
		'platforms' => 'Debian',
		'package_url' => 'https://packages.debian.org/search?searchon=names&keywords={}',
		'install_cmd' => 'sudo apt update && sudo apt install {}',
	),
	'aur' => array(
		'name' => 'AUR',
		'platforms' => 'ArchLinux',
		'package_url' => 'https://aur.archlinux.org/packages?O=0&K={}',
	),
	'chocolatey' => array(
		'name' => 'Chocolatey',
		'platforms' => 'Windows',
		'package_url' => 'https://community.chocolatey.org/packages/{}',
		'install_cmd' => 'choco install {}',
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
	'guix' => array(
		'name' => 'Guix',
		'platforms' => 'Guix',
		'package_url' => 'https://packages.guix.gnu.org/search/?query={}',
		'install_cmd' => 'guix install {}',
	),
	'nix' => array(
		'name' => 'Nix',
		'platforms' => 'NixOS',
		'package_url' => 'https://search.nixos.org/packages?from=0&size=50&sort=relevance&type=packages&query={}',
		'install_cmd' => 'nix-shell -p {}',
	),
	'pacman' => array(
		'name' => 'Pacman',
		'platforms' => 'ArchLinux',
		'package_url' => 'https://archlinux.org/packages/?q={}',
		'install_cmd' => 'sudo pacman -S {}',
	),
	'pamac' => array(
		'name' => 'Pamac',
		'platforms' => 'Manjaro',
		'package_url' => 'https://software.manjaro.org/package/{}',
		'install_cmd' => 'sudo pamac install {}',
	),
	'snap' => array(
		'name' => 'Snap',
		'platforms' => 'GNU/Linux',
		'package_url' => 'https://snapcraft.io/{}',
		'install_cmd' => 'sudo snap install {}',
	),
);
$PLATFORMS = array(
	'Android',
	'Linux',
	'Windows',
);
$ARCHS = array(
	'x86' => 'x86',
	'x86_64' => 'x86_64',
);
?>
