<?php

declare(strict_types=1);

namespace ASK\Svg;

use ASK\Svg\Components\Icon;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * BladeIconsServiceProvider
 * @ignore
 */
final class BladeIconsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();
        $this->registerFactory();
        $this->registerManifest();
    }

    public function boot(): void
    {
        $this->bootCommands();
        $this->bootDirectives();
        $this->bootIconComponent();
        $this->bootPublishing();
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/blade-icons.php', 'blade-icons');

        if (file_exists(base_path('ask-svg.json'))) {
            $config = $this->app->make('config');
            $configO = $config->get('blade-icons', []);
            $configFromJ = json_decode(file_get_contents(base_path('ask-svg.json')), true);
            $result = array_merge_recursive_distinct($configO, $configFromJ);
            $config->set('blade-icons', $result);
        }
    }

    private function makeConfigFile(array $config)
    {
        $configFile = '<?php' . PHP_EOL;
        $configFile .= 'return [' . PHP_EOL;
        foreach ($config as $key => $value) {
            $configFile .= $this->writeKeyValue($key, $value);
        }
        $configFile .= '];';
        file_put_contents(__DIR__ . '/../config/config.php', $configFile);
    }

    private function writeKeyValue(string $key, $value)
    {
        if (is_array($value)) {
            $return = "'" . $key . "' => [" . PHP_EOL;
            foreach ($value as $k => $val) {
                $return .= $this->writeKeyValue($k, $val);
            }
            $return .= '],' . PHP_EOL;
        } else {
            $return = "'" . $key . "' => '" . ($value === false ? 'false' : ($value === true ? 'true' : $value)) . "'," . PHP_EOL;
        }
        return $return;
    }

    private function registerFactory(): void
    {
        $this->app->singleton(Factory::class, function (Application $app) {
            $config = $app->make('config')->get('blade-icons', []);

            $factory = new Factory(
                new Filesystem(),
                $app->make(IconsManifest::class),
                $app->make(FilesystemFactory::class),
                $config,
            );

            foreach ($config['sets'] ?? [] as $set => $options) {
                if (!isset($options['disk']) || !$options['disk']) {
                    $paths = $options['paths'] ?? $options['path'] ?? [];

                    $options['paths'] = array_map(
                        fn ($path) => $app->basePath($path),
                        (array) $paths,
                    );
                }

                $factory->add($set, $options);
            }

            return $factory;
        });

        $this->callAfterResolving(ViewFactory::class, function ($view, Application $app) {
            $app->make(Factory::class)->registerComponents();
        });
    }

    private function registerManifest(): void
    {
        $this->app->singleton(IconsManifest::class, function (Application $app) {
            return new IconsManifest(
                new Filesystem(),
                $this->manifestPath(),
                $app->make(FilesystemFactory::class),
            );
        });
    }

    private function manifestPath(): string
    {
        return $this->app->bootstrapPath('cache/blade-icons.php');
    }

    private function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\CacheCommand::class,
                Console\ClearCommand::class,
            ]);
        }
    }

    private function bootDirectives(): void
    {
        Blade::directive('svg', fn ($expression) => "<?php echo e(svg($expression)); ?>");
    }

    private function bootIconComponent(): void
    {
        if ($name = config('blade-icons.components.default')) {
            Blade::component($name, Icon::class);
        }
    }

    private function bootPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/blade-icons.php' => $this->app->configPath('blade-icons.php'),
            ], 'blade-icons');
        }
    }
}
