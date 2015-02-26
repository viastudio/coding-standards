## About

This configures Sublime Text to work with our coding standards and provide real-time feedback.

## Requirements

### Sublime Text 3
- [SublimeLinter](https://github.com/SublimeLinter/SublimeLinter3)
- [SublimeLinter-php](https://github.com/SublimeLinter/SublimeLinter-php)
- [SublimeLinter-phpcs](https://github.com/SublimeLinter/SublimeLinter-phpcs)
- [SublimeLinter-jshint](https://github.com/SublimeLinter/SublimeLinter-jshint)
- [SublimeLinter-css](https://github.com/SublimeLinter/SublimeLinter-csslint)
- [SublimeLinter-jscs](https://github.com/SublimeLinter/SublimeLinter-jscs)

### Sublime Text 2
- [SublimeLinter](https://github.com/SublimeLinter/SublimeLinter-for-ST2) (no longer supported by devs, recommend using ST3 instead)
- [Phpcs](https://github.com/benmatselby/sublime-phpcs)

## Installation

**You must have phpcs, jscs, jshint, and css installed locally & have set up our coding standard before doing this.**

If you don't have [Package Control](https://sublime.wbond.net/), install it.

### Sublime Text 3

Install the list of ST3 packages above using Package Control. Restart Sublime.

Open `st3/SublimeLinter.sublime-settings` in this folder and copy it into your SublimeLinter user settings located in:
* OS X: `~/Library/Application Support/Sublime Text 3/SublimeLinter.sublime-settings`.

These settings will lint with our coding standard and provide underlining of problematic code. Placing your cursor in non-standard code will show the specific issue in the status bar on the bottom of the window. If you'd prefer a list of errors on save, set `"show_errors_on_save": true`.

You can further customize your linter with [these options](http://sublimelinter.readthedocs.org/en/latest/global_settings.html).

### Sublime Text 2

Install the list of ST2 packages above using Package Control. Restart Sublime. SublimeLinter for ST2 bundles csslint & jshint but doesn't support phpcs, so we need to use an additional package. Restart Sublime.

Open `st2/phpcs.sublime-settings` in this folder and copy it into your sublime-phpcs user settings located in:
* OS X: '~/Library/Application Support/Sublime Text 2/Packages/User/phpcs.sublime-settings`

sublime-phpcs doesn't support realtime linting, but will run every time you save a file.
