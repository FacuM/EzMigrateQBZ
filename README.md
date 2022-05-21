EzMigrate for Qobuz
===================

**EzMigrate for Qobuz** is a CLI application that will help you migrate your favorites between Qobuz accounts.

# About

This software was designed just for fun, playing around with external APIs and as a learning exercise. I won't provide any kind of pro-level support for this software and you shouldn't expect it to work for long, since Qobuz's API could change at any time without prior notice.

# Features

- [X] Full favorites dump

    Dump all of your favorite albums, tracks and artists into RAW JSON files that you can use later on to import your data back into a new Qobuz account. 

# Requirements

- PHP 7.4 or greater
- cURL extension for PHP

# Usage

- Run the export script: `php export.php`, you'll be asked for your e-mail (or username) and password. Type the credentials of your old account.
- Wait for the dump to complete.
- Now, run the import script: `php import.php`, you'll be asked for your e-mail (or username) and password. Type the credentials of your new account.
- Wait for the import to complete.
- That's it! Your favorites should now be available in your new account.

# Disclaimer

Please take into consideration that I'm not affiliated in any way with the Qobuz company. All this code has been written through experimentation, public documentation found online and reverse engineering of network requests found in the Android app with no intent to cause any damages.

If you feel like this isn't the case, feel free to file a takedown request.

# License

This project is licensed under the terms of [The Unlicense](LICENSE).