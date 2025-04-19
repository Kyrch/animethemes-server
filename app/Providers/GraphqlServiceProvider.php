<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\Models\List\ExternalEntryWatchStatus;
use App\Enums\Models\List\ExternalProfileSite;
use App\Enums\Models\List\ExternalProfileVisibility;
use App\Enums\Models\List\PlaylistVisibility;
use App\Enums\Models\Wiki\AnimeMediaFormat;
use App\Enums\Models\Wiki\AnimeSeason;
use App\Enums\Models\Wiki\AnimeSynonymType;
use App\Enums\Models\Wiki\ImageFacet;
use App\Enums\Models\Wiki\ResourceSite;
use App\Enums\Models\Wiki\ThemeType;
use App\Enums\Models\Wiki\VideoOverlap;
use App\Enums\Models\Wiki\VideoSource;
use App\GraphQL\Types\EnumType;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Schema\TypeRegistry;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class GraphqlServiceProvider.
 */
class GraphqlServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if (app()->isLocal()) {
            Auth::login(User::find(1));
        }

        $this->bootModels();
        $this->bootEnums();
    }

    /**
     * Boot the model namespaces so schema can find them.
     *
     * @return void
     */
    protected function bootModels(): void
    {
        $modelNamespaces = Config::get('lighthouse.namespaces.models', []);

        $modelsBasePaths = [
            'App\\Models' => app_path('Models'),
            'App\\Pivots' => app_path('Pivots'),
        ];

        foreach ($modelsBasePaths as $baseNamespace => $basePath) {
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($basePath)) as $file) {
                if ($file->isDir() || $file->getExtension() !== 'php') {
                    continue;
                }

                $relativePath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $file->getPath());
                $relativeNamespace = $relativePath ? '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath) : '';

                $namespace = $baseNamespace . $relativeNamespace;

                if (!in_array($namespace, $modelNamespaces)) {
                    $modelNamespaces[] = $namespace;
                }
            }
        }

        Config::set('lighthouse.namespaces.models', $modelNamespaces);
    }

    /**
     * Register the enums to use as type.
     *
     * @return void
     */
    protected function bootEnums(): void
    {
        $typeRegistry = app(TypeRegistry::class);
        $typeRegistry->register(new EnumType(ExternalEntryWatchStatus::class));
        $typeRegistry->register(new EnumType(ExternalProfileSite::class));
        $typeRegistry->register(new EnumType(ExternalProfileVisibility::class));
        $typeRegistry->register(new EnumType(PlaylistVisibility::class));
        $typeRegistry->register(new EnumType(AnimeMediaFormat::class));
        $typeRegistry->register(new EnumType(AnimeSeason::class));
        $typeRegistry->register(new EnumType(AnimeSynonymType::class));
        $typeRegistry->register(new EnumType(ImageFacet::class));
        $typeRegistry->register(new EnumType(ResourceSite::class));
        $typeRegistry->register(new EnumType(ThemeType::class));
        $typeRegistry->register(new EnumType(VideoOverlap::class));
        $typeRegistry->register(new EnumType(VideoSource::class));
    }
}
