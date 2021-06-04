# Exceptions To Stream plugin for Craft CMS 3.x

A small plugin to capture thrown exceptions (excluding status codes in the 400 range) to send to standard error stream

![Screenshot](resources/img/plugin-logo.png)

Icons by [svgrepo.com](https://www.svgrepo.com/svg/38944/river) & [pngrepo.com](https://www.pngrepo.com/svg/129426/river)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project where your `composer.json` exists:

        cd /path/to/project

2. Updated `composer.json`:

        "repositories": [
          {
            "type": "vcs",
            "url": "https://github.com/extreme-creations/craft-exceptionstostream.git"
          }
        ]

3. Then tell Composer to load the plugin:

        composer require madebyextreme/exceptions-to-stream

        or

        docker-compose exec php bash -c "cd /var/www/site/craft/ && composer require madebyextreme/exceptions-to-stream"

4. In the Control Panel, go to Settings → Plugins and click the “Install” button for Exceptions To Stream, Or:

        ./craft plugin/install exceptions-to-stream

        or

        docker-compose exec php bash -c "cd /var/www/site/craft/ && ./craft plugin/install exceptions-to-stream"

Brought to you by [Extreme](https://madebyextreme.com/)
