<?php
$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('d:/Laravel/myProject/resources/views'));
$count = 0;
foreach ($dir as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        // Match the <link ... > tag with optional preceding spaces/tabs and optional trailing newline
        $newContent = preg_replace('/[ \t]*<link rel="stylesheet" href="\{\{ asset\(\'css\/responsive\.css\'\) \}\}">\r?\n?/', '', $content);
        if ($content !== $newContent) {
            file_put_contents($file->getPathname(), $newContent);
            echo "Updated: " . $file->getPathname() . "\n";
            $count++;
        }
    }
}
echo "Total updated: $count\n";
