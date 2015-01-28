Document Authentication Plugin
================================================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/basilicom/pimcore-plugin-document-authentication/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/basilicom/pimcore-plugin-document-authentication/?branch=master)
[![Codacy Badge](https://www.codacy.com/project/badge/99428c40b3214dc4a68c43ca502ff6f9)](https://www.codacy.com/public/christophluehr_3288/pimcore-plugin-document-authentication)
[![Build Status](https://scrutinizer-ci.com/g/basilicom/pimcore-plugin-document-authentication/badges/build.png?b=master)](https://scrutinizer-ci.com/g/basilicom/pimcore-plugin-document-authentication/build-status/master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/abcbcad7-4642-4882-a234-43321c705d45/mini.png)](https://insight.sensiolabs.com/projects/abcbcad7-4642-4882-a234-43321c705d45)
[![Dependency Status](https://www.versioneye.com/user/projects/54c9071ea888b903fa000002/badge.svg?style=flat)](https://www.versioneye.com/user/projects/54c9071ea888b903fa000002)

Developer info: [Pimcore at basilicom](http://basilicom.de/en/pimcore)

## Synopsis

This Pimcore <http://www.pimcore.org> plugin adds a
front controller plugin to enable selective HTTP Basic Authentication
on a per-document basis via document properties.

## Code Example / Method of Operation

Just enable the plugin, adapt the website properties (username and password)
in the website settings and add the predefined property to a document in
the document tree.

## Motivation

An existing website might be extended with new document pages which
should be inaccessible to ordinary users for preview. The built-in
HTTP Basic Authentication feature of Pimcore is global. This
plugin enables password protection on a document level.

## Installation

Add "basilicom-pimcore-plugin/document-authentication" as a requirement to the
composer.json in the toplevel directory of your Pimcore installation.

Example:

    {
        "require": {
            "basilicom-pimcore-plugin/document-authentication": ">=1.0.0"
        }
    }

## API Reference

* n/a

## Tests

* none

## Contributors

* Conrad Guelzow <conrad.guelzow@basilicom.de>

## License

* BSD-3-Clause
