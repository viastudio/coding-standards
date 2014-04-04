# About

VIA Studio uses the git pre-commit hook to ensure code can not be committed without meeting our coding standards.

The pre-commit hook checks files within the commit. Depending on the type of the file, it must pass its respective validation.

## PHP
PHP must pass the PHP linter (`php -l`) as well as our `Via` PHP CodeSniffer ruleset without *errors*.

## JavaScript
JavaScript must pass `jshint`.

## CSS
CSS must pass `csslint` without *errors*.

# Installation

You can create a symlink to our pre-commit hook from within your git repository.

	cd .git/hooks/
	ln -s /path/to/cloned/coding-standards/git/hooks/pre-commit

