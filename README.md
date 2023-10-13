# Development

## Setup
- instalirati Docker (ovisno o sustavu instalirati WSP za windows i napraviti update na WSP2)
- kopirati i preimenovati .env.example u .env
- generiranje ključa: `php artisan key:generate`
- composer update: `composer update`
- dodati Sail u root: `alias sail='bash vendor/bin/sail'`

- pokretanje kontejnera: `sail up`
- migracije i seedanje baze: `sail artisan migrate --seed` 

## Help
- reset baze: `sail artisan migrate:fresh --seed`
- prikaži sve rute: `sail artisan r:l`
- gašenje kontejnera: `sail down`
