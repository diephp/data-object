
# DataObject

Simple object to be used as a DTO/MODEL/ValueObject.

## Overview

The `DataObject` class is a versatile data container designed for use as a Data Transfer Object (DTO), Model, or ValueObject. It can be initialized with an associative array and provides various methods for manipulating and accessing data. This class implements both the `\JsonSerializable` and `ArrayAccess` interfaces.

## Features

- Initialize with an associative array
- Implements `\JsonSerializable` and `ArrayAccess` interfaces
- Supports dot notation for nested data access
- Provides methods for merging, filtering, mapping, and transforming data
- Automatically converts objects to arrays if they implement a `toArray` method
- Various utility methods like `hash`, `flatten`, `collapse`, `clone`, etc.

## Installation

Install the package via Composer:

```bash
composer require diephp/dataobject
```

Or manually add it to your `composer.json`:

```json
"require": {
    "diephp/dataobject": "^1.0.0"
}
```

## Usage

### Initialization

Create a new instance of `DataObject`:

```php
use DiePHP\DataObject;

$data = new DataObject([
    'name' => 'John Doe',
    'email' => 'john.doe@example.com'
]);
```

Or use the static `of` method:

```php
$data = DataObject::of([
    'name' => 'John Doe',
    'email' => 'john.doe@example.com'
]);
```

### Accessing Data

Access data using property notation or array access:

```php
echo $data->name; // John Doe
echo $data['email']; // john.doe@example.com
```

Check if a key exists:

```php
if ($data->has('name')) {
    // Key exists
}
```

### Manipulating Data

Set a value:

```php
$data->set('name', 'Jane Doe');
```

Get a value with a default:

```php
$email = $data->get('email', 'default@example.com');
```

Merge new data:

```php
$data->merge([
    'address' => '123 Main St',
    'phone' => '123-456-7890'
]);
```

Filter data:

```php
$data->filter(function ($value, $key) {
    return !empty($value);
});
```

Map data:

```php
$mappedData = $data->map('*', function ($value) {
    return strtoupper($value);
});
```

### Utility Methods

Convert to array:

```php
$array = $data->toArray();
```

Convert to JSON:

```php
$json = json_encode($data);
```

Get the MD5 hash of the data:

```php
$hash = $data->hash();
```

Clone the object:

```php
$clone = $data->clone();
```

### Example

```php
use DiePHP\DataObject;

$data = new DataObject([
    'user' => [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com'
    ]
]);

echo $data->get('user.name'); // John Doe

$data->set('user.address', '123 Main St');
echo $data->get('user.address'); // 123 Main St

$data->merge(['user.phone' => '123-456-7890']);
echo $data->get('user.phone'); // 123-456-7890

$data->filter(function ($value, $key) {
    return !empty($value);
});

echo $data->hash(); // MD5 hash of the data

echo json_encode($data); // JSON representation
```

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
