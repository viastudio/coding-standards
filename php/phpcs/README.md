# About

This is a set of [custom sniffs](http://pear.php.net/manual/en/package.php.php-codesniffer.coding-standard-tutorial.php) and [ruleset](http://pear.php.net/manual/en/package.php.php-codesniffer.annotated-ruleset.php) which make up our `VIA` coding standard.

We currently use `PSR-2` as our base ruleset with minor whitespace changes to match our coding style and widen support for non-PHPFIG frameworks.

# Requirements

- [PEAR](http://pear.php.net/manual/en/installation.getting.php)
- [phpcs](https://github.com/squizlabs/PHP_CodeSniffer) (PEAR Install)

# Installation

After installing the PHP CodeSniffer, you can create symlinks to each of the directories under `Standards/`.

You can navigate to the default standards directory with the following commands:

	cd `pear config-get php_dir`
	cd PHP/CodeSniffer/Standards/

Within this directory are the default standards installed with PHP CodeSniffer. Add symlinks for *each* of the directories in our `Standards/`. For example:

	ln -s /path/to/cloned/coding-standards/php/phpcs/Standards/Via Via

