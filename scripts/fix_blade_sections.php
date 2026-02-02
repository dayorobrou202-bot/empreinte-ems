<?php
// Simple Blade section balancer
// Usage: php scripts/fix_blade_sections.php

$root = __DIR__ . '/../resources/views';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$files = [];
foreach ($it as $file) {
    if ($file->isFile() && substr($file->getFilename(), -10) === '.blade.php') {
        $files[] = $file->getPathname();
    }
}

$modified = [];
foreach ($files as $f) {
    $text = file_get_contents($f);
    $countSection = preg_match_all("/@section\b/", $text);
    $countEnd = preg_match_all("/@endsection\b/", $text);

    if ($countSection === false || $countEnd === false) continue;

    if ($countSection == $countEnd) continue;

    echo "Analyzing: $f\n";
    echo "  sections: $countSection, endsections: $countEnd\n";

    // If there's exactly one more @endsection than @section and the last non-empty line is @endsection, remove it.
    if ($countEnd === $countSection + 1) {
        $lines = preg_split('/\r?\n/', $text);
        $i = count($lines) - 1;
        while ($i >= 0 && trim($lines[$i]) === '') $i--;
        if ($i >= 0 && preg_match('/^\s*@endsection\b/', $lines[$i])) {
            // backup
            copy($f, $f . '.bak');
            echo "  Backing up original to: " . basename($f) . ".bak\n";
            // remove that last @endsection line
            array_splice($lines, $i, 1);
            $new = implode("\n", $lines);
            file_put_contents($f, $new);
            echo "  Removed trailing @endsection\n";
            $modified[] = $f;
            continue;
        }
    }

    // If there are fewer @endsection than @section, try to append missing endsections at end.
    if ($countSection === $countEnd + 1) {
        // backup
        copy($f, $f . '.bak');
        echo "  Backing up original to: " . basename($f) . ".bak\n";
        $appendCount = $countSection - $countEnd;
        $new = rtrim($text) . "\n" . str_repeat("\n@endsection", $appendCount) . "\n";
        file_put_contents($f, $new);
        echo "  Appended $appendCount @endsection(s) at file end\n";
        $modified[] = $f;
        continue;
    }

    echo "  Warning: complex imbalance; manual review needed.\n";
}

echo "\nSummary:\n";
if (count($modified)) {
    foreach ($modified as $m) echo "  Modified: $m\n";
} else {
    echo "  No simple fixes applied.\n";
}

echo "Done. Review .bak files for backups.\n";
