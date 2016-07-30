<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(array(__DIR__ . '/lib', __DIR__ . '/tests'));

return Symfony\CS\Config\Config::create()
    ->fixers(array(
        'duplicate_semicolon',
        'extra_empty_lines',
        'multiline_array_trailing_comma',
        'namespace_no_leading_whitespace',
        'new_with_braces',
        'no_blank_lines_after_class_opening',
        'no_empty_lines_after_phpdocs',
        'object_operator',
        'operators_spaces',
        'trim_array_spaces',
        'phpdoc_indent',
        'phpdoc_no_access',
        'phpdoc_no_empty_return',
        'phpdoc_no_package',
        'phpdoc_scalar',
        'phpdoc_trim',
        'phpdoc_types',
        'phpdoc_order',
        'unused_use',
        'ordered_use',
        'remove_leading_slash_use',
        'remove_lines_between_uses',
        'function_typehint_space',
        'self_accessor',
        'single_array_no_trailing_comma',
        'single_blank_line_before_namespace',
        'single_quote',
        'spaces_cast',
        'whitespacy_lines',
        'newline_after_open_tag',
    ))
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->setUsingCache(true)
    ->finder($finder);
