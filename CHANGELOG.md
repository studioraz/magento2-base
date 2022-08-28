# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.4.3] - 2022-08-28
### Added
- SR logo to sys. conf. menu item

### Changed
- Module title in admin menu (`Studio Raz` => `StudioRaz`) 

## [1.4.1] - 2022-02-08
### Fixed
- DateTimeHelper issue, when any operation did not count Timezone, which is passed to DateTime object;

### Added
- extra optional param $timezone to each method of DateTimeHelper

## [1.4.0] - 2022-02-01
### Added:
- getTimestamp method DateTimeHelper
- subtractDateInterval method DateTimeHelper

## [1.3.0]
### Changed
- Moved const declaration above properties 

## [1.2.0] - 2021-04-16
### Implemented
- Helper - to handle DateTime objects
- Exception - common LocalizedException for all SR extensions

## [1.1.0] - 2021-03-22
### Added
- Feature that allows moving a component in the js layout

### Changed
- Applied module template

## [1.0.0] - 2020-12-16
### Added
- Project init


[Unreleased]: https://github.com/studioraz/magento2-base/compare/1.4.3...HEAD
[1.4.3]: https://github.com/studioraz/magento2-base/compare/1.4.2...1.4.3
[1.4.2]: https://github.com/studioraz/magento2-base/compare/1.4.1...1.4.2
[1.4.1]: https://github.com/studioraz/magento2-base/compare/1.4.0...1.4.1
[1.4.0]: https://github.com/studioraz/magento2-base/compare/1.3.0...1.4.0
[1.3.0]: https://github.com/studioraz/magento2-base/compare/1.2.0...1.3.0
[1.2.0]: https://github.com/studioraz/magento2-base/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/studioraz/magento2-base/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/studioraz/magento2-base/releases/tag/1.0.0
