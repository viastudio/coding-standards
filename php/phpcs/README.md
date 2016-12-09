# About

This is a set of [custom sniffs](http://pear.php.net/manual/en/package.php.php-codesniffer.coding-standard-tutorial.php) and [ruleset](http://pear.php.net/manual/en/package.php.php-codesniffer.annotated-ruleset.php) which make up our `VIA` coding standard.

We currently use `PSR-2` as our base ruleset with minor whitespace changes to match our coding style and widen support for non-PHPFIG frameworks.

# Requirements

- [Composer](https://getcomposer.org/download/)
- [phpcs](https://github.com/squizlabs/PHP_CodeSniffer) (Composer Install)

# Installation

Make sure `export PATH="$PATH:$HOME/.composer/vendor/bin"` has been added to your `.bash_profile` or `.bashrc` file

After installing the PHP CodeSniffer, you can create symlinks to each of the directories under `Standards/`.

You can navigate to the default standards directory with the following commands:

	cd ~/.composer/vendor/squizlabs/php_codesniffer/CodeSniffer/Standards

Within this directory are the default standards installed with PHP CodeSniffer. Add symlinks for *each* of the directories in our `Standards/`. For example:

	ln -s /path/to/cloned/coding-standards/php/phpcs/Standards/Via Via

# Usage

To run PHP CodeSniffer using our standard, use the following command:

    phpcs -ns --standard=Via path_or_file_to_check

