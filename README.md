
## Great-circle distance implementation using laravel

This test uses a .txt file with a list of affiliates and output a list of affiliates(JSON) within 100km of a location(53.3340285, -6.2535495). The only route in use is /
You need to have laravel 8 installed to be able to run this code and use the command: php artisan server after cloning this repo.

The formula used is available on https://en.wikipedia.org/wiki/Great-circle_distance

Most of the code is located on app/Http/Controllers/AppController.php

You can access this code running live on https://gcddevtest.herokuapp.com/


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
