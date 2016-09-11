# Esqueleto - PHP MVC skeleton

Esqueleto is a skeleton PHP MVC application.<br>
It aims to be simple and fast with a good structure.<br>
Ideal to do rapid prototyping.

*This is a work in progress*

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development.

### Prerequisities

+ Local server with PHP and MySQL
+ [NodeJs](https://nodejs.org/en/)
+ [GruntJs](http://gruntjs.com/)
+ [Composer](https://getcomposer.org/)


### Installing

#### Windows environment

Edit the file with admin priviledges  `C:\Windows\System32\drivers\etc\hosts`<br>
Add a new line with:
```
127.0.0.1  esqueleto.dev
```

On apache server, uncomment the line from the file `apache\apache2.4.18\conf\httpd.conf`

```
Include conf/extra/httpd-vhosts.conf
```

On the httpd-vhosts file add

```
<VirtualHost *:80>
    ServerName esqueleto.dev
    DocumentRoot C:/wamp64/www/dev/esqueleto/public
    <Directory  "C:/wamp64/www/dev/esqueleto/public/">
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require local
    </Directory>
</VirtualHost>
```

Edit `DocumentRoot` and `Directory` accordingly (point to where you put the project inside the server)<br>

This means that, everytime `esqueleto.dev` is called in the browser, it will load the index file inside the `DocumentRoot` folder

From the command prompt, navigate to the root of the project and execute:
```
npm install
```
```
composer update
```
```
grunt
```

Import the db located at `data/db_esqueleto.zip`

## Usage

+ Edit configs in `config/development.php`
+ Routes are defined in the db
+ If you add a new controller, update the `public/index.php`

## Deployment

In the `config` folder there are two files, one for development and other for production. In `public/index.php` change the mode you want
```
define('APP_MODE', 'development');
```

## Built With

* [klein router](https://github.com/klein/klein.php)
* [Monolog](https://github.com/Seldaek/monolog)
* [PDO](http://php.net/manual/en/book.pdo.php)

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D  

## Authors

* **Gonçalo Gonçalves** - *Initial work* - [http://goncalogoncalves.com/](http://goncalogoncalves.com/)

## License

This project is licensed under the Apache License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Thank you to all the people that was involved in one way or another
* Inspiration from [Slim framework](http://www.slimframework.com/) and [Mini](https://github.com/panique/mini)
* If you find any bugs, please report them.
