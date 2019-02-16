# Beez

Beez allow you to create invoices and inventory sales.

## Installation
Require this package with [composer](http://getcomposer.org).

```
composer require mare06xa/beez --dev
```

Laravel 5.5+ uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

## Laravel 5.4:
If you don't use auto-discovery, add the ServiceProvider to the providers array in `config/app.php`

```
Mare06xa\Beez\BeezServiceProvider::class
```

## Usage

***Insert the customer (partner)***

- If the partner is already in the database it returns it's ID
- If it's not it adds it and returns it's ID

| Key | Description | Required |
|------|-----|-----|
| name   | Company or person's name | Yes |
| street | Company or person's street address | Yes |
| postal | Company or person's postal code | Yes |
| city   | Company or person's city | Yes |
| country | Company or person's country | No |

```
$beezAPI = new Beez();

$beezAPI->insertCustomer([
    'name'    => 'Name Surname',
    'street'  => 'Test Street 42',
    'postal'  => '1000',
    'city'    => 'Test City',
    'country' => 'Test Country'
]);
```


***Add the Invoice head***

Invoice consists of invoice head and multiple invoice body lines. 
First you add the Invoice head and get the ID of added invoice.

| Key | Description | Format | Required |
|------|-----|-----|----|
| date_sent   | Date when invoice was issued | dd.mm.yyyy | Yes |
| date_served | Date when service or item was delivered | dd.mm.yyyy | Yes |
| date_to_pay | Date to which invoice should be payed | dd.mm.yyyy | Yes |
| date_payed  | Date when invoice was paid | dd.mm.yyyy | No |
| payment | Mark that invoice has been paid | | No |

```
$beezAPI = new Beez();

$beezAPI->insertHead([
    'date_sent'   => Carbon::now()->format("d.m.Y"),
    'date_served' => Carbon::now()->format("d.m.Y"),
    'date_to_pay' => Carbon::now()->addDay()->format("d.m.Y"),
    'date_payed'  => Carbon::now()->addDay()->format("d.m.Y"),
    'payment'     => 'paid',
]);
```
