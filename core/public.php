<?php

function wput($key, $value, $persist = false) {
    \ls\core\wput($key, $value, $persist);
}

function wmap($sourceKey, $destinationKey, $persist = false) {
    \ls\core\wmap($sourceKey, $destinationKey, $persist);
}

function wmove($sourceKey, $destinationKey, $persist = false) {
    \ls\core\wmove($sourceKey, $destinationKey, $persist);
}

function wget($key) {
    return \ls\core\wget($key);
}

function whas($key) {
    return \ls\core\whas($key);
}

function wremove($key) {
    \ls\core\wremove($key);
}

function wvalidate($key, $validator) {
    return \ls\core\wvalidate($key, $validator);
}

function wequals($key, $value, $strict = true) {
    return \ls\core\wequals($key, $value, $strict);
}

function wclear() {
    \ls\core\wclear();
}

function cget($key) {
    return \ls\core\cget($key);
}

function chas($key) {
    return \ls\core\chas($key);
}

function useLib($name, $module = null) {
    \ls\core\useLib($name, $module);
}

function useModel($name, $module = null) {
    \ls\core\useModel($name, $module);
}
