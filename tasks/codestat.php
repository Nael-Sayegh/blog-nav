<?php

$document_root = realpath(__DIR__.'/../');
require_once($document_root.'/include/consts.php');

$include_dirs = [
    '',
    '403',
    'a',
    'admin',
    'api',
    'c',
    'css',
    'gadgets',
    'include',
    'include/lib/facebook',
    'locales',
    'r',
    'scripts',
    'tasks',
    'u',
];

$exclude_paths = [
    'cache',
    'vendor',
    'include/lib/facebook/vendor',
    'include/lib/mtcaptcha',
    'admin/adminer',
];

$allowed_exts = ['php', 'css', 'js', 'xml', 'txt'];

function list_files_recursive(string $root, array $include_dirs, array $exclude_paths, array $allowed_exts): array
{
    $files = [];

    $rii = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($rii as $file)
    {
        if (!$file->isFile())
        {
            continue;
        }

        $rel = substr((string) $file->getPathname(), strlen($root) + 1);

        foreach ($exclude_paths as $ex)
        {
            $ex = rtrim((string) $ex, '/');
            if (str_starts_with($rel, $ex.'/') || $rel === $ex)
            {
                continue 2;
            }
        }

        $included = false;
        foreach ($include_dirs as $dir)
        {
            $dir = rtrim((string) $dir, '/');
            if ($dir === '' && !str_contains($rel, '/'))
            {
                $included = true;
                break;
            }
            elseif (str_starts_with($rel, $dir.'/'))
            {
                $included = true;
                break;
            }
        }

        if (!$included)
        {
            continue;
        }

        $ext = strtolower(pathinfo($rel, PATHINFO_EXTENSION));
        if ($ext === '' || !in_array($ext, $allowed_exts))
        {
            continue;
        }

        $files[] = $rel;
    }

    return array_values(array_unique($files));
}

$files = list_files_recursive($document_root, $include_dirs, $exclude_paths, $allowed_exts);

$n_files = 0;
$n_lines = 0;
$n_chars = 0;

foreach ($files as $file)
{
    $n_files++;
    $path = $document_root.'/'.$file;
    if ($f = fopen($path, 'r'))
    {
        while (!feof($f))
        {
            fgets($f, 8192);
            $n_lines++;
        }
        fclose($f);
        $n_chars += filesize($path);
    }
    else
    {
        echo "Not found: $file\n";
    }
}

$outfile = fopen($document_root.'/cache/codestatc.php', 'w');
fputs($outfile, '<?php $codestat_n_files='.$n_files.';$codestat_n_lines='.$n_lines.';$codestat_n_chars='.$n_chars.'; ?>');
fclose($outfile);
