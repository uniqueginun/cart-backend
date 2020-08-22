<?php

Route::get('version', function () {
    return phpversion();
});