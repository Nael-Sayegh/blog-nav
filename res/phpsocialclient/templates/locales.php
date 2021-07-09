<?php
/* Copyright (c) 2017 Pascal Engélibert
This file is part of PHPSocialClient.
PHPSocialClient is free software: you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
PHPSocialClient is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.
You should have received a copy of the GNU Lesser General Public License along with PHPSocialClient. If not, see <http://www.gnu.org/licenses/>.
*/

if(isset($lang) and file_exists($_SERVER['DOCUMENT_ROOT'].'/phpsocialclient/locales/'.$lang.'.php')) {}
else $lang = 'en';
require('locales/'.$lang.'.php');
?>
