# Pomm bridge for Api platform

[![Build Status](https://travis-ci.org/pomm-project/pomm-api-platform.svg?branch=master)](https://travis-ci.org/pomm-project/pomm-api-platform)

Use [Pomm](http://www.pomm-project.org/) with [Api platform](https://api-platform.com/).

## Filters

### Search Filter

The search filter supports:

 * ```partial``` strategy uses ```LIKE %text%``` to search for fields that containing the text.
 * ```start strategy``` uses ```LIKE text%``` to search for fields that starts with text.
 * ```end strategy``` uses ```LIKE %text``` to search for fields that ends with text.
 * ```word_start``` strategy uses ```LIKE text% OR LIKE % text%``` to search for fields that contains the word starting with text.

Prepend the letter ```i``` to the filter if you want it to be case insensitive. For example ```ipartial``` or ```iexact```.

Add an entry in `services.yml`:

```yml
services:
    app.book.search_filter:
        parent:    'api_platform.pomm.search_filter'
        arguments: [ { 'title': 'exact', 'description': 'partial'} ]
        tags:      [ { name: 'api_platform.filter', id: 'book.search' } ]
```

And in `resources.yml` use the service filter:

```yml
resources:
    AppBundle\Entity\Book:
        collectionOperations:
            get:
                filters: ['book.search']
```
