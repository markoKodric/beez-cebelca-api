# Beez

Beez allow you to create invoices and inventory sales.

## Installation
Require this package with [composer](http://getcomposer.org).

```
composer require mare06xa/beez --dev
```

Laravel 5.5+ uses **Package Auto-Discovery**, so doesn't require you to manually add the ServiceProvider.

**Laravel 5.4 and below:**
If you don't use auto-discovery, add the ServiceProvider to the providers array in `config/app.php`

```php
Mare06xa\Beez\BeezServiceProvider::class
```

## Configuration
You will need API token from Cebelca BIZ.
- Go to **https://www.cebelca.biz/**, sign up and confirm your account
- Sign in and go to Settings (Nastavitve) in top right corner
- Click "Access settings" (Nastavitve dostopa) in top left corner
- On the bottom you will find "API Access" (API dostop) section
- Click the button to activate your API access and get the API token

Add this lines to your `.env` configuration file

```
BIZ_TOKEN={Insert your token here}
BIZ_DOMAIN=https://www.cebelca.biz
BIZ_DEBUG=false
```

---

## Usage

***Registering location***

Before you can fiscalize invoices you need to register location with Tax Office.  
If you want to TEST fiscalize invoices you need to register location to TEST FURS server.

You can do this in Cebelca web interface too. This is the way to do it with API. This way you get and ID of location automatically.

**You don't need to use this if you already have the location.**

| Key          | Description                                                | Format | Required |
|--------------|------------------------------------------------------------|----------------|----------|
| type         | Location type (A: Movable object (car, taxi, ...); B: Fixed address; C: Electronic device) | String | Yes |
| location_id  | Internal id of location (you determine it, must be unique) | String | Yes      |
| register_id  | Internal id of register (you determine it, must be unique) | String | Yes      |
| test_mode    | Register with test or real FURS server                     | 0/1    | No       |

***Insert the customer (partner)***

- If the partner is already in the database it returns it's ID
- If it's not it adds it and returns it's ID

| Key     | Description                        | Format         | Required |
|---------|------------------------------------|----------------|----------|
| name    | Company or person's name           | String         | Yes      |
| street  | Company or person's street address | String         | Yes      |
| postal  | Company or person's postal code    | String/Integer | Yes      |
| city    | Company or person's city           | String         | Yes      |
| country | Company or person's country        | String         | No       |

---

***Add the Invoice head***

**Option 1**:

Invoice consists of invoice head and multiple invoice body lines. 
First you add the Invoice head and get the ID of added invoice.

| Key         | Description                             | Format / Options | Required |
|-------------|-----------------------------------------|-------------------|----------|
| date_sent   | Date when invoice was issued            | Date (dd.mm.yyyy) | Yes      |
| date_served | Date when service or item was delivered | Date (dd.mm.yyyy) | Yes      |
| date_to_pay | Date to which invoice should be payed   | Date (dd.mm.yyyy) | Yes      |
| date_payed  | Date when invoice was paid              | Date (dd.mm.yyyy) | No       |
| payment     | Mark that invoice has been paid         | String ["paid"]   | No       |


**Option 2**:

Use ***withMoreOptions*** parameter in function.

*Additional options*:

| Key             | Description                                                      | Format         | Required |
|-----------------|------------------------------------------------------------------|----------------|----------|
| taxnum          | Can be used instead of **insertCustomer** function               | String/Integer | No       |
| id_document_ext | Overrides the ID of invoice sent by Cebelca **(must be unique)** | String/Integer | No       |
| id_currency     | Foreign currency ID (default currency is EUR)                    | String/Integer | No       |
| conv_rate       | Conversion rate between foreign curreny and EUR                  | String/Float   | No       |

---

***Add the invoice body***

Adds one/multiple items to the invoice.

| Key      | Description                             | Format | Required |
|----------|-----------------------------------------|--------|----------|
| title    | Name or description of the service/item | String | Yes      |
| qty      | Quantity of items                       | Float  | Yes      |
| mu       | Measuring unit (hour/piece/kg/...)      | String | Yes      |
| price    | Price per unit                          | Float  | Yes      |
| vat      | Value Added Tax                         | Float  | Yes      |
| discount | Discount in percentage                  | Float  | No       |

---

***Add payment***

| Key               | Description                                              | Format            | Required |
|-------------------|----------------------------------------------------------|-------------------|----------|
| date_of           | Date of payment                                          | Date (dd.mm.yyyy) | Yes      |
| amount            | Total amount paid (calculated automatically if left out) | Float             | No       |
| id_payment_method | Payment method ID (1: Credit card, 2: Cash)              | Integer           | Yes      |

---

***Fiscalize invoice***

In Slovenia you need to fiscalize (send to Tax office) all "Cash" invoices in realtime.  
Invoice is considered "Cash" invoice when it is not paid by direct transaction to your bank account (wire transfer) or PayPal.

- Non "Cash" payments **CAN** be fiscalized
- "Cash" payments **MUST** be fiscalized.

| Key         | Description                                                                          | Format    | Required |
|-------------|--------------------------------------------------------------------------------------|----------------|-----|
| id_location | Fiscal invoice predefined location. Location must be registered at Tax office        | String/Integer | Yes |
| fiscalize   | Determine if invoice is fiscalized                                                   | 0/1            | No  |
| op-tax-id   | Personal tax ID of the person/company issuing an invoice                             | String         | Yes |
| op-name     | Operators name that is printed on invoice                                            | String         | Yes |
| test_mode   | Fiscalizes to **TEST** server. Location must be registered at TEST Tax office server | 0/1            | Yes |

---

***Generate PDF of an invoice***

Get the binary data of PDF from the server.

Parameters:
- Path to storage folder
- Document title

---

## Examples

- Registering location
```php
$beezAPI = new Beez();

$beezAPI->addLocation([
    'type'        => 'C',
    'location_id' => 'UniqueLocation1245',
    'register_id' => 'UniqueRegister1254',
    'test_mode'   => 1
]);

echo $beezApi->getLocation();
```

- Basic making of an invoice and generating PDF
```php
$beezAPI = new Beez();

$beezAPI->insertCustomer([
    'name'    => 'Name Surname',
    'street'  => 'Test Street 42',
    'postal'  => '1000',
    'city'    => 'Test City',
    'country' => 'Test Country'
])->insertHead([
    'date_sent'   => Carbon::now()->format("d.m.Y"),
    'date_served' => Carbon::now()->format("d.m.Y"),
    'date_to_pay' => Carbon::now()->addDay()->format("d.m.Y"),
    'date_payed'  => Carbon::now()->addDay()->format("d.m.Y"),
    'payment'     => 'paid',
])->insertItems([
    [
        'title' => "Item 1",
        'qty'   => 1,
        'mu'    => 'kg',
        'price' => 12,
        'vat'   => 22
    ],
    [
        'title' => "Service 1",
        'qty'   => 1.24,
        'mu'    => 'piece',
        'price' => 42.5,
        'vat'   => 9.5,
        'discount' => 10.5
    ]
])->insertPayment([
    'date_of' => Carbon::now()->addDay()->format("d.m.Y"),
    'amount'  => 54.5,
    'id_payment_method' => Payment::CREDIT_CARD,
])->fiscalizeInvoice([
    'id_location' => 12,
    'fiscalize'   => 1,
    'op-tax-id    => '123456789',
    'op-name'     => 'Operator',
    'test_mode'   => 1
])->generatePDF('path/to/pdf/', 'PDF Title');
```
