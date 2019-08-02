Description

Develop a script which returns a list of URLs based on a given input data. Input data is a JSON file provided as an argument. This file contains Array of Hashes, and every such Hash represents an object with possible fields:

- scheme: string, valid values: http|https

- username: an alphanumeric string

- password: an alphanumeric string

- domain_name: valid domain name

- port: integer

- path: string

- query: Hash with key-value pairs

- fragment: string

- disabled: boolean



`domain_name` is a single required field, the rest are optional



The code can be written in Ruby or Python.



The output is printed to STDOUT, every URL on a separate line.

All errors and exceptions have to be sent to STDERR.



Example

Input:

[

   {

       "scheme": "http",

       "domain_name": "www.anydomain.com",

       "path": "path/to/file",

       "fragment": "header2"

   },

   {

       "scheme": "http",

       "domain_name": "www.domain2.com",

       "disabled": true

   },

   {

       "scheme": "https",

       "domain_name": "www.somedomain.com",

       "path": "some/path",

       "query": {

           "key1": "value1",

           "key2": "value2"

       }

   }

]



Output:

http://www.anydomain.com/path/to/file#header2

https://www.somedomain.com/some/path?key1=value1&key2=value2