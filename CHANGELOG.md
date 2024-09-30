# Changelog

All notable changes to `Blueprint` will be documented in this file.

## [0.7.0](https://github.com/rougin/blueprint/compare/v0.6.0...v0.7.0) - Unreleased

> [!WARNING]
> This release will introduce a backward compatability break if upgrading from `v0.6.0` release.
> This is only applicable to packages that were extended to the `Blueprint` package.

### Added
- `Command` class for an alternative way of creating commands

### Changed
- Code coverage to `Codecov`
- Code documentation by `php-cs-fixer`
- Improved code quality by `phpstan`
- Simplified code structure
- Workflow to `Github Actions`

### Removed
- `Application` class
- `File` class
- `league/flysystem` package
- `twig/twig` package
- `rdlowrey/auryn` package

## [0.6.0](https://github.com/rougin/blueprint/compare/v0.5.0...v0.6.0) - 2017-01-11

### Added
- `Application` class

### Changed
- Code quality
- Moved `GreetCommand` to `tests/Fixture`

### Removed
- `BLUEPRINT_FILENAME` constant
- `CONTRIBUTING.md`

## [0.5.0](https://github.com/rougin/blueprint/compare/v0.4.0...v0.5.0) - 2016-12-28

### Added
- Support for Twig extensions
- Support for specified directory

## [0.4.0](https://github.com/rougin/blueprint/compare/v0.3.0...v0.4.0) - 2016-10-10

### Added
- `Commands\GreetCommand`
- `Console` for preparing the application easily

### Changed
- `File` to `League\Flysystem`

## [0.3.0](https://github.com/rougin/blueprint/compare/v0.2.1...v0.3.0) - 2016-08-01

### Added
- Additional parameter in Blueprint::setTemplatePath for setting up `Twig_Environment`

## [0.2.1](https://github.com/rougin/blueprint/compare/v0.2.0...v0.2.1) - 2016-07-22

### Changed
- Version of `Symfony` components

## [0.2.0](https://github.com/rougin/blueprint/compare/v0.1.5...v0.2.0) - 2016-03-25

### Added
- Unit tests for `Blueprint`
- `Common\File` class for handling files

### Changed
- Moved preloading of `Twig_Environment` to `Blueprint::setTemplatePath`

### Removed
- `Parser` class

## [0.1.5](https://github.com/rougin/blueprint/compare/v0.1.4...v0.1.5) - 2016-02-02

### Changed
- `blueprint.yml` template

### Fixed
- Issue in version of [kevinlebrun/colors.php](https://github.com/kevinlebrun/colors.php)

## [0.1.4](https://github.com/rougin/blueprint/compare/v0.1.3...v0.1.4) - 2015-10-20

### Added
- Executables in `composer.json`

## [0.1.3](https://github.com/rougin/blueprint/compare/v0.1.2...v0.1.3) - 2015-10-20

### Changed
- Simplified code structure

## [0.1.2](https://github.com/rougin/blueprint/compare/v0.1.1...v0.1.2) - 2015-10-08

### Changed
- Main logic of the application

## [0.1.1](https://github.com/rougin/blueprint/compare/v0.1.0...v0.1.1) - 2015-08-09

### Added
- Extensibility of `Blueprint` class

## 0.1.0 - 2015-04-01

### Added
- `Blueprint` library