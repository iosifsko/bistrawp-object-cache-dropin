# BistraWP Redis Object Cache Drop-in

This is a production-ready, plugin-free `object-cache.php` drop-in file designed for WordPress sites using Redis OSS via the `phpredis` extension.

## Features

- ✅ Full support for WordPress internal object caching (`wp_cache_*` functions)
- ✅ Handles transients, site options, users, and more
- ✅ Uses Redis OSS (compatible with AWS ElastiCache Redis)
- ✅ Zero plugin bloat — lightweight drop-in file only
- ✅ Base-tier ready for BistraWP infrastructure

## Usage

1. Upload `object-cache.php` to the `/wp-content/` directory of your WordPress site.
2. Make sure the `phpredis` extension is installed and enabled.
3. Redis keys will now be automatically generated and used by WordPress core.

## Notes

- Update the Redis host inside `object-cache.php` if your endpoint is different:
  ```php
  $redis->connect('your-redis-host', 6379, 1);
  ```
- Works with WordPress 5.x and 6.x
- Compatible with single-site and multi-site installs

---

Created for the BistraWP platform by Iosif Skorohodov and Akira (ChatGPT)
