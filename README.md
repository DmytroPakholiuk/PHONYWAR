<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


## About 

This one is a test task for company <i>"E-Chat"</i> 

## Installation

### Linux

<p>
So, if you have Linux, be sure to have docker and docker-compose installed.
Then you should make sure that ports <code>20080, 19000, 16379</code> are 
available. If not, feel free to change default exposed ports in <code>.env</code>
</p>

<p>
Then simply clone the repository, give the <code>install.sh</code> script
the rights to be executed and run it. It will take care of deployment with 
docker-compose and whatnot.
</p>
<p>
After installation the project will be set up at http://phonywars.com:20080
</p>

### Windows

<p>
I don't have a script for deployment on Windows, so if you wish to use that
OS, you will have to do all the stuff manually. I don't guarantee it will go
very well in that case. Anyway, you will have to:
</p>
<ol>
    <li>Copy <code>.env.example</code> to <code>.env</code></li>
    <li>
        In Linux you would have to <code>chmod -R 777 storage</code>, 
        which was not a very sane thing to do, but if you get 
        errors regarding denied access to storage, you will have to deal 
        with that.
    </li>
    <li>
        Create a Docker network for the project: 
        <code>docker network create --gateway 130.10.2.1 --subnet 130.10.2.0/24 phonywar_network</code>
    </li>
    <li>Run <code>docker-compose up -d</code></li>
    <li>
        Generate the Laravel encryption key (optional): 
        <code>docker exec phonywar_php bash -c "php artisan key:generate"</code>
    </li>
    <li>
        Install composer packages
        <code>docker exec phonywar_php bash -c "composer install"</code>
    </li>
    <li>
        In your <i>"C:/Windows/System32/Drivers/etc/hosts"</i> file
        (local DNS registry) add a line with 
        <code>127.0.0.1     phonywar.com</code>
    </li>
</ol>
<p>
After installation the project will be set up at http://phonywars.com:20080
</p>

## Configuration

If you wish to reconfigure the project, the best way to do so would be by 
altering your <code>.env</code> file and not by tinkering with Laravel configs.
It is because the <code>.env</code> variables are used in both Laravel app and
when deploying project in docker-compose, providing better integrity.
