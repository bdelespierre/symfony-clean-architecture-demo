# Symfony Clean Architecture Demo

## Installation

Requirements

- PHP8
- Composer
- ext-json

Dev requirements

- ext-curl

To install all requirements:

```bash
# for Ubuntu 20.04
apt install -y php7.4 php7.4-json php7.4-curl

# switch between PHP versions (if needed)
update-alternatives --config php
```

To install the project:

```bash
# clone the project
git clone https://github.com/bdelespierre/symfony-clean-architecture-demo.git

# move into the project directory
cd symfony-clean-architecture-demo

# install the dependencies
composer install
```

## Usage

Validate a promo-code:

```bash
bin/console promo-code:validate <code>
```

## Testing

For now, only use cases and adapters are under test. To run the tests:

```bash
make test
```

To generate a coverage report in HTML in the `coverage/` folder (Xdebug extension required):

```bash
make coverage
```

## Contributing

Commited code must validate against the QA pipeline. You may use `make` to check your code at any time:

```bash
make qa
```

Current level for PHPStan is 8 (highest), let's keep it that way.

Implementation gaps are *tolerated* as long as they're flagged with a `@todo` mention. A list of todos can be displayed any time with:

```bash
make todolist
```

## Folder Stucture

The project is structured under the [Clean Architecture](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html):

- `src/` is where **Application** classes live (controllers, commands, adapters etc.) **NO BUSINESS LOGIC ALLOWED HERE**
- `domain/` is where the **Business Logic** live (use-cases, models, contracts)
- `infrastructure/` is where the **Service** classes live (repositories, factories, API clients etc.) **NO BUSINESS LOGIC ALLOWED HERE**

The rest is standard Symfony.

Model is structured using the [Domain Driven Design](https://medium.com/ssense-tech/domain-driven-design-everything-you-always-wanted-to-know-about-it-but-were-afraid-to-ask-a85e7b74497a).
