#Install

1. Manually copy the `MauticVocativeBundle` directory into your Mautic `plugins` directory.
 - for example `/var/www/mautic/plugins/MauticVocativeBundle`.
2. Clear Mautic cache by `./app/console cache:clear` or just delete the `app/cache` dir.
3. Log in to your Mautic as an admin, open cogwheel menu in the right top corner and choose *Plugins*
4. Click *Install/Upgrade Plugins*
 - if everything goes well, you got new plugin *FOMVocative*.

#Usage
In your Mautic insert into an email template this shortcode around *some name*
`[some name|vocative]`
- for example `[Karel|vocative]`
- or better example `[{leadfield=firstname}|vocative]`  
hint: use `CTRL+SHIFT+V` to insert copied text without formatting, also check source code of your email template by
![Mautic source code icon](https://github.com/jaroslavtyc/mautic-bundle-skeleton/blob/master/mautic/app/bundles/CoreBundle/Assets/js/libraries/ckeditor/plugins/sourcedialog/icons/sourcedialog.png)
button for unwanted formatting

## Where errors dwell
 If any error happens, first of all, have you **cleared the cache**?
 
 Otherwise check the logs for what happened:
 
 1. they are placed in app/logs dir in your Mautic, like `/var/www/mautic/app/logs/mautic_prod-2016-02-19.php`
 2. or, if they are more fatal or just Mautic does not catch them (error 500), see your web-server logs, like `/var/log/apache2/error.log`

#Known issues
If a name ends by non-character (sadly including whitespaces), the *E* suffix is added.
`[Who am I?|vocative]` = `Who am I?E`
 - on the other hand, also foreign names are converted to czech form
 `[Cassandra|vocative]` = `Cassandro`, `[android|vocative]` = `Androide`

# Credits
The plugin has been created thanks to sponsor [svetandroida.cz](https://www.svetandroida.cz/)
and thanks to the author of free czech vocative library [`bigit/vokativ`](https://bitbucket.org/bigit/vokativ.git) Petr Joachim.
