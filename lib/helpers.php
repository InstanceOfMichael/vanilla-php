<?php

function dump(...$args) {
    echo '<tt><pre style="overflow-x: auto;">';
    foreach($args as $arg) {
        echo htmlentities(print_r($arg, true));
    }
    echo '</pre></tt>';
}

// if string $a is greater than $b
// assumes a-zA-Z0-9
// compares as if strings represent higher
// than base16 (with undefined behavior for high bases)
// also undefined behavior for leading zeros
function str_gt($a, $b):bool {
    $al = strlen($a);
    $bl = strlen($b);
    if ($al !== $bl) return $al > $bl;
    return strnatcmp($a, $b) > 0;
}
function str_gte($a, $b):bool {
    if ($a === $b) return true;
    return str_gt($a, $b);
}
function is_image(string $path):bool {
    static $extentions = [
        'jpg' => 1, 'jpeg' => 1, 'png' => 1, 'gif' => 1, 'gifv' => 1,
    ];
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if ($ext && array_key_exists($ext, $extentions)) {
        return true;
    }
    return false;
}

// if (!is_image('skdjfljdskfls.jpg')) throw new \Exception('invalid result!');
// if (!is_image('skdjfljdskfls.GIF')) throw new \Exception('invalid result!');
// if (!is_image('skdjfljdskfls.gifv')) throw new \Exception('invalid result!');
// if (is_image('skdjfljdskfls.txt')) throw new \Exception('invalid result!');
// if (is_image('skdjfljdskfls.')) throw new \Exception('invalid result!');
// if (is_image('skdjfljdskfls')) throw new \Exception('invalid result!');
