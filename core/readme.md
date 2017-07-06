# Installation

## Server
Landing Framework requires a LAMP stack to run on. For this manual we use a digitalocean.com VPS with serverpilot.io automated updates and SSL.

## Crontabs
Each system user has their own list of scheduled tasks. This list is called a crontab. To view a crontab, SSH in to your server and run the command:

`crontab -l`

To edit a system user's crontab, run the command:

`crontab -e`

To add the Laravel cron job that makes a web request every minute, scroll to the bottom of the file and add the line:

`* * * * * php /srv/users/serverpilot/apps/name_of_your_app/public/core/artisan schedule:run >> /dev/null 2>&1`

For more information on crontabs, consult the [ServerPilot crontabs documentation](https://serverpilot.io/community/articles/how-to-use-cron-to-schedule-scripts.html).

## Queues
In development mode you can listen to queues with the following command:

`php artisan queue:work`

If you make changes to the job, run:

`php artisan queue:restart`

For production you need to set up Supervisor as described below.

### Installing Supervisor
Supervisor is a process monitor for the Linux operating system, and will automatically restart your `queue:work` process if it fails. To install Supervisor on Ubuntu, you may use the following command:

`sudo apt-get install supervisor`

### Configuring Supervisor
Supervisor configuration files are typically stored in the `/etc/supervisor/conf.d` directory. Within this directory, you may create any number of configuration files that instruct supervisor how your processes should be monitored. For example, let's create a `name_of_your_app.conf` file that starts and monitors a `queue:work` process:

```[program:name_of_your_app]
process_name=%(program_name)s_%(process_num)02d
command=php /srv/users/serverpilot/apps/name_of_your_app/public/core/artisan queue:work database --sleep=3 --tries=3
autostart=true
autorestart=true
user=serverpilot
numprocs=8
redirect_stderr=true
stdout_logfile=/srv/users/serverpilot/apps/name_of_your_app/supervisor.log``` 

In this example, the `numprocs` directive will instruct Supervisor to run 8 `queue:work` processes and monitor all of them, automatically restarting them if they fail. Of course, you should change the `queue:work sqs` portion of the `command` directive to reflect your desired queue connection.

### Starting Supervisor
Once the configuration file has been created, you may update the Supervisor configuration and start the processes using the following commands:

`sudo supervisorctl reread`

`sudo supervisorctl update`

`sudo supervisorctl start name_of_your_app:*`
`sudo supervisorctl start 0landingframework:*`

For more information on Supervisor, consult the [Supervisor documentation](http://supervisord.org/index.html).

### Troubleshooting
You can try to restart the service with this command:

`service supervisor restart`

## Mailgun webhooks
To track email clicks and opens, we have to configure Mailgun webhooks. Log in to your Mailgun account, click *Webhooks* and enter the following URL for all events you want to track:

`https://try.landingframework.com/ec/mg/event`

## Based on Laravel

Laravel has the most extensive and thorough documentation and video tutorial library of any modern web application framework. The [Laravel documentation](https://laravel.com/docs) is thorough, complete, and makes it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 900 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.