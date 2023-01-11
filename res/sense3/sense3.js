/* global bowser */

(function sense3() {
  let sense3Baseurl = '';
  const defaultColors = ['#ffffff', '#963425', '#333333', '#757575', '#963425', '#cccccc'];
  const i18n = {
    en: {
      close: 'Close',
      notad: 'This is NOT an advertising',
      os: 'System',
      browser: 'Browser',
      adblock: 'Adblock',
      enabled: 'Enabled',
      disabled: 'Disabled ?',
      ua: 'User-Agent',
      referrer: 'Referrer',
      banner: 'Banner',
      bannerHelp: 'Banners are displayed to visitors randomly according to the criteria detected by the software. If nothing is found, a default image will be displayed (Van Gogh). It is possible to override the detection criteria and force the display of a specific banner.',
      format: 'Format',
      leaderboard: 'Leaderboard (728 x 90)',
      medium: 'Medium rectangle (350 x 200)',
      fbanner: 'Static banner',
      none: 'None (random)',
      tags: 'Tags',
      tagsHelp: 'It is possible to restrict the detection criteria. If you only want to display fake ads about Firefox and Linux for example and exclude everything else, you just need to check <code>firefox</code> and <code>linux</code> among the keywords. Obviously, if the visitor is already under Firefox and Linux, the default image will be displayed.',
      colors: 'Colors',
      colorsHelp: 'Colors only apply to text banners.',
      background: 'Background',
      text1: 'Text 1',
      text2: 'Text 2',
      link: 'Link',
      button: 'Button',
      border: 'Border',
      generate: 'Get the code',
    },
    fr: {
      close: 'Fermer',
      notad: 'Ceci n’est PAS une publicité',
      os: 'Plateforme',
      browser: 'Navigateur',
      adblock: 'Adblock',
      enabled: 'Actif',
      disabled: 'Inactif ?',
      ua: 'User-Agent',
      referrer: 'Référent',
      banner: 'Bannière',
      bannerHelp: 'Les bannières s’affichent aux visiteurs de manière aléatoire selon les critères détectés par le logiciel. Si rien n’est trouvé, une image par défaut sera affichée (Van Gogh). Il est possible d’outrepasser les critères de détection et forcer l’affichage d’une bannière spécifique.',
      format: 'Format',
      leaderboard: 'Entête large (728 x 90)',
      medium: 'Rectangle moyen (350 x 200)',
      fbanner: 'Forcer une bannière',
      none: 'Aucune (aléatoire)',
      tags: 'Mots-clés',
      tagsHelp: 'Il est possible de restreindre les critères de détection. Si vous ne voulez afficher que des fausses pubs concernant Firefox et Linux par exemple et exclure tout le reste, il vous faudra juste cocher <code>firefox</code> et <code>linux</code> parmi les mots-clés. Évidemment, si le visiteur est déjà sous Firefox et Linux, l’image par défaut sera affichée.',
      colors: 'Couleurs',
      colorsHelp: 'Les couleurs ne s’appliquent qu’aux bannières textuelles.',
      background: 'Arrière-plan',
      text1: 'Texte 1',
      text2: 'Texte 2',
      link: 'Lien',
      button: 'Bouton',
      border: 'Bordure',
      generate: 'Obtenir le code',
    },
  };
  let d = {};
  const s = {

    /** Banners ***************************************************** */
    data() {
      d = {
        /**
        <id>: {
          condition: <boolean function>,
          format: ['<filetype>', '<width>x<height>', '<width>x<height>'],
          link: {
            xx: 'https://example.com/', (i18n managed by the website)
          or
            en: 'https://example.com/en/',
            fr: 'https://example.com/fr/',
          },
          text: {
            en: ['<title>','<subtitle>'],
            fr: ['<titre>','<sous-titre>'],
          },
          tags: ['<category>', '<tag1>', '<tag2>', …],
        },
        */
        // Referrer
        dioGafam: {
          condition: s.is.bad('gafam'),
          format: ['png', '300x250'],
          link: { xx: 'https://degooglisons-internet.org/' },
          text: {
            en: ['GAFAM', 'We <3 your data !'],
            fr: ['GAFAM', 'We <3 your data !'],
          },
          tags: ['referrer', 'facebook', 'degooglisons', 'google', 'apple', 'microsoft', 'amazon', 'gafam'],
        },
        dioAmazon: {
          condition: s.is.bad('amazon'),
          format: ['png', '300x250'],
          link: { xx: 'https://degooglisons-internet.org/' },
          text: {
            en: ['Amazon', 'très méchant'],
            fr: ['Amazon', 'très méchant'],
          },
          tags: ['referrer', 'amazon', 'degooglisons', 'gafam'],
        },
        dioFacebook: {
          condition: s.is.bad('facebook'),
          format: ['png', '300x250'],
          link: { xx: 'https://degooglisons-internet.org/' },
          text: {
            en: ['Facebook', 'is watching you…'],
            fr: ['Facebook', 'is watching you…'],
          },
          tags: ['referrer', 'facebook', 'degooglisons', 'gafam'],
        },
        dioGoogle: {
          condition: s.is.bad('google'),
          format: ['png', '300x250'],
          link: { xx: 'https://degooglisons-internet.org/' },
          text: {
            en: ['Warning', 'Don’t feed the Google'],
            fr: ['Warning', 'Don’t feed the Google'],
          },
          tags: ['referrer', 'google', 'degooglisons', 'gafam'],
        },
        dioMicrosoft: {
          condition: s.is.bad('microsoft'),
          format: ['png', '300x250'],
          link: { xx: 'https://degooglisons-internet.org/' },
          text: {
            en: ['Microsoft', 'Do you need a backdoor ?'],
            fr: ['Microsoft', 'Do you need a backdoor ?'],
          },
          tags: ['referrer', 'microsoft', 'degooglisons', 'gafam'],
        },
        amazon: {
          condition: s.is.bad('twitter'),
          format: ['txt', '300x250', '728x90'],
          colors: '#231f20,#ffffff,#ffffff,#f89820,#f89820,#cccccc',
          link: { xx: 'https://framabookin.org' },
          text: {
            en: ['Thanks for buying from Amazon', 'And thanks for your personal data!'],
            fr: ['Merci pour vos achats chez Amazon', 'Et merci pour vos données personnelles !'],
          },
          tags: ['referrer', 'amazon', 'gafam', 'framabookin'],
        },
        mastodon: {
          condition: s.is.bad('twitter'),
          format: ['txt', '300x250', '728x90'],
          colors: '#282c37,#9baec8,#d9e1e8,#2b90d9,#2b90d9,#9baec8',
          link: { xx: 'https://joinmastodon.org' },
          text: {
            en: [
              ['Join Mastodon', 'A free social network in 500 characters'],
              ['Twitter chooses what you see', 'Choose what you get'],
              ['You came from Twitter', 'Try an ethic alternative'],
              ['Don’t give your life to Twitter', 'Give it a Toot!'],
            ],
            fr: [
              ['Rejoignez Mastodon', 'Le réseau social libre en 500 caractères'],
              ['Twitter choisis ce que tu vois', 'Reprend le pouvoir'],
              ['Vous étiez sur Twitter', 'Voici une alternative éthique'],
              ['N’offre pas ta vie à Twitter', 'Offre lui des Pouets !'],
            ],
          },
          tags: ['referrer', 'twitter', 'social'],
        },
        diaspora: {
          condition: s.is.bad('facebook'),
          format: ['txt', '300x250', '728x90'],
          link: {
            en: 'https://podupti.me/',
            fr: 'https://framasphere.org',
          },
          text: {
            en: [
              ['Want to #DeleteFacebook…?', 'Here’s an ethic alternative'],
              ['Do you feel clostro-Facebook…?', 'Breathe with Diaspora*'],
              ['Thank you for coming from Facebook', 'Have you thought about trying Diaspora*?'],
              ['Facebook saw you came here', 'Diaspora* would never spy on you'],
            ],
            fr: [
              ['Tu veux #DeleteFacebook…?', 'Voici une alternative éthique'],
              ['Tu te sens esclave de Facebook…?', 'Diaspora* te libère'],
              ['Merci d’être venu·e depuis Facebook', 'Avez-vous pensé à essayer Diaspora*&nbsp;?'],
              ['Facebook t’a vu venir ici', 'Diaspora* ne t’espionne pas'],
            ],
          },
          tags: ['referrer', 'facebook', 'social', 'gafam'],
        },
        // Browser
        chrome: {
          condition: s.is.bad('chrome'),
          format: ['txt', '300x250', '728x90'],
          link: { xx: 'https://www.mozilla.org/firefox/' },
          img: 'img/bg/firefox.png',
          text: {
            en: [
              ['Drop Google Chrome', 'Surf 2x faster with Firefox Quantum'],
              ['Chrome is unsecure', 'Firefox protects your data'],
            ],
            fr: [
              ['Laissez tomber Google Chrome', 'Surfez 2x plus vite avec Firefox Quantum'],
              ['Chrome n’est pas sûr', 'Firefox protège vos données'],
            ],
          },
          tags: ['browser', 'firefox', 'mozilla', 'google', 'chrome'],
        },
        edge: {
          condition: (bowser.msedge || bowser.msie),
          format: ['txt', '300x250', '728x90'],
          link: { xx: 'https://www.mozilla.org/firefox/' },
          img: 'img/bg/firefox.png',
          text: {
            en: ['Your browser is unsecure', 'Firefox protects your data'],
            fr: ['Votre navigateur n’est pas sûr', 'Firefox protège vos données'],
          },
          tags: ['browser', 'firefox', 'mozilla', 'microsoft', 'gafam'],
        },
        firefoxUpdate: {
          condition: (bowser.firefox && bowser.version <= 56),
          format: ['txt', '300x250', '728x90'],
          colors: '#0a84ff,#363959,#363959,#00feff,#ff9400,#cccccc',
          img: 'img/bg/firefox.png',
          link: { xx: 'https://www.mozilla.org/firefox/' },
          text: {
            en: ['Firefox Quantum', 'Internet for people, not profit.'],
            fr: ['Passez à Firefox Quantum', 'La dernière version ultra-rapide de Mozilla'],
          },
          tags: ['browser', 'firefox', 'mozilla', 'chrome', 'google'],
        },
        firefox: {
          condition: s.is.bad('browser'),
          format: ['txt', '300x250', '728x90'],
          colors: '#003eaa,#ffffff,#ffffff,#40b6f8,#16da00,#003eaa',
          img: 'img/bg/firefox.png',
          link: { xx: 'https://www.mozilla.org/firefox/' },
          text: {
            en: [
              ['Have you ever tried Firefox?', 'Firefox is fast and respects your privacy.'],
              ['Your privacy matters!', 'Firefox is fast and respects your privacy'],
            ],
            fr: [
              ['Avez-vous déjà essayé Firefox&nbsp;?', 'Le navigateur rapide et respectueux de votre vie privée'],
              ['Votre vie privée est importante !', 'Firefox est rapide et respecte votre vie privée'],
            ],
          },
          tags: ['browser', 'firefox', 'mozilla', 'chrome', 'google', 'safari', 'opera', 'microsoft'],
        },
        // Adblock
        ublock: {
          condition: (!s.is.adblock() && !bowser.firefox && !s.is.bad('chrome')),
          format: ['txt', '300x250', '728x90'],
          link: { xx: 'https://github.com/gorhill/uBlock' },
          text: {
            en: ['Relieve the Internet (and your eyes)', 'Use an ad blocker'],
            fr: ['Soulagez Internet (et vos yeux)', 'Utilisez un bloqueur de pub'],
          },
          tags: ['browser', 'adblock', 'advertising'],
        },
        ublockFirefox: {
          condition: (!s.is.adblock() && bowser.firefox),
          format: ['txt', '300x250', '728x90'],
          link: {
            en: 'https://addons.mozilla.org/en-US/firefox/addon/ublock-origin/',
            fr: 'https://addons.mozilla.org/fr/firefox/addon/ublock-origin/',
          },
          text: {
            en: ['Please, block this ad', 'Install uBlock Origins'],
            fr: ['S’il te plaît, bloque cette pub…', 'Installe uBlock Origins'],
          },
          tags: ['browser', 'adblock', 'advertising', 'mozilla', 'firefox'],
        },
        ublockChrome: {
          condition: (!s.is.adblock() && s.is.bad('chrome')),
          format: ['txt', '300x250', '728x90'],
          link: {
            xx: 'https://chrome.google.com/webstore/detail/ublock-origin/cjpalhdlnbpafiamejdnhcphjbkeiagm',
          },
          text: {
            en: ['Relieve the Internet (and your eyes)', ' Use an ad blocker'],
            fr: ['Soulagez Internet (et vos yeux)', 'Utilisez un bloqueur de pub'],
          },
          tags: ['browser', 'adblock', 'advertising', 'google', 'chrome'],
        },
        green: {
          condition: (!s.is.adblock() && (s.is.ref('lilo.org') || s.is.ref('ecosia.org'))),
          format: ['txt', '300x250', '728x90'],
          colors: '#ffffff,#467c81,#333333,#8bcc49,#467c81,#cccccc',
          link: { xx: 'https://github.com/gorhill/uBlock' },
          text: {
            en: ['Ecological and easy', 'Blocking advertising reduces web traffic by ~60%'],
            fr: ['Écolo et simple', 'Bloquer la pub réduit le trafic web de ~60%'],
          },
          tags: ['browser', 'adblock', 'advertising', 'green', 'referrer'],
        },
        // OS
        elementary: {
          condition: s.is.bad('pc'),
          format: ['txt', '300x250', '728x90'],
          link: { xx: 'https://elementary.io' },
          text: {
            en: ['Elementary', 'A fast and open replacement for Windows and MacOS'],
            fr: ['Elementary', 'Un remplaçant rapide, élégant et libre à Windows et MacOS'],
          },
          tags: ['system', 'elementary', 'windows', 'linux', 'mac'],
        },
        mageia: {
          condition: s.is.bad('pc'),
          format: ['txt', '300x250', '728x90'],
          link: { xx: 'https://www.mageia.org' },
          text: {
            en: ['Mageia', 'Easy to use, user-friendly, stable and efficient linux'],
            fr: ['Mageia', 'Un linux facile d’utilisation, convivial, stable et efficace'],
          },
          tags: ['system', 'mageia', 'windows', 'linux', 'mac'],
        },
        ubuntu: {
          condition: s.is.bad('win10'),
          colors: '#e95420,#ffffff,#ffffff,#000000,#5E2750,#cccccc',
          img: 'img/bg/ubuntu.png',
          format: ['txt', '300x250', '728x90'],
          link: {
            en: 'https://www.ubuntu.com/desktop',
            fr: 'http://ubuntu-fr.org',
          },
          text: {
            en: ['Your Windows feeds on your data', 'Here’s an alternative'],
            fr: ['Ce Windows pompe tes données', 'Voici une alternative'],
          },
          tags: ['system', 'ubuntu', 'windows', 'linux', 'mac'],
        },
        win10: {
          condition: s.is.bad('win10'),
          format: ['txt', '300x250', '728x90'],
          link: {
            en: 'https://fix10.isleaked.com',
            fr: 'https://www.cnil.fr/fr/reglez-les-parametres-vie-privee-de-windows-10',
          },
          text: {
            en: ['Your Windows is watching you', 'Here is how to blind it'],
            fr: ['Ton Windows te regarde', 'Voici comment l’aveugler'],
          },
          tags: ['system', 'microsoft', 'windows'],
        },
        sense3: {
          condition: false,
          format: ['png', '300x250', '728x90'],
          link: { xx: 'https://sense3.org' },
          text: {
            en: ['This is not an ad', ' (nor a pipe)'],
            fr: ['Ceci n’est pas une pub', '(ni une pipe)'],
          },
          tags: ['adblock', 'advertising', 'google'],
        },
        sense3Fork: {
          condition: false,
          format: ['txt', '300x250'],
          link: { xx: 'https://framagit.org/josephk/sense3' },
          text: {
            en: ['Fork me', 'I’m famous :P'],
            fr: ['Fork me', 'I’m famous :P'],
          },
          tags: ['adblock', 'advertising', 'google'],
        },
        // Art
        vangogh: {
          condition: true,
          format: ['jpg', '300x250', '728x90'],
          link: { xx: 'https://commons.wikimedia.org/wiki/File:VanGogh-starry_night.jpg' },
          text: {
            en: ['Starry night', 'Vincent van Gogh - Public Domain'],
            fr: ['Nuit étoilée', 'Vincent van Gogh - Domaine Public'],
          },
          tags: ['art', 'vangogh', 'painting', 'france'],
        },
        patturaani: {
          condition: true,
          format: ['jpg', '300x250', '728x90'],
          link: { xx: 'https://commons.wikimedia.org/wiki/File:Ganapathy_-_Wedding.jpg' },
          text: { en: ['Ganaphathy Wedding', 'Patturaani - CC By-SA'] },
          tags: ['art', 'patturaani', 'painting', 'india', 'ganesha'],
        },
        hals: {
          condition: true,
          format: ['jpg', '300x250', '728x90'],
          link: { xx: 'https://commons.wikimedia.org/wiki/File:Frans_Hals_-_Luitspelende_nar.jpg' },
          text: {
            en: ['Buffoon playing a lute', 'Frans Hals - Public Domain'],
            fr: ['Boufon au luth', 'Frans Hals - Domaine Public'],
          },
          tags: ['art', 'painting', 'netherlands', 'hals'],
        },
        chaplin: {
          condition: true,
          format: ['mp4', '300x250'],
          link: { xx: 'https://commons.wikimedia.org/wiki/File:The_Kid_(1921).webm' },
          text: {
            en: ['The Kid', 'Charlie Chaplin - Public Domain'],
            fr: ['Le Kid', 'Charlie Chaplin - Domaine Public'],
          },
          tags: ['art', 'cinema', 'chaplin', 'movie'],
        },
        wikipedia: {
          condition: true,
          format: ['txt', '300x250', '728x90'],
          img: 'img/bg/wikipedia.png',
          link: {
            en: 'https://en.wikipedia.org',
            fr: 'https://fr.wikipedia.org',
          },
          text: {
            en: ['Contribute to Wikipedia', 'The free encyclopedia that anyone can edit'],
            fr: ['Contribuez à Wikipédia', 'L’encyclopédie libre que chacun peut améliorer'],
          },
          tags: ['generic', 'wikipedia', 'encyclopedia'],
        },
      };
    },

    /** Sense3 ****************************************************** */
    init() {
      if (s.is.inframe()) {
        document.addEventListener('DOMContentLoaded', () => {
          s.data();
          s.selectBanner();
        });
      } else {
        s.addIframes();
        document.addEventListener('DOMContentLoaded', () => {
          s.showGenerator();
        });
      }
    },

    addIframes() {
      const scripts = document.getElementsByTagName('script');
      let j = 0;
      for (let i = 0; i < scripts.length; i += 1) {
        if (scripts[i].getAttribute('data-sense3')) {
          let [format, tags, colors, id] = scripts[i].getAttribute('data-sense3').split(';');
          format = format || '300x250';
          tags = tags || '';
          colors = colors || defaultColors.join().toLowerCase();
          id = id || '';
          const [w, h] = format.split('x');

          sense3Baseurl = s.l(scripts[i].getAttribute('src').replace('sense3.js', './'));
          const referrer = (document.referrer.split('/')[2] !== undefined) ? document.referrer.split('/')[2] : '';

          const iframe = document.createElement('iframe');
          iframe.width = w; iframe.height = h;
          iframe.frameborder = '0'; iframe.marginwidth = '0'; iframe.marginheight = '0';
          iframe.vspace = '0'; iframe.hspace = '0';
          iframe.allowtransparency = 'true'; iframe.scrolling = 'no';
          iframe.style = `width: ${w}px; height: ${h}px; border: none;`;
          iframe.src = `${sense3Baseurl}sense3.html#${s.pageLang()};${tags};${colors};${id};${referrer}`;
          iframe.className = 'sense3';
          iframe.id = `sense3_${j}`;
          if (!document.getElementById(`sense3_${j}`)) {
            scripts[i].parentNode.insertBefore(iframe, scripts[i].nextSibling);
          }
          j += 1;
        }
      }
    },

    selectBanner() {
      const w = window.innerWidth;
      const h = window.innerHeight;

      // Import params from hash
      let lg = ''; let tags = ''; let id; let colors;
      [lg, tags, colors, id] = window.location.hash.replace(/^#/, '').split(';');
      document.getElementsByTagName('html')[0].setAttribute('lang', lg);

      if (id !== 'showInfos') {
        // List contextual banners
        const banner = []; let i = 0;
        Object.keys(d).forEach((k) => {
          if (d[k].condition &&
            d[k].format.indexOf(`${w}x${h}`) !== -1
            && s.is.inTags(d[k].tags, tags.split(','))) {
            banner[i] = k;
            i += 1;
          }
        });
        // Choose a random banner or 'vangogh'
        if (id === '') {
          id = (banner.length !== 0) ? banner[Math.floor(Math.random() * (banner.length))] : 'vangogh';
        }

        s.showBanner(id, lg, w, h);

        // Add style if exist in params or in data{}
        colors = d[id].colors || colors;
        const img = d[id].img || '';
        s.customize(colors, img);
      } else {
        s.showInfos(lg);
      }
    },

    showBanner(id, lg, w, h) {
      if (d[id] !== undefined) {
        const type = d[id].format[0];
        const llg = (d[id].link.xx !== undefined) ? 'xx' : lg;
        const tlg = (d[id].text[lg] === undefined) ? 'en' : lg; // replace 'en' by browser default lang (if text is avalaible)

        // Is a random sentense ?
        let t1 = ''; let t2 = '';
        if (typeof d[id].text[tlg][0] === 'string') {
          [t1, t2] = d[id].text[tlg];
        } else if (d[id].text[tlg][0].constructor.name === 'Array') {
          const random = Math.floor(Math.random() * d[id].text[tlg].length);
          [t1, t2] = d[id].text[tlg][random];
        }

        let html = '';
        // Image
        if (/(png|jpg|gif)/.test(type)) {
          html = `
          <a id="img" href="${d[id].link[llg]}" target="_blank" title="${d[id].text[tlg].join(' - ')}">
            <img alt="" src="img/${id}-${w}_${h}-${llg}.${type}"
              onerror="this.style='display:none;';document.getElementById('text').style='';">
          </a>
          <a id="text" href="${d[id].link[llg]}" target="_blank" style="display:none;">
            <h1>${t1}</h1>
            <p>${d[id].link[llg].split('/')[2]}</p>
            <h2>${t2}</h2>
            <span class="button" aria-hidden="true">➤</span>
          </a>`;

        // Video
        } else if (/(mp4|webm)/.test(type)) {
          html = `
          <a id="video" href="${d[id].link[llg]}" target="_blank">
            <video autoplay="" muted loop="loop" preload="none">
              <source src="img/${id}-${w}_${h}-${llg}.${type}" type="video/${type}"/>
              <h1>${t1}</h1>
              <p>${d[id].link[llg].split('/')[2]}</p>
              <h2>${t2}</h2>
              <span class="button" aria-hidden="true">➤</span>
            </video>
          </a>`;
        // Text
        } else {
          html = `
          <a id="text" href="${d[id].link[llg]}" target="_blank">
            <h1>${t1}</h1>
            <p>${d[id].link[llg].split('/')[2]}</p>
            <h2>${t2}</h2>
            <span class="button" aria-hidden="true">➤</span>
          </a>`;
        }
        html = `${html}
          <a id="about" href="https://sense3.org" target="_blank" title="${i18n[lg].notad}">
            <img src="frameicon.png" alt="s3">
          </a>
          <a id="close" href="#" onclick="document.getElementsByTagName('body')[0].className = 'hidden'; return false;" title="${i18n[lg].close}">
            <i aria-hidden="true">&times;</i>
            <span class="sr-only">${i18n[lg].close}</span>
          </a>`;
        document.getElementsByTagName('body')[0].innerHTML = html;
      }
    },

    customize(colors, img) {
      const [bg, h1, h2, p, btn, bdr] = colors.replace(' ', '').toLowerCase().split(',');
      const bgImg = (img !== '') ? `background-image: url(${img});` : '';

      const style = document.createElement('style');
      style.innerHTML = `
          #text {
            background-color: ${bg.substr(0, 7)};
            border-color: ${bdr.substr(0, 7)};
            ${bgImg}
          }

          #text h1 {
            color: ${h1.substr(0, 7)};
          }

          #text h2 {
            color: ${h2.substr(0, 7)};
          }

          #text p {
            color: ${p.substr(0, 7)};
          }

          #text .button {
            background: ${btn.substr(0, 7)};
          }

          #text ~ #about,
          #text ~ #close {
            background: ${bdr.substr(0, 7)};
          }
      `;
      document.getElementsByTagName('head')[0].appendChild(style);
    },

    showInfos(lg) {
      const browsers = [
        'android', 'chrome', 'chromium', 'firefox', 'ios',
        'kMeleon', 'msedge', 'msie', 'opera', 'qupzilla',
        'safari', 'sailfish', 'samsungBrowser', 'seamonkey',
        'sleipnir', 'tizen', 'vivaldi',
      ];
      const os = [
        'android', 'blackberry', 'chromeos', 'firefoxos',
        'ios', 'linux', 'mac', 'tizen', 'webos',
        'windowsphone', 'windows',
      ];
      const params = window.location.hash.replace(/#/, '').split(';');
      const referrer = params[(params.length - 1)];
      const img = {
        os: (os.indexOf(bowser.osname.toLowerCase()) !== -1) ? `${bowser.osname.toLowerCase()}.png` : 'unknown.png',
        browser: (browsers.indexOf(bowser.name.toLowerCase()) !== -1) ? `${bowser.name.toLowerCase()}.png` : 'unknown.png',
        adblock: s.is.adblock() ? 'ublock-on.png' : 'ublock-off.png',
      };

      const html = `
        <table>
          <thead>
            <tr>
              <th>${i18n[lg].os}</th>
              <th>${i18n[lg].browser}</th>
              <th>${i18n[lg].adblock}</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><img src="img/icons/os/${img.os}" alt=""><br>${bowser.osname} ${(bowser.osversion || '')}</td>
              <td><img src="img/icons/browsers/${img.browser}" alt=""><br>${bowser.name} ${(bowser.version || '')}</td>
              <td><img src="img/icons/ublock/${img.adblock}" alt=""><br>${(s.is.adblock() ? i18n[lg].enabled : i18n[lg].disabled)}</td>
            <tr>
          </tbody>
        </table>
        <table>
          <tbody>
            <tr>
              <th><label for="ua">${i18n[lg].ua}</label></th>
              <td>
                <input type="text" class="form-control" id="ua" value="${window.navigator.userAgent}" readonly>
              </td>
            </tr>
            <tr>
              <th><label for="referrer">${i18n[lg].referrer}</label></th>
              <td>
                <input type="text" class="form-control" id="referrer" value="${referrer}" readonly>
              </td>
            </tr>
          </tbody>
        </table>`;
      document.getElementsByTagName('body')[0].innerHTML = html;
    },

    showGenerator() {
      const scripts = document.getElementsByTagName('script');
      for (let i = 0; i < scripts.length; i += 1) {
        if (scripts[i].getAttribute('data-sense3-generator')) {
          sense3Baseurl = s.l(scripts[i].getAttribute('src').replace('sense3.js', './'));
          const lg = s.pageLang();
          s.data();
          const tags = s.list('tags'); const ids = s.list('ids');
          let htmlTags = ''; let htmlIds = '';
          Object.keys(tags[0]).forEach((k) => {
            htmlTags = `${htmlTags}
              <label class="col-xs-6 col-sm-4 col-md-2 btn-xs btn-default">
                <input type="checkbox" value="${k}"> ${k} <span class="badge">${tags[0][k]}</span>
              </label>`;
          });
          for (let j = 0; j < ids.length; j += 1) {
            htmlIds = `${htmlIds}
              <option value="${ids[j]}">${ids[j]}</option>`;
          }

          const html = `
          <form class="form-horizontal" id="sense3_generator" onsubmit="document.getElementById('sense3_code').className = ''; sense3Generate(); return false;">
            <input type="hidden" id="sense3Baseurl" value="${sense3Baseurl}">
            <input type="hidden" id="sense3DefaultColors" value="${defaultColors.join().toLowerCase()}">
            <fieldset id="banner">
            <legend>${i18n[lg].banner}</legend>
              <p class="alert alert-info small">${i18n[lg].bannerHelp}</p>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="format" class="col-sm-4 control-label">${i18n[lg].format}</label>
                    <div class="col-sm-8">
                      <select id="format" class="form-control">
                        <option value="728x90">${i18n[lg].leaderboard}</option>
                        <option value="300x250">${i18n[lg].medium}</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="force" class="col-sm-4 control-label">${i18n[lg].fbanner}</label>
                    <div class="col-sm-8">
                      <select id="force" class="form-control" onChange="document.getElementById('tags').style = (this.value !== '') ? 'display: none;' : '';">
                        <option value="">${i18n[lg].none}</option>
                        ${htmlIds}
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
            <fieldset id="tags">
              <legend>${i18n[lg].tags}</legend>
              <p class="alert alert-info small">${i18n[lg].tagsHelp}</p>
              ${htmlTags}
            </fieldset>
            <fieldset id="colors">
              <legend>${i18n[lg].colors}</legend>
              <p class="alert alert-info small">${i18n[lg].colorsHelp}</p>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="bg" class="col-sm-4 control-label">${i18n[lg].background}</label>
                    <div class="col-sm-8">
                      <input id="bg" value="${defaultColors[0].toLowerCase()}" class="form-control" type="color">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="h1" class="col-sm-4 control-label">${i18n[lg].text1}</label>
                    <div class="col-sm-8">
                      <input id="h1" value="${defaultColors[1].toLowerCase()}" class="form-control" type="color">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="h2" class="col-sm-4 control-label">${i18n[lg].text2}</label>
                    <div class="col-sm-8">
                      <input id="h2" value="${defaultColors[2].toLowerCase()}" class="form-control" type="color">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="p" class="col-sm-4 control-label">${i18n[lg].link}</label>
                    <div class="col-sm-8">
                      <input id="p" value="${defaultColors[3].toLowerCase()}" class="form-control" type="color">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="btn" class="col-sm-4 control-label">${i18n[lg].button}</label>
                    <div class="col-sm-8">
                      <input id="btn" value="${defaultColors[4].toLowerCase()}" class="form-control" type="color">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="bdr" class="col-sm-4 control-label">${i18n[lg].border}</label>
                    <div class="col-sm-8">
                      <input id="bdr" value="${defaultColors[5].toLowerCase()}" class="form-control" type="color">
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
            <div id="sense3_code" class="hidden">
              <div id="preview" class="text-center"></div>
              <pre style="max-width:600px;margin:10px auto;"><code></code></pre>
            </div>
            <p class="text-right"><button class="btn btn-primary" type="submit">${i18n[lg].generate}</button></p>
          </form>
          `;

          const div = document.createElement('div');
          div.innerHTML = html;
          div.id = 'sense3_generator';
          if (!document.getElementById('sense3_generator')) {
            scripts[i].parentNode.insertBefore(div, scripts[i].nextSibling);
          }
        }
      }
    },

    list(type) {
      let list = []; const count = {};
      switch (type) {
        case 'tags':
          Object.keys(d).forEach((k) => {
            for (let i = 0; i < d[k].tags.length; i += 1) {
              list.push(d[k].tags[i]);
            }
          });
          list.sort();
          list.forEach((x) => { count[x] = (count[x] || 0) + 1; });
          list = [];
          list.push(count);
          break;
        case 'ids':
          Object.keys(d).forEach((k) => {
            list.push(k);
          });
          break;
        default:
          // no default
          break;
      }
      return list;
    },

    /** Generic fonctions ******************************************* */
    l(href) {
      const link = document.createElement('a');
      link.href = href;
      return [link.protocol, '//', link.host, link.pathname, link.search, link.hash].join('');
    },

    pageLang() {
      let lang = '';
      const html = document.getElementsByTagName('html');
      const meta = document.getElementsByTagName('script');

      if (html[0].getAttribute('lang')) {
        lang = html[0].getAttribute('lang');
      } else if (html[0].getAttribute('locale')) {
        lang = html[0].getAttribute('locale');
      } else {
        for (let i = 0; i < meta.length; i += 1) {
          if ((meta[i].getAttribute('http-equiv') && meta[i].getAttribute('content') &&
              meta[i].getAttribute('http-equiv').indexOf('Language') > -1) ||
              (meta[i].getAttribute('property') && meta[i].getAttribute('content')
               && meta[i].getAttribute('property').indexOf('locale') > -1)) {
            lang = meta[i].getAttribute('content');
          }
        }
      }
      lang = (lang !== '') ? lang : 'en';

      return lang.substr(0, 2).toLowerCase();
    },

    /** Boolean fonctions ******************************************* */
    is: {
      inframe() {
        return (window.top.location !== window.self.document.location);
      },

      DNT() {
        return (navigator.doNotTrack === 'yes' ||
          navigator.doNotTrack === '1' || window.doNotTrack === '1');
      },

      lang(lg) {
        const userLang = navigator.languages ||
          [root.navigator.language || root.navigator.userLanguage];
        for (let i = 0; i < userLang.length; i += 1) {
          if (userLang[i].substring(0, 2).toLowerCase() === lg) {
            return true;
          }
        }
        return false;
      },

      ref(url) {
        const params = window.location.hash.replace(/#/, '').split(';');
        return (params[(params.length - 1)] === url);
      },

      adblock() {
        const spoof = document.getElementById('bottomAd');

        let blockedSpoof = false;

        if (!spoof) {
          blockedSpoof = true;
        } else if (spoof.style && spoof.style.display === 'none') {
          blockedSpoof = true;
        } else if ((typeof spoof.clientHeight) !== 'undefined' && spoof.clientHeight === 0) {
          blockedSpoof = true;
        }

        return blockedSpoof;
      },

      inTags(t1, t2) {
        if (t2[0] === '') { return true; }

        for (let i = 0; i < t2.length; i += 1) {
          if (t1.indexOf(t2[i].toLowerCase().replace(' ', '')) !== -1) { return true; }
        }

        return false;
      },

      // Shortcuts for conditions
      bad(target) {
        let b = false;
        switch (target) {
          // Browsers
          case 'browser':
            b = s.is.bad('chrome') || bowser.safari ||
              bowser.opera || bowser.msie || bowser.msedge;
            break;
          case 'chrome':
            b = bowser.chrome || bowser.chromium;
            break;
          // OS
          case 'os':
            b = s.is.bad('pc') || s.is.bad('mobile');
            break;
          case 'pc':
            b = bowser.mac || bowser.windows;
            break;
          case 'mobile':
            b = bowser.ios;
            break;
          case 'win10':
            b = bowser.windows && bowser.check({ windows: '10' });
            break;
          // Referers
          case 'search':
            b = s.is.ref('google.com') || s.is.ref('bing.com') ||
              s.is.ref('yahoo.com');
            break;
          case 'social':
            b = s.is.bad('facebook') || s.is.bad('twitter');
            break;
          case 'video':
            b = s.is.bad('youtube') || s.is.ref('dailymotion.com') || s.is.ref('vimeo.com');
            break;
          // GAFAM
          case 'gafam':
            b = s.is.bad('google') || s.is.bad('apple') || s.is.bad('facebook') ||
              s.is.bad('amazon') || s.is.bad('microsoft');
            break;
          case 'google':
            b = s.is.bad('chrome') || s.is.ref('google.com')
              || s.is.ref('gmail.com') || s.is.bad('youtube');
            break;
          case 'apple':
            b = bowser.ios || bowser.safari;
            break;
          case 'facebook':
            b = s.is.ref('facebook.com'); // TODO add facebook link reducer
            break;
          case 'amazon':
            b = s.is.ref('amazon.com');
            break;
          case 'microsoft':
            b = bowser.windows || bowser.msie || bowser.msedge ||
              s.is.ref('bing.com');
            break;
          case 'twitter':
            b = s.is.ref('twitter.com') || s.is.ref('t.co');
            break;
          case 'youtube':
            b = s.is.ref('youtube.com') || s.is.ref('youtu.be');
            break;
          default:
            // no default
            break;
        }
        return b;
      },
    },
  };
  s.init();
}());

function sense3Generate() { // eslint-disable-line no-unused-vars
  const sense3Baseurl = $('#sense3Baseurl').val() || '';
  const sense3DefaultColors = $('#sense3DefaultColors').val() || '';
  const force = $('#force').val() || '';
  const tags = []; const colors = [];
  if (force === '') {
    $('#tags input:checked').each((i) => {
      tags.push($('#tags input:checked').eq(i).val());
    });
  }
  $('#colors input').each((i) => {
    colors.push($('#colors input').eq(i).val());
  });

  let params = $('#format').val();
  params = `${params};${tags.join()};${colors.join()};${force}`;
  params = params.replace(sense3DefaultColors, '').replace(/(;+)$/, '');

  const code = `<script src="${sense3Baseurl}sense3.js" data-sense3="${params}"></script>`;
  $('#sense3_code code').text(code);
  $('#sense3_code #preview').html(code);
}
