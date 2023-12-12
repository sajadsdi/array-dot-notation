# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).
## [1.0.5] - 2023-12-12

### Added

- Added `getArrayKeys` method tool.

### Changed

- Added php reference to array input of `set` and `delete` methods.

### Fixed

- Fixed `has` methods on traits.


## [1.0.4] - 2023-12-09

### Added

- Added delete functions for single and multi traits.
- Added new callback for `get` method.

### Changed

- Renamed `callback` param on get method to `callbackDefault`.
- Added `callback` param on get method for called before return.
- Change after callbacks for get and set to before.
- refactor.
### Fixed

- No Fixed.


## [1.0.3] - 2023-12-07

### Added

- No Added.

### Changed

- No Changed.

### Fixed

- Fix namespace for test file.

## [1.0.2] - 2023-10-09

### Added

- add `callback` for `get` method.
- add `callback` for `set` method.

### Changed

- change `get` method params and add `callback` param.
- change `set` method param and add `callback` param.
- Modify the `getByDot` method in DotNotationTrait and add `callback` param.
- Modify the `getByDotMulti` method in MultiDotNotationTrait and add `callback` param.

### Fixed

- No Fixed.

## [1.0.1] - 2023-10-08

### Added

- add `default` value(s) for `get` method.
- add `hasOne` method.

### Changed

- change get method params and add `default` param.
- change `has` function param and scenario to get multiple keys.
- Modify the `getByDot` method in DotNotationTrait to provide default value.
- Modify the `getByDotMulti` method in MultiDotNotationTrait to provide default value(s).

### Fixed

- No Fixed.

## [1.0.0] - 2023-09-30

### Added

- Initial release.

