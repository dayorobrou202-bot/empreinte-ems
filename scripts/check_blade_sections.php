<?php
$dir = __DIR__ . '/../resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$issue = [];
foreach ($files as $file) {
    if (!$file->isFile()) continue;
    if (substr($file->getFilename(), -10) !== '.blade.php') continue;
    $path = $file->getPathname();
    $content = file_get_contents($path);
    $sections = preg_match_all('/@section\b/', $content);
    $endsections = preg_match_all('/@endsection\b/', $content);
    $yields = preg_match_all('/@yield\b/', $content);
    $extends = preg_match_all('/@extends\b/', $content);
    if ($sections !== $endsections) {
        $issue[] = [
            'file' => substr($path, strlen(__DIR__ . '/../')),
            'sections' => $sections,
            'endsections' => $endsections,
            'yields' => $yields,
            'extends' => $extends,
        ];
    }
}
if (empty($issue)) {
    echo "OK: tous les fichiers Blade ont des paires @section/@endsection équilibrées.\n";
    exit(0);
}
foreach ($issue as $i) {
    echo "MISMATCH: {$i['file']} -> sections={$i['sections']} endsections={$i['endsections']} yields={$i['yields']} extends={$i['extends']}\n";
}
exit(1);
