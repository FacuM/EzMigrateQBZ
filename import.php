<?php

require_once 'includes/core.php';

$hasExports = false;

foreach (FAVORITE_TYPES as $type) {
    $file = EXPORT_PATH . '/' . $type . '.json';

    if (file_exists($file)) {
        $hasExports = true;

        break;
    }
}

if (!$hasExports) {
    print 'No exports found.' . PHP_EOL;

    exit(0);
}

setup();

foreach (FAVORITE_TYPES as $type) {
    print 'Reading ' . $type . '... ';

    $nonPluralType = substr($type, 0, -1);

    $file = EXPORT_PATH . '/' . $type . '.json';

    if (!file_exists($file)) {
        print 'No ' . $type . ' found.' . PHP_EOL;

        continue;
    }

    $favorites = json_decode(file_get_contents($file), true);

    print 'OK!' . PHP_EOL;

    if ($favorites) {
        foreach ($favorites as $favorite) {
            print '=> ' . $favorite['id'] . ': ' . ($favorite['title'] ?? $favorite['name']) . '... ';

            $result = request('/favorite/create', 'POST', [
                $nonPluralType . '_ids' => $favorite['id']
            ]);

            if (
                isset($result['status'])
                &&
                $result['status'] == 'success'
            ) {
                print 'OK!' . PHP_EOL;
            } else {
                print 'FAIL! - ' . $result['status'] .  PHP_EOL;
            }
        }
    }
}

print 'Done.' . PHP_EOL;

exit(0);

?>