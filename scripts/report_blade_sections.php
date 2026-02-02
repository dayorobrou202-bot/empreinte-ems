<?php
$root = __DIR__ . '/../resources/views';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$report = [];
foreach ($it as $file) {
    if ($file->isFile() && substr($file->getFilename(), -10) === '.blade.php') {
        $path = $file->getPathname();
        $text = file_get_contents($path);
        $cSection = preg_match_all("/@section\b/", $text);
        $cEnd = preg_match_all("/@endsection\b/", $text);
        $report[$path] = [$cSection ?? 0, $cEnd ?? 0];
    }
}
$out = '';
foreach ($report as $p => $counts) {
    list($s, $e) = $counts;
    if ($s !== $e) {
        $out .= "$p | sections=$s endsections=$e\n";
    }
}
if ($out === '') $out = "All balanced\n";
file_put_contents(__DIR__ . '/blade_report.txt', $out);
echo "Report written to scripts/blade_report.txt\n";
