<?php

require_once 'includes/core.php';

function getFavorites($type) {
    $result = request('/favorite/getUserFavorites', 'GET', [
        'type'      => $type,
        'limit'     => PAGE_SIZE
    ]);

    $favorites = $result[ $type ]['items'];

    while (count($favorites) < $result[ $type ]['total']) {
        $result = request('/favorite/getUserFavorites', 'GET', [
            'type'      => $type,
            'limit'     => PAGE_SIZE,
            'offset'    => $result[ $type ]['offset'] + PAGE_SIZE
        ]);

        $favorites = array_merge($favorites, $result[ $type ]['items']);
    }

    return $favorites;
}

setup();

foreach (FAVORITE_TYPES as $type) {
    print 'Exporting ' . $type . '... ';

    $favorites = getFavorites($type);

    if ($favorites) {
        $file = EXPORT_PATH . '/' . $type . '.json';

        file_put_contents($file, json_encode($favorites));
    }

    print 'OK!' . PHP_EOL;
}

print 'Done.' . PHP_EOL;

exit(0);

?>