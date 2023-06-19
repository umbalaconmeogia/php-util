# Development note

## PHPUnit

### Installation

There are at least two ways of installing PHPUnit.
1. Via composer
    ```shell
    composer require phpunit/phpunit
    ```
    Confirm phpunit
    ```shell
    ./vendor/bin/phpunit --version
    PHPUnit 9.5.21
    ```
2. Download [PHPUnit phar file](https://phar.phpunit.de/phpunit-9.phar), rename it to *phpunit* and save to a folder that you can access
    Confirm phpunit
    ```shell
    phpunit --version
    PHPUnit 9.5.21
    ```

### Running test

(Incase install phpunit into vendor directory via composer)

#### Running all test cases
```shell
./vendor/bin/phpunit tests/NumberSystemTest.php
```

#### Running specified test case
```shell
./vendor/bin/phpunit
```

### References

* [PHPUnit](https://phpunit.de/)
* [PHPUnit Manual](https://phpunit.readthedocs.io/en/9.5/)
* [Creating a Composer Library with PHPUnit and TDD](https://engagedphp.com/2018/03/creating-a-composer-library/)