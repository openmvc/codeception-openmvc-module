# Codeception OpenMVC Module

This module will let you run functional tests against your OpenMVC app.

## Installation

Add the package into your composer.json:

    {
      "require-dev": {
        "codeception/codeception": "*",
        "openmvc/codeception-openmvc-module": "dev-master"
      }
    }

Tell Composer to download
the package:

    php composer.phar update

Then enable it in your `functional.suite.yml` configuration
(default values are shown below):

    class_name: TestGuy
    modules:
      enabled:
        - OpenMVC
      config:
        OpenMVC:
          locale: 'en'
          index: 'public_html/index.php'

## Current issues

Currently, in order to test that one page redirects to another page, you'd need
PHP XDebug extension. Planning to refactor the code of both OpenMVC
framework and this module so that XDebug
extension won't be required. Stay tuned.

# License

Released under the same liceces as Codeception: MIT
