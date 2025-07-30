# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.0.0] - 2024-12-19

### Added
- Added support for Laravel 9.x, 10.x, 11.x, and 12.x
- Updated PHP requirement to 8.1+
- Improved service provider with configuration merging
- Enhanced testing with Orchestra Testbench
- Added comprehensive version compatibility matrix

### Changed
- Updated composer.json to support multiple Laravel versions
- Updated service provider to use version-agnostic patterns
- Simplified test setup to work with Orchestra Testbench

## [2.0.0] - 2024-12-19

### Added
- Updated to support Laravel 11.x
- Added PHP 8.2+ requirement
- Modernized code with proper return types
- Updated service provider to use singletons
- Added comprehensive test suite
- Improved facade documentation with IDE support

### Changed
- Updated all service classes with explicit return types
- Enhanced facade classes with comprehensive PHPDoc annotations
- Improved exception handling and validation

## [1.0.0] - Initial Release

### Added
- Initial release of Laravel DANA Payment Package
- Basic DANA API integration
- Service classes for core DANA functionality
- Facade classes for easy access
- Configuration management
- Basic documentation 