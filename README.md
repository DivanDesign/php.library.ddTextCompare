# ddTextCompare
A simple tool allowing to compare two strings. The library works only with UTF-8 strings for now.

## What this library is
ddTextCompare can be used in situations when it is not possible to make the native string comparison in PHP.
The library relies on chars number and their position, therefore, it's suitable for a comparison
of strings containing typos or having different word order but the same words.

## What this library isn't
ddTextCompare has nothing to do with morphological or any other analysis you would expect from an intelligent search
engine. The main purpose for the library is to stay simple.

## How it works

By default ddTextCompare performs a simple analysis. Each string being compared is represented as two n-dimensional vectors,
where n is the number of unique chars used in both strings. The firs vector v<sub>1</sub> shows what chars are in its string
and their total number. The other vector v<sub>2</sub> shows where those unique chars are.
When those two vectors are found for each string, cosine similarity will be found for the vector pairs.
Once calculated, cos(v<sub>1,1</sub>v<sub>1,2</sub>) and cos(v<sub>2,1</sub>v<sub>2,2</sub>) are multiplied by their wights respectively
and the result is modified to make it belong to a range between 0 and 1.

## Installation

### Composer
Just add the package to your composer.json.

```bash
composer require dd/php-library-ddtextcompare
```

### Manually

Though it's convenient to use Composer, it is also possible to place the library wherever you want and include all the classes inside the “src” folder manually.

## Basic usage

Here are some examples.

### Comparing strings with typos

```php
$compare = new DDTextCompare();
$similarity = $compare->compare("Text without any typos", "Text wihtout ayn typoes");
// $similarity = 0.99076390557741
```

### Adjusting weights

By default, the weights for all criteria are equal, but it can be changed.
When a wight is changed the another weight will be adjusted automatically to make their sum equal to 1.

```php
$compare = new DDTextCompare();
$comparator = new Comparator\Cosine();

//Change the char total criterion weight
$comparator->setCharTotalWeight(0.8)

$similarity = $compare->compare("Text without any typos", "Txet wihtout ayn typoes", $comparator);
// $similarity = 0.99310343750374
```

### Extending

A custom comparator class can be created by implementing the DDTextCompare\Comparator interface.

```php
$compare = new DDTextCompare();
$comparator = new Comparator\YourCustomComparator();

$similarity = $compare->compare("Text without any typos", "Txet wihtout ayn typoes", $comparator);
```

## Changelog
### Version 0.9 (2015-11-07)
* \+ The first release.