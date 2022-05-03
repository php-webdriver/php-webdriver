<?php
use Doctum\RemoteRepository\GitHubRemoteRepository;
use Doctum\Doctum;
use Doctum\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$root = realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR;
$srcRoot = $root;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in($srcRoot . 'lib');

$versions = GitVersionCollection::create($srcRoot)
    ->addFromTags('1.*') // only latest minor version
    ->addFromTags('0.6.0')
    ->add('main', 'main branch')
;

return new Doctum($iterator, [
    'title' => 'php-webdriver API',
    'theme' => 'default',
    'build_dir' => $root . '/build/dist/%version%/',
    'cache_dir' => $root . '/build/cache/%version%/',
    'include_parent_data' => true,
    'remote_repository' => new GitHubRemoteRepository('php-webdriver/php-webdriver', $srcRoot),
    'versions' => $versions,
    'base_url' => 'https://php-webdriver.github.io/php-webdriver/%version%/'
]);
