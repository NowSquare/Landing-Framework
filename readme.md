# Installation

We recommend [DigitalOcean](https://m.do.co/c/481d60856a31) for hosting and [ServerPilot](https://www.serverpilot.io/?refcode=4f2ccbd3eae6) for automated security and SSL.

Installation instructions are included with the script.

## Prerequisites
For installation you need accounts for all of the services below. Currently only [mailgun.com](https://www.mailgun.com/) is supported for sending mails.

 - [digitalocean.com](https://m.do.co/c/481d60856a31)
 - [serverpilot.io](https://www.serverpilot.io/?refcode=4f2ccbd3eae6)
 - [mailgun.com](https://www.mailgun.com/)

## VPS
After you've deployed a DigitalOcean Ubuntu VPS and connected this to your ServerPilot account, create an app with your ServerPilot account. Also, create a database and write the credentials down somewhere.

## Install files
Log in to your VPS and go to the directory of the app you've created with ServerPilot. For example:

``` bash
$ cd /srv/users/serverpilot/apps/name_of_your_app/public
```

If you have purchased the Landing Framework with the JavaScript and CSS build files, clone this Git repository:

``` bash
$ git clone https://gitlab.com/NowSquare/Landing-Framework.git .
```

If you have purchased the regular Landing Framework license, clone this Git repository:

``` bash
$ git clone https://gitlab.com/NowSquare/Landing-Framework-Build.git .
```

> Make sure you copy the dot (.) at the end. It will tell the Git command to clone to the current directory.

All files will be downloaded to the `public` folder now.

## Config file
In the `core` directory, there is an example config file `.env.example`. Copy this file to `.env` and open it to edit:
``` bash
$ cd core
$ cp .env.example .env
$ sudo nano .env
```

In the config file you can enter the database credentials set up earlier. Set up a domain in your [mailgun.com](https://www.mailgun.com/) account and enter the details at the mail settings. Also, make sure you enter the correct `APP_URL`.

Get your [Google Maps key](https://developers.google.com/maps/documentation/javascript/get-api-key), and enter it at the `GMAPS_KEY` parameter. It should look something like this:

``` 
GMAPS_KEY="AIzuSyTkg4y-D0LT5gvUWFCgmCoSiMJq-Tf1JwM"
``` 

## Generate and seed database
Once the config file database settings are configured, you can open the url where you've installed the framework. The database will be generated automatically and you can login with `info@example.com` and the password `welcome`.

## Crontabs
Each system user has their own list of scheduled tasks. This list is called a crontab. To edit a system user's crontab, run the command:
``` bash
$ crontab -e
```

To add the Laravel cron job that makes a web request every minute, scroll to the bottom of the file and add the line (replace `name_of_your_app` with your ServerPilot app name):
``` bash
* * * * * php /srv/users/serverpilot/apps/name_of_your_app/public/core/artisan schedule:run >> /dev/null 2>&1
```

That's it. For more information on crontabs, consult the [ServerPilot crontabs documentation](https://serverpilot.io/community/articles/how-to-use-cron-to-schedule-scripts.html).

## Queues
If a mail or mailing is sent by the system, this is done with a queue so there is no delay for the user and long tasks aren't interrupted when a user visits another page.

### Installing Supervisor
Supervisor is a process monitor for the Linux operating system, and will automatically restart your `queue:work` process if it fails. To install Supervisor on Ubuntu, you may use the following command:
``` bash
$ sudo apt-get install supervisor
```

### Configuring Supervisor
Supervisor configuration files are typically stored in the `/etc/supervisor/conf.d` directory. Within this directory, you may create any number of configuration files that instruct supervisor how your processes should be monitored. For example, let's create a `name_of_your_app.conf` file that starts and monitors a `queue:work` process:

```
[program:name_of_your_app]
process_name=%(program_name)s_%(process_num)02d
command=php /srv/users/serverpilot/apps/name_of_your_app/public/core/artisan queue:work database --sleep=3 --tries=3
autostart=true
autorestart=true
user=serverpilot
numprocs=8
redirect_stderr=true
stdout_logfile=/srv/users/serverpilot/apps/name_of_your_app/supervisor.log
``` 

In this example, the `numprocs` directive will instruct Supervisor to run 8 `queue:work` processes and monitor all of them, automatically restarting them if they fail. Of course, you should change the `queue:work sqs` portion of the `command` directive to reflect your desired queue connection.

### Starting Supervisor
Once the configuration file has been created, you may update the Supervisor configuration and start the processes using the following commands:

``` bash
$ sudo supervisorctl reread
$ sudo supervisorctl update
$ sudo supervisorctl start name_of_your_app:*
```

For more information on Supervisor, consult the [Supervisor documentation](http://supervisord.org/index.html).

### Troubleshooting
If queues are not triggered, restart the service with this command:

``` bash
$ service supervisor restart
```

## Mailgun webhooks
To track email clicks and opens, we have to configure Mailgun webhooks. Log in to your Mailgun account, click `Webhooks` and enter the following URL for all events except `Spam Complaints` and `Unsubscribes` (replace `app.example.com` with your domain):

`https://app.example.com/ec/mg/event`

## First login

When the framework is installed, you can login with the following credentials:

**E-mail:** info@example.com

**Password:** welcome