<?php

function lang() {
    if (whas(LOCALE)) {
        echo '&' . LOCALE . '=' . wget(LOCALE);
    }
}

function flang() {
    if (whas(LOCALE)) {
        echo '?' . LOCALE . '=' . wget(LOCALE);
    }
}

function ilang() {
    if (whas(LOCALE)) {
        echo '<input type="hidden" name="' . LOCALE . '" value="' . wget(LOCALE) . '" />';
    }
}
