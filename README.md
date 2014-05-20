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

Then enable it in your `functional.suite.yml` configuration:

    class_name: TestGuy
    modules:
      enabled:
        - OpenMVC

# License

Released under the same liceces as Codeception: MIT
