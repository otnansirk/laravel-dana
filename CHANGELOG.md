# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.0.0](https://github.com/otnansirk/laravel-dana/releases/tag/v3.0.0) — 2025-08-14

### Feat
- Added support for Laravel 9.x, 10.x, 11.x, and 12.x
- Updated PHP requirement to 8.1+
- Improved service provider with configuration merging
- Enhanced testing with Orchestra Testbench
- Added comprehensive version compatibility matrix

### Changed
- Updated composer.json to support multiple Laravel versions
- Updated service provider to use version-agnostic patterns
- Simplified test setup to work with Orchestra Testbench

## [2.3.0](https://github.com/otnansirk/laravel-dana/releases/tag/v2.3.0) — 2024-08-07
- **Feat:** Added support for Laravel 10 and 11

## [2.2.1](https://github.com/otnansirk/laravel-dana/releases/tag/v2.2.1) — 2024-01-25
- **Fix:** Resolved issue with missing or incorrect transaction status information

## [2.2.0](https://github.com/otnansirk/laravel-dana/releases/tag/v2.2.0) — 2024-01-25
- **Feature:** Added ability to query orders for checking transaction status

## [2.1.2](https://github.com/otnansirk/laravel-dana/releases/tag/v2.1.2) — 2023-07-31
- **Feature:** Introduced callback for MDR (Merchant Discount Rate) calculation  
- **Bug Fixes:**  
  - Made `merchantTransId` dynamic  
  - Fixed MDR calculation in callback function

## [2.0.0](https://github.com/otnansirk/laravel-dana/releases/tag/v2.0.0) — 2023-07-17
- **Feature:** Added support for Laravel 9

## [1.0.0](https://github.com/otnansirk/laravel-dana/releases/tag/v1.0.0) — 2022-11-01
- **Initial Release:** First release :)