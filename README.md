| :warning: Note - Not the latest version |
|:----------------------------------------|
| This is the repository for Bolt 3 PHP 8.1 Fork. Please know that <br><strong>Bolt 5 has been released</strong>. If you are starting a <br>new project, please use the following:                                
| - [Bolt 5 installation instructions](https://docs.boltcms.io/5.0/installation/installation) |
| - [Bolt 5 Core repository](https://github.com/bolt/core)   |


Bolt 3 on PHP 8.1
===

Welcome to the Bolt 3 Fork for PHP 8.0 and 8.1!

PHP 7.4 will not receive security updates after 25 November 2022.
This project allows you to run Bolt 3 on a security supported version until 28 November 2024. 

Why?
---
Bolt 5 is sadly missing a lot of extensions like menueditor, emailobfuscator, menuchain.
Updating multi-language sites is also not trivial.
If you are not under these constraints, please consider updating to Bolt 5.

How to update from the official version:
-------

Adapt your composer.json to the snippet below.
Then run composer update.

    "config": {
		"vendor-dir":"vendor",
		"bin-dir":"vendor/bin",
        "platform": {
			"php": "8.1"
		}
	},
    "require": {
        "php": "^7.3 || ^8.0",
        "symfony/http-foundation": "dev-bolt3 as v2.8.52",
        "symfony/yaml": "dev-v2.8.52-php8 as 2.8.52",
        "bolt/bolt": "3.7.x-dev",
        "bolt/configuration-notices": "^1.0",
        "bolt/simple-deploy": "^1.0@beta",
    },
    "minimum-stability": "dev",
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/Largo/symfony-http-foundation.git",
            "references": "bolt3"
        },
        {
            "type": "git",
            "url": "https://github.com/Largo/bolt3.git"
        },
        {
            "type": "git",
            "url": "https://github.com/Largo/symfony-yaml.git"
        },
        {
            "type": "git",
            "url": "https://github.com/Largo/bolt-collection.git"
        },
        {
            "type": "git",
            "url": "https://github.com/Largo/bolt-common.git"
        },
        {
            "type": "git",
            "url": "https://github.com/Largo/bolt-filesystem.git"
        },
        {
            "type": "git",
            "url": "https://github.com/Largo/bolt-requirements.git"
        },
        {
            "type": "git",
            "url": "https://github.com/Largo/bolt-package-wrapper.git"
        },
        {
            "type": "git",
            "url": "https://github.com/Largo/bolt-session.git"
        },
        {
            "type": "git",
            "url": "https://github.com/Largo/bolt-themes.git"
        },
        {
            "type": "git",
            "url": "https://github.com/Largo/bolt-thumbs.git"
        },
        {
            "type": "git",
            "url": "https://github.com/Largo/bolt-codingstyle.git"
        },
        {
            "type": "git",
            "url": "https://github.com/Largo/bolt-event-dispatcher.git"
        },
        {
            "type": "git",
            "url": "https://github.com/Largo/symfony-config.git"
        },
        {
            "type": "git",
            "url": "https://github.com/Largo/pimple.git"
        },
        {
            "type": "git",
            "url": "https://github.com/Largo/Silex.git"
        }
    ]


Warning:
-------
Deprecation Errors are hidden.
So far all functions appear to work correctly, but we do not offer any guarantees.
This project might work on PHP 8.2, but it will not work in it's current form on PHP 9.0,
when the deprecated functions will probably be removed.


Authors
-----
PHP 8.1 Adaption work by Largo.
Thanks to Two Kings and Friends for Bolt!

Bolt
====

A [Sophisticated, lightweight & simple CMS][bolt-cm] released under the open
source [MIT-license][MIT-license].

Bolt is a tool for Content Management, which strives to be as simple and
straightforward as possible.

It is quick to set up, easy to configure, uses elegant templates, and above
all, it's a joy to use!

Bolt is created using modern open source libraries, and is best suited to build
sites in HTML5 with modern markup.

Installation
------------

Detailed instructions can be found in the [official documentation][docs].

**NOTE:** Cloning the repository directly is only supported for development of
the core of Bolt, see the link above for various supported options to suit
your needs.

Reporting issues
----------------

See our [Contributing to Bolt][contributing] guide.

Support
-------

Have a question? Want to chat? Run into a problem? See our [community][support]
page.

Development
--------

The ongoing Bolt development and maintenance takes place under the care of:

 - [Two Kings &ndash; Artisinal Web Development](https://twokings.nl)

Sponsors: 

 - [Webforward, Richard Leishman](https://www.webfwd.co.uk/)
 - → You and/or your company's name on this list? 
 [Become a sponsor](https://github.com/users/bobdenotter/sponsorship).
 
---

[![Build Status][travis-badge]][travis] [![Scrutinizer Continuous Inspections][codeclimate-badge]][codeclimate] [![SensioLabsInsight][sensio-badge]][sensio-insight] [![CII Best Practices](https://bestpractices.coreinfrastructure.org/projects/1223/badge)](https://bestpractices.coreinfrastructure.org/projects/1223) [![Slack][slack-badge]](https://slack.bolt.cm)

[bolt-cm]: https://bolt.cm
[MIT-license]: http://opensource.org/licenses/mit-license.php
[docs]: https://docs.bolt.cm/installation
[support]: https://bolt.cm/community
[travis]: http://travis-ci.org/bolt/bolt
[travis-badge]: https://travis-ci.org/GawainLynch/bolt.svg?branch=release%2F3.3
[codeclimate]: https://lima.codeclimate.com/github/bolt/bolt
[codeclimate-badge]: https://lima.codeclimate.com/github/bolt/bolt/badges/gpa.svg
[sensio-insight]: https://insight.sensiolabs.com/projects/4d1713e3-be44-4c2e-ad92-35f65eee6bd5
[sensio-badge]: https://insight.sensiolabs.com/projects/4d1713e3-be44-4c2e-ad92-35f65eee6bd5/mini.png
[slack-badge]: https://slack.bolt.cm/badge/ratio
[contributing]: https://github.com/bolt/bolt/blob/master/.github/CONTRIBUTING.md
