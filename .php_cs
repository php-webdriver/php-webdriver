<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in([__DIR__ . '/lib', __DIR__ . '/tests']);

return Symfony\CS\Config\Config::create()
    ->fixers([
        'array_element_white_space_after_comma',
        'duplicate_semicolon',
        'extra_empty_lines',
        'function_typehint_space',
        'lowercase_cast',
        'method_argument_default_value',
        'multiline_array_trailing_comma',
        'namespace_no_leading_whitespace',
        'native_function_casing',
        'new_with_braces',
        'no_blank_lines_after_class_opening',
        'no_empty_lines_after_phpdocs',
        'no_empty_phpdoc',
        'no_empty_statement',
        'object_operator',
        'operators_spaces',
        'trim_array_spaces',
        'phpdoc_indent',
        'phpdoc_no_access',
        'phpdoc_no_empty_return',
        'phpdoc_no_package',
        'phpdoc_scalar',
        'phpdoc_single_line_var_spacing',
        'phpdoc_trim',
        'phpdoc_types',
        'phpdoc_order',
        'unused_use',
        'ordered_use',
        'remove_leading_slash_use',
        'remove_lines_between_uses',
        'return',
        'self_accessor',
        'single_array_no_trailing_comma',
        'single_blank_line_before_namespace',
        'single_quote',
        'spaces_after_semicolon',
        'spaces_before_semicolon',
        'spaces_cast',
        'standardize_not_equal',
        'ternary_spaces',
        'trim_array_spaces',
        'unary_operators_spaces',
        'unused_use',
        'whitespacy_lines',

        // additional contrib checks
        'concat_with_spaces',
        'newline_after_open_tag',
        'no_useless_else',
        'no_useless_return',
        'php_unit_construct',
        'php_unit_dedicate_assert',
        'phpdoc_order',
        'short_array_syntax',
    ])
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->setUsingCache(true)
    ->finder($finder);
