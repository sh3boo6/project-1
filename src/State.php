<?php
/**
 * Global State Manager
 * 
 * This class provides a simple way to manage global state throughout the application.
 * It can be used to store and retrieve values that need to be accessible across different parts of the app.
 */

class State {
    private static $state = [];
    
    /**
     * Set a value in the global state
     *
     * @param string $key The key to store the value under
     * @param mixed $value The value to store
     * @return void
     */
    public static function set($key, $value) {
        self::$state[$key] = $value;
    }
    
    /**
     * Get a value from the global state
     *
     * @param string $key The key to retrieve
     * @param mixed $default The default value to return if the key doesn't exist
     * @return mixed The stored value or the default value
     */
    public static function get($key, $default = null) {
        return self::$state[$key] ?? $default;
    }
    
    /**
     * Check if a key exists in the global state
     *
     * @param string $key The key to check for
     * @return bool Whether the key exists
     */
    public static function has($key) {
        return isset(self::$state[$key]);
    }
    
    /**
     * Remove a value from the global state
     *
     * @param string $key The key to remove
     * @return void
     */
    public static function remove($key) {
        if (isset(self::$state[$key])) {
            unset(self::$state[$key]);
        }
    }
    
    /**
     * Get all values in the global state
     *
     * @return array All values in the state
     */
    public static function all() {
        return self::$state;
    }
    
    /**
     * Clear the entire global state
     *
     * @return void
     */
    public static function clear() {
        self::$state = [];
    }
}
