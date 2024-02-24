# Descartes MVC
Descartes est un système MVC pour PHP volontairement simple et minimaliste.

Descartes fourni un système de routage largement automatisé et utilise l'architecture Objet de PHP5 dans le but de découper les différentes fonctionnalitées d'un site en classes et méthodes.
Descartes propose également un certain nombre de fonctionnalités permettant la mise en place simple d'une API Rest.

Descartes est distribué dans une forme intégrant un certain nombre d'outils, notamment une administration de base de données.

Descartes sert de base à une grande partie des développement de Raspbian France et est distribué sous license GNU/GPL V3.

Le système tire sont nom du philosophe Français René Descartes, en hommage à son brillant "Discours de la méthode" (et particulièrement aux "Règles de la méthode"), que nous pensons être bel et bon pour ce qui est du développement informatique.


# Documentation

## Controllers
- **Public Controllers**:
  - Extend `\descartes\Controller`.
  - Responsible for processing input data, formatting them, and handling requests from users.
  - Each function in a public controller represents a page of the application.
  - Common processing for all routes of a controller should be done in the constructor.
  - If you need to do some logic on all method of the controller, for example for auth or data initialization, you can do it in construct.

- **Internal Controllers**:
  - Extend `\descartes\InternalController`.
  - Used to perform the core logic for most tasks.
  - Handle tasks like database interactions, business logic, etc.

## Models
- Interaction with the Database.
- Methods available to simplify database interactions (CRUD).
- Methods in `\descartes\Model` that start with `_` provide database operations.

## Templates
- Located in the `template` directory.
- Pages can be rendered using the `render` method of a controller.

## Routes
- Defined in `routes.php`.
- Routes map URLs to controller actions.

## Request Flow
- The typical flow for a request:
  1. Router calls a public controller.
  2. Public controller calls one or more internal controllers.
  3. Internal controllers interact with models.
  4. Public controller renders a template.

## Constants
You can set constants by defining an array key => value in variable `$env` in following files (each file will be merged with previous file, overwriting existings constants if necessary) :
- `descartes/env.php`
- `env.descartes.php`
- `env.php`
- `env.XXX.php` where `XXX` is the value of a previously define constant `ENV`.

Look into `descartes/env.php` to see default available constants.

## Conclusion
[Conclude the documentation, mentioning any additional resources or next steps.]
