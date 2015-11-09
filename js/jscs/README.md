# About

This `jscs` standard is loosely based on the Google preset with some VIA Studio specific customizations. This roughly lines up with our [phpcs](php/phpcs) standard while making some exceptions due to language differences.

# Requirements

- [npm](https://www.npmjs.com)
- [jscs](http://jscs.info)

# Installation

    sudo npm install -g jscs # you must install this globally
    ln -s /path/to/coding-standards/js/jscs/via.json ~/.jscsrc

# Usage

To run JSCodeSniffer using our standard, use the following command:

    jscs path_or_file_to_check

