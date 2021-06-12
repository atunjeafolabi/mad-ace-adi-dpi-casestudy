## Overview
This application receives an input data file of list of hotels in XML and JSON formats,
validates it and then converts it into CSV.

## Installation:
From the project root directory, run `composer install`

## Usage:
  ```php bin/console trivago:convert <file_name> [options] ```

Arguments
 * file_name (e.g hotels.json, hotels.xml)     

#### Options:
```
-- sort             Sort by column (e.g stars and name column)

-- order            Arrange in ascending (ASC) / descending (DESC) order           

-- strict           Terminates the process if any invalid entry is detected when set to true. 
                    (No output file is generated)

-- debug            Outputs invalid entries on console when set to true

-- help             Show help message

```

#### Usage Example:

```php bin/console trivago:convert file_name.ext```
- ##### With Options

    - `php bin/console trivago:convert hotels.json --sort=stars`
    - `php bin/console trivago:convert hotels.xml --sort=stars --order=asc`

#### Validation Rules

- A hotel name may not contain non-ASCII characters like ( ß, ä, ö )

- The hotel URL must be valid. Below are examples
    
    - Valid URLs
        - https://www.berlin.com
        - http://www.berlin.com
        - https://berlin.com
        - http://berlin.com
        - http://www.berlin.de
        - http://berlin.xyz
        - https://www.berlin.com/to/some/other/path
        - http://www.berlin.com/to/some/other/path
        - https://berlin.com/to/some/other/path
        - http://berlin.com/to/some/other/path
        - https://berlin.com/to/some/other/path.htm
        - http://berlin.com/to/some/other/path.php
        - https://berlin.com/path.xyz
        
    - Invalid URLs
        - http://www.invalid-uri
        - www.invalid-uri
        - http://www.invalid-uri/xyz

- Hotel ratings are given as a number from 0 to 5 stars (never negative numbers).

#### Output
- The generated CSV file can be found in `/var/out` folder

---

For help on using this command:

```php bin/console trivago:convert --help```

### Running Test:

```composer test```

---

### Other Input Formats:

 - To extend this application to handle new input formats,
   create a new input format class which implements the
   `App\Contracts\Encoder` contract

```
class AnotherInputFormat implements Encoder {

   /**
     * {@inheritdoc}
     */
    public function format(): string
    {
        return "format"; // i.e file extension
    }

    /**
     * {@inheritdoc}
     */
    public function decode(array $data) : string
    {
        return $this->encoder->decode($data, $this->format());
    }
}
```

### Logs
- Whenever an error/exception occurs, information about it
is logged in a file `*.log`, depending on the environment the 
application is running. This file is located in `/var/log` folder

### Technology

- PHP 7
- Symfony 5 framework

