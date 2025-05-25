<?php

$tr = [
  '_' => 'contact',
  '_todo_level' => 2,
  '_last_author' => 'Corentin',
  '_last_modif' => 1747856433,
  'title' => 'About {{site}}',
  'maintext' => <<<ENDSTR
      <p>Here are the website's team, history, contacts... (all dates and times are given in Paris timezone, GMT+1 or +2)</p>

      <h2>The {{site}} team</h2>
      <p>Here is the list of the website administration team members:</p>
      <ul>
      {{teamlist}}
      </ul>
      <ul>
      <li>The drawer of our logos: <a href="https://facebook.com/crayongra">David Engélibert</a>.</li>
      <li><a href="/contact_form.php">Contact us</a>.</li>
      </ul>
      <ul>
      <li><a href="skype:live:miklhcos?chat">Discuss on Skype (needs a Skype client to be installed)</a></li>
      <li><a href="skype:live:miklhcos?call">Call us on Skype (needs a Skype client to be installed)</a></li>
      </ul>

      <h2>Website's history</h2>
      <p>This website was born on 2015-05-25 named <q>Accessibilité Programmes</q>.<br>
      It has been renamed <q>ProgAccess33</q> on 2016-09-19, and finally, its name has been shortened again to became <q>ProgAccess</q> with the V15.0 on 2017-09-09.<br>
      This website comes from the Corentin's desire (a blind high school student) to share his passion and to gather in one place the softwares accessible by blind people and by everybody.</p>
      <p>During summer 2018, many changes came: in August, <a href="opensource.php">the source code</a> has been published under <a href="https://gnu.org">GNU AGPL</a> license, so the website is a free (libre) software. Later in the same month, the team start translating the site, beginning with Italian, Esperanto, English and Spanish. Michel Such gave the website <a href="https://nvda.fr">NVDA.FR</a> to us, and a dedicated website is created for <a href="https://accessikey.nvda.fr">the AccessiKey</a>.</p>

      <h2>Follow us</h2>
      <h3>On social networks</h3>
      <ul>
      <li><a href="https://www.facebook.com/ProgAccess">ProgAccess on Facebook</a></li>
      </ul>

      <h3>Directly on the site</h3>
      <ul>
      <li><a href="/newsletter.php">Subscribe to the newsletter (sent each night bitween 20:12 and 20:21)</a></li>
      <li><a href="/history.php">Modifications journal (real-time)</a></li>
      <li><a href="/rss_feed.xml">RSS stream (real-time)</a></li>
      <li><a href="/api">For developpers: open-data via our API</a></li>
      </ul>

      <h2>Help us</h2>
      <a href="https://liberapay.com/ProgAccess/donate" style="border: 2px solid #f6c915; border-radius: 5px; color: #1a171b; background: white; display: inline-block; font-family: Helvetica Neue,Helvetica,sans-serif; font-size: 14px; max-width: 150px; min-width: 110px; position: relative; text-align: center; text-decoration: none;">
      <span style="background-color: #f6c915; display: block; font-family: Ubuntu,Arial,sans-serif; font-style: italic; font-weight: 700; padding: 3px 7px 5px;">
      <img src="https://liberapay.com/assets/liberapay/icon-v2_black.svg" height="20" width="20" style="vertical-align: middle;" alt="Logo Liberapay"/>
      <span style="vertical-align: middle;">LiberaPay</span>
      </span>
      <span style="display: block; padding: 5px 15px 2px;"><span style="color: #f6c915; position: absolute; left: -2px;">&#10132;</span>We receive <br><span style="font-size: 125%">0,00&#8239;€</span><br> weekly</span>
      </a>
      <p>You can donate in <a href="https://monnaie-libre.fr">libre currency</a> to ProgAccess, <a href="https://demo.cesium.app/#/app/wot/EEGevmgQcgzXou2ucaf1S9pCMvwKfu56ukRRLPn4D3y9/">with the public key EEGevmgQ</a>. (<a href="https://duniter.org/en/duniter-why-how/">What is a libre currency?</a>)</p>

      <h2>Our contributions</h2>
      <p>The members of {{site}} contribute to other projects!</p>
      <table class="table1">
      <caption>Our contributions</caption>
      <thead>
      <tr>
      <th>Project</th>
      <th>Platform</th>
      <th>Contributors</th>
      <th>Object</th>
      </tr>
      </thead>
      <tbody>
      <tr>
      <td><a href="https://github.com/BearWare/TeamTalk5">TeamTalk</a></td>
      <td>Windows, Android, IOS, GNU/Linux, Mac</td>
      <td>Corentin</td>
      <td>French translation, code</td>
      </tr>
      <tr>
      <td>Sullivan+</td>
      <td>Android</td>
      <td>Corentin</td>
      <td>French translation</td>
      </tr>
      <tr>
      <td><a href="http://twblue.es/">TWBlue</a></td>
      <td>Windows</td>
      <td>Corentin</td>
      <td>French translation</td>
      </tr>
      <tr>
      <td><a href="https://www.nvaccess.org/">NVDA</a></td>
      <td>Windows</td>
      <td>Corentin (cotranslator)</td>
      <td>French translation</td>
      </tr>
      <tr>
      <td><a href="https://gparted.org">GParted</a></td>
      <td>GNU/Linux</td>
      <td>Pascal</td>
      <td>Accessibility</td>
      </tr>
      </tbody>
      </table>
      ENDSTR
,
  'teamlist_item' => '{{age}} years old, joined on {{date}}.',
];
