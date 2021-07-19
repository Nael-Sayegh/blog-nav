# ProgAccess

This is the source code of [the ProgAccess website](https://progaccess.net).

This repository exists for simplifying both development, contribution and source code publication.

This repository does not contain the editorial content of ProgAccess. Articles and files are distributed via the website and [the API](https://progaccess.net/api). Articles are under CC BY-SA 4.0.

## Support

## Contribution

MR and issues welcome!

If you want to help (report bugs, translate...) but don't know how to use Git or GitLab, just send a message via [the contact form](https://progaccess.net/contacter.php).

In case you've found a security issue, please also use the contact form instead of GitLab.

## Installation

ProgAccess is not designed to run on any other instance than the official ones (dev and prod). Support is not guaranteed for any other instance. **The following instructions are not exhaustive.**

Dependencies:
* PHP
* PHP PDO
* MySQL
* Apache or Nginx
* cron (or any alternative)
* SMTP server

Steps:
* This repository has to be the server root.
* The folders `.`, `files`, `locales`, `cache` have to be writable by PHP.
* Create MySQL database and tables. (TODO: add SQL file to repo)
* Copy `inclus/config.php` to `inclus/config.local.php` and edit the copy. Ensure that this file is not readable from the network.
* Create cron jobs for the files in `tasks` (TODO: add crontab to repo)

## License

### Source code

CopyLeft 2015-2021 Team ProgAccess

ProgAccess is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, version 3 of the License.

ProgAccess is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along with ProgAccess. If not, see https://www.gnu.org/licenses/.

### Locales

CopyLeft 2018-2021 Team ProgAccess

The ProgAccess website's translations (all the files contained in the `locales` folder) are licensed under the Creative Commons Attribution-ShareAlike 4.0 International License (CC BY-SA). To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/4.0/.

### Libraries

This repository contains some libraries that may have a different license. In that case, the copyright notice and license should be in the library's folder.
