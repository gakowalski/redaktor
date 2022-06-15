# Redaktor

Very basic, self-hosted IDE to quickly edit code on dev environments while no better means of work are possible.

## Installation & Usage

1. Copy anywhere on the webserver and download submodules: `git submodule init`.
2. Copy `config.sample.php` to `config.php` and change `security_password_hash` value to SHA256 hash of password you want to use.
3. Edit `config.php` and change `path_limit` so you won't go outside of your dev folder.
3. You're ready: open `index.php` in web browser with `pass` parameter equal to your password.

Now you'll see simple directory listing. Click on folders or on breadcrumbs-heading to change dir. Click on files to go into edit mode.

## Features

* [ACE code editor](https://ace.c9.io/) (syntax highlighting, indent/outdent, highlight matching parentheses, live syntax checker for some languages etc.)
* Automatic PHP syntax check.
* Directory listing with writable/non-writable info about files.
