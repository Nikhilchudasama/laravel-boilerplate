<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector;
use Rector\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector;
use Rector\Naming\Rector\Class_\RenamePropertyToMatchTypeRector;
use Rector\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use RectorLaravel\Rector\Class_\UnifyModelDatesWithCastsRector;
use RectorLaravel\Rector\FuncCall\FactoryFuncCallToStaticCallRector;
use RectorLaravel\Rector\FuncCall\RemoveDumpDataDeadCodeRector;
use RectorLaravel\Rector\MethodCall\ChangeQueryWhereDateValueWithCarbonRector;
use RectorLaravel\Rector\PropertyFetch\OptionalToNullsafeOperatorRector;
use RectorLaravel\Set\LaravelSetList;
use RectorLaravel\Set\LaravelSetProvider;

return RectorConfig::configure()
    ->withParallel()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->withSets([
        // Core Rector sets
        SetList::CODING_STYLE,
        SetList::EARLY_RETURN,
        SetList::NAMING,
        SetList::PRIVATIZATION,
        SetList::TYPE_DECLARATION,
        SetList::DEAD_CODE,

        // PHP version
        LevelSetList::UP_TO_PHP_82,

        // Laravel sets
        LaravelSetList::LARAVEL_120,
        LaravelSetList::LARAVEL_ARRAY_STR_FUNCTION_TO_STATIC_CALL,
        LaravelSetList::ARRAY_STR_FUNCTIONS_TO_STATIC_CALL,
        LaravelSetList::LARAVEL_LEGACY_FACTORIES_TO_CLASSES,
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_TYPE_DECLARATIONS,
    ])
    ->withImportNames()
    ->withSetProviders(LaravelSetProvider::class)
    ->withRules([
        FactoryFuncCallToStaticCallRector::class,
        OptionalToNullsafeOperatorRector::class,
        RemoveDumpDataDeadCodeRector::class,
        UnifyModelDatesWithCastsRector::class,
        ChangeQueryWhereDateValueWithCarbonRector::class,
        JsonThrowOnErrorRector::class,
    ])
    ->withSkip([
        // Naming rules that can be too aggressive
        RemoveNonExistingVarAnnotationRector::class,
        RenamePropertyToMatchTypeRector::class,
        RenameParamToMatchTypeRector::class,
        RenameVariableToMatchMethodCallReturnTypeRector::class,

        // Type strictness
        NullToStrictStringFuncCallArgRector::class,
        ReadOnlyClassRector::class,
    ]);
