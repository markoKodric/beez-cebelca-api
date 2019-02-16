# Beez

Beez allow you to create invoices and inventory sales.

## Installation
Require this package with [composer](http://getcomposer.org).
`composer require mare06xa/beez --dev`
Laravel 5.5+ uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

## Laravel 5.4:
If you don't use auto-discovery, add the ServiceProvider to the providers array in `config/app.php`
`Mare06xa\Beez\BeezServiceProvider::class`

## Usage

***Customer***
| Key | Description | Required |
|------|-----|-----|
| name   | Company or person's name | - [x] |
| street | Company or person's street address | - [x] |
| postal | Company or person's postal code | - [x] |
| city   | Company or person's city | - [x] |
| country | Company or person's country | - [] |

```
$beezAPI = new Beez();

$beezAPI->insertCustomer([
    'name'    => 'Name Surname',
    'street'  => 'Test Street 42',
    'postal'  => '1000',
    'city'    => 'Test City',
    'country' => 'Test Country'
])
```
