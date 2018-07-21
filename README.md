# Districts

Districts is an application that gets data about districts (area and population). App loads data from two websites: [Gdańsk](https://www.gdansk.pl/) and [Kraków](http://www.bip.krakow.pl/) using cURL Multi, parses it, analyzes and saves it to the database. 

User can actualize, create, read, update, delete, sort and filter districts data.

Loading the data about 54 districts takes only 1 second.

It is possible to run the application and actualize all districts data using command line.
 
## Technologies

* PHP
* Composer
* cURL Library
* DOM Document
* PDO
* MySQL
* AJAX

## Usage

```php
/** 
* Create database with sql.sql file
* Add your database credentials to core/config.php
* Generate the vendor/autoload.php file using: composer dump-autoload
* Document Root is in public directory
**/
 
// To actualize data use console command
php public/index.php actualize
```

## License
[MIT](https://choosealicense.com/licenses/mit/)
