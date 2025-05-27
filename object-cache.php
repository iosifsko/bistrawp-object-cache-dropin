<?php
// Production-ready Redis object-cache.php for WordPress (BistraWP Base Tier)

if (!class_exists('Redis')) {
    error_log('Redis extension not found.');
    return;
}

global $wp_object_cache;

$redis = new Redis();
try {
    $redis->connect('bistrawp-redis-basic.fg5vfl.ng.0001.usw2.cache.amazonaws.com', 6379, 1);
} catch (Exception $e) {
    error_log('Redis connection failed: ' . $e->getMessage());
    return;
}

$wp_object_cache = array(
    'redis' => $redis,
    'prefix' => 'wp_cache:',
    'global_groups' => array(
        'blog-details', 'blog-id-cache', 'blog-lookup', 'blog_meta',
        'site-details', 'site-lookup', 'site-options', 'site-transient',
        'rss', 'users', 'userlogins', 'usermeta', 'user_meta', 'useremail',
        'userlogins', 'usernice', 'useremail', 'userslugs',
        'options', 'transient', 'comment', 'counts', 'plugins'
    ),
    'non_persistent_groups' => array()
);

function wp_cache_key($key, $group) {
    global $wp_object_cache;
    return $wp_object_cache['prefix'] . $group . ':' . $key;
}

function wp_cache_add($key, $data, $group = '', $expire = 0) {
    return wp_cache_set($key, $data, $group, $expire);
}

function wp_cache_set($key, $data, $group = '', $expire = 0) {
    global $wp_object_cache;
    if (in_array($group, $wp_object_cache['non_persistent_groups'])) return false;
    $id = wp_cache_key($key, $group);
    $data = serialize($data);
    return $expire > 0
        ? $wp_object_cache['redis']->setex($id, $expire, $data)
        : $wp_object_cache['redis']->set($id, $data);
}

function wp_cache_get($key, $group = '') {
    global $wp_object_cache;
    if (in_array($group, $wp_object_cache['non_persistent_groups'])) return false;
    $id = wp_cache_key($key, $group);
    $data = $wp_object_cache['redis']->get($id);
    return $data === false ? false : unserialize($data);
}

function wp_cache_delete($key, $group = '') {
    global $wp_object_cache;
    return $wp_object_cache['redis']->del(wp_cache_key($key, $group));
}

function wp_cache_flush() {
    global $wp_object_cache;
    return $wp_object_cache['redis']->flushDB();
}

function wp_cache_init() {
    return true;
}

function wp_cache_close() {
    global $wp_object_cache;
    $wp_object_cache['redis']->close();
}

function wp_cache_add_global_groups($groups) {
    global $wp_object_cache;
    $groups = (array)$groups;
    $wp_object_cache['global_groups'] = array_merge($wp_object_cache['global_groups'], $groups);
}

function wp_cache_add_non_persistent_groups($groups) {
    global $wp_object_cache;
    $groups = (array)$groups;
    $wp_object_cache['non_persistent_groups'] = array_merge($wp_object_cache['non_persistent_groups'], $groups);
}
