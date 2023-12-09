![PHP Array Dot Notation](https://sajadsdi.github.io/images/array-dot-notation.png)


# Array Dot Notation
Array Dot Notation is a high-performance and lightweight PHP library that allows you to set and get values in arrays using dot notation. It simplifies working with nested arrays by providing an intuitive way to access and modify data.

## Features
- Get values from arrays using dot notation keys.
- Set values in arrays using dot notation keys.
- Delete key(s) from array using dot notation.
- Check if a key or multi keys exists in an array using dot notation.
- Check if one key from keys is exists in an array...
- Map keys for output data in get operations.
- single and multiple default value for `get` function
- callback for when to return the default value for each key in `get` function
- callback for when set ech keys in `set` function

## Installation

You can install this library using Composer. If you haven't already set up Composer for your project, you can do so by following the [official Composer installation guide](https://getcomposer.org/doc/00-intro.md).

Once Composer is installed, run the following command to install Array Dot Notation:

```bash
composer require sajadsdi/array-dot-notation
```

## Usage

### Basic Usage

Here's how you can use Array Dot Notation in your PHP project:

```php
<?php
require 'vendor/autoload.php';

use Sajadsdi\ArrayDotNotation\DotNotation;

// Create an instance of DotNotation with an array
$data = [
    'user' => [
        'profile' => [
            'id' => 625,
            'pic' => '625.png',
        ],
    ],
];

$dotNotation = new DotNotation($data);

// Get values using dot notation
$userId = $dotNotation->get('user.profile.id'); // $userId will be 625
$picPath = $dotNotation->get('user.profile.pic'); // $picPath will be '625.png'

// Check if a key exists
if ($dotNotation->has('user.profile.id')) {
    // Key exists
} else {
    // Key does not exist
}

// Set values using dot notation
$dotNotation->set(['user.profile.id' => 12345, 'user.profile.pic' => 'new_pic.png']);

// Get the updated value
$newUserId = $dotNotation->get('user.profile.id'); // $newUserId will be 12345

// Delete key(s) using dot notation
$dotNotation->delete('user.profile.id');

$user = $dotNotation->get();
/** The $user will be :
[
    'user' => [
        'profile' => [
            'pic' => 'new_pic.png'
        ]
    ]
]
*/
//set again
$dotNotation->set(['user.profile.id' => 1234, 'user.profile.pic' => 'new_pic2.png']);
//multi keys deletion
$dotNotation->delete(['user.profile.id','user.profile.pic']);

$user = $dotNotation->get();
/** The $user will be :
[
    'user' => [
        'profile' => []
    ]
]
*/

```

### Advanced Usage

**Example 1: Getting Multiple Values Simultaneously**

You can retrieve multiple values simultaneously using dot notation:

```php
$settings = [
    'app' => [
        'name' => 'My App',
        'version' => '1.0',
    ],
    'user' => [
        'theme' => 'light',
    ],
];

// Initialize DotNotation with the $settings array
$dotNotation = new DotNotation($settings);

$result = $dotNotation->get(['app.name', 'app.version', 'user']);
// The result will be ['name' => 'My App', 'version' => '1.0', 'user' => ['theme' => 'light']]
```

**Example 2: Mapping Keys for Output Data in Get**

You can map keys for the output data when using the `get` method:

```php
$data = [
    'user' => [
        'profile' => [
            'id' => 625,
            'pic' => '625.png',
        ],
    ],
];

// Initialize DotNotation with the $data array
$dotNotation = new DotNotation($data);

$keys = ['profile_id' => 'user.profile.id', 'profile_photo' => 'user.profile.pic'];

$result = $dotNotation->get($keys);
// The result will be ['profile_id' => 625, 'profile_photo' => '625.png']
```

**Example 3: Automatic Key Handling for Duplicate Keys**

When you get values without using a map, the library will automatically handle duplicate keys:

```php
$array = [
    'users' => [
        ['id' => 1, 'name' => 'John'],
        ['id' => 2, 'name' => 'Alice'],
        ['id' => 3, 'name' => 'Emma'],
        ['id' => 4, 'name' => 'Emily'],
        ['id' => 5, 'name' => 'Sofia'],
    ],
];

// Initialize DotNotation with the $array
$dotNotation = new DotNotation($array);

$keys = ['users.2.name', 'users.3.name', 'users.4.name'];

$result = $dotNotation->get($keys);
// The result will be ['name' => 'Emma', 'name_1' => 'Emily', 'name_2' => 'Sofia']

// The keys are ['users.2.name', 'users.3.name', 'users.4.name'], in fact [0 => 'users.2.name', 1 =>'users.3.name', 2 => 'users.4.name']
```

**Example 4: Automatic Key Handling for Numeric Keys**

When the last key is a numeric index, it will use the input array index:

```php
$array = [
    'users' => [
        ['id' => 1, 'name' => 'John'],
        ['id' => 2, 'name' => 'Alice'],
        ['id' => 3, 'name' => 'Emma'],
        ['id' => 4, 'name' => 'Emily'],
        ['id' => 5, 'name' => 'Sofia'],
    ],
];

// Initialize DotNotation with the $array
$dotNotation = new DotNotation($array);

$keys = ['users.2', 'users.3', 'users.4'];

$result = $dotNotation->get($keys);
// The result will be [['id' => 3, 'name' => 'Emma'], ['id' => 4, 'name' => 'Emily'], ['id' => 5, 'name' => 'Sofia']]

// A better view of the result:
// [
//    [0] => ['id' => 3, 'name' => 'Emma'],
//    [1] => ['id' => 4, 'name' => 'Emily'],
//    [2] => ['id' => 5, 'name' => 'Sofia']
// ]
```


#### Example 5: Using `get` with a Single Default Value

In this example, we retrieve the value of the "user.profile.name" key from the data. This key doesn't exist, the default value "Guest" is returned.

```php
$data = [
            'app'  => [
                'name'    => 'My App',
                'version' => '1.0',
            ],
            'user' => [
                'theme' => 'light',
            ],
        ];

// Initialize DotNotation with your data array
$dotNotation = new DotNotation($data);

// Get the value with a single default value if the key doesn't exist
$username = $dotNotation->get('user.profile.name', 'Guest');

echo "Username: $username"; // Output: Username: Guest
```

#### Example 6: Using `get` with a Single Default Value and an Existing Key

In this example, we retrieve the value of the "user.profile.pic" key from the data. Since this key exists in the data, the actual value of the key is returned, and the default value is ignored.

```php
<?php
$data = [
    'user' => [
        'profile' => [
            'pic' => '625.png',
        ],
    ],
];
// Initialize DotNotation with your data array
$dotNotation = new DotNotation($data);

// Get the value with a single default value if the key exists
$profilePic = $dotNotation->get('user.profile.pic', 'default_pic.png');

echo "Profile Picture: $profilePic"; // Output: Profile Picture: 625.png (value from the data)
```

#### Example 7: Using `get` with Multiple Default Values

In this example, we retrieve the values of the "user.profile.name" and "user.profile.bio" keys from the data. If either of these keys doesn't exist, the corresponding default values are returned for each key.

```php
$data = [
    'user' => [
        'profile' => [
            'name' => 'John Doe',
        ],
    ],
];
// Initialize DotNotation with your data array
$dotNotation = new DotNotation($data);

// Get multiple values with a single default value for each key if the key doesn't exist
$profile = $dotNotation->get(['user.profile.name', 'user.profile.bio'], ['Guest', 'No bio available']);

// Output: ['name' => "John Doe", 'Bio' => "No bio available"]
```

#### Example 8: Using multiple `get` with one Default Value

In this example, we retrieve the values of the "user.profile.name" and "user.profile.bio" keys from the data. If either of these keys doesn't exist, default value are returned for each key.

```php
$data = [
    'user' => [
        'profile' => [
            'pic' => '625.png',
        ],
    ],
];
// Initialize DotNotation with your data array
$dotNotation = new DotNotation($data);

// Get multiple values with one default value for each key if the key doesn't exist
$profile = $dotNotation->get(
    ['user.profile.name', 'user.profile.bio'],'No available'
);

// Output: ['name' => "No available", 'Bio' => "No available"]
```
#### Example 9: Using multiple key in `has` method

In this example, we check for exist the "user.profile.name" and "user.profile.bio" keys in the data. if all keys exists in the data ,true returned.
```php
$data = [
    'user' => [
        'profile' => [
            'name' => 'John Doe',
        ],
    ],
];
// Initialize DotNotation with your data array
$dotNotation = new DotNotation($data);

// Check if multiple keys exist using an array of keys
$keysToCheck = ['user.profile.name', 'user.profile.bio'];

if ($dotNotation->has($keysToCheck)) {
    echo "All keys exist in the data.";
} else {
    echo "At least one key does not exist in the data.";
}
```

#### Example 10: Using `hasOne` method

if one of all keys exist in the data ,true returned.
```php
$data = [
    'user' => [
        'profile' => [
            'name' => 'John Doe',
        ],
    ],
];
// Initialize DotNotation with your data array
$dotNotation = new DotNotation($data);

// Check if at least one of the keys exists using an array of keys
$keysToCheck = ['user.profile.name', 'user.profile.bio'];

if ($dotNotation->hasOne($keysToCheck)) {
    echo "At least one key exists in the data.";
} else {
    echo "None of the keys exist in the data.";
}
```

These examples demonstrate the advanced capabilities of the Array Dot Notation library for handling various scenarios when accessing and manipulating nested arrays using dot notation.


### Contributing

We welcome contributions from the community to improve and extend this library. If you'd like to contribute, please follow these steps:

1. Fork the repository on GitHub.
2. Clone your fork locally.
3. Create a new branch for your feature or bug fix.
4. Make your changes and commit them with clear, concise commit messages.
5. Push your changes to your fork on GitHub.
6. Submit a pull request to the main repository.

### Reporting Bugs and Security Issues

If you discover any security vulnerabilities or bugs in this project, please let us know through the following channels:

- **GitHub Issues**: You can [open an issue](https://github.com/sajadsdi/array-dot-notation/issues) on our GitHub repository to report bugs or security concerns. Please provide as much detail as possible, including steps to reproduce the issue.

- **Contact**: For sensitive security-related issues, you can contact us directly through the following contact channels

### Contact

If you have any questions, suggestions, financial, or if you'd like to contribute to this project, please feel free to contact the maintainer:

- Email: thunder11like@gmail.com

We appreciate your feedback, support, and any financial contributions that help us maintain and improve this project.

## License

This library is open-source software licensed under the MIT License.

