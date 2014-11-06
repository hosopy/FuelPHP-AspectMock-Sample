# FuelPHP-AspectMock-Sample

2014/11/07 WEBチーム勉強会で利用するサンプルコードです。

## Requirements

* PHP 5.4以上

## Setup

```sh
$ git clone https://github.com/hosopy/FuelPHP-AspectMock-Sample.git
$ cd FuelPHP-AspectMock-Sample
$ php composer.phar install
$ FUEL_ENV=test php oil r migrate
$ php oil test --group=Api
```
