<?php

declare(strict_types=1);

/*
 * This file is part of Chevere.
 *
 * (c) Rodolfo Berrios <rodolfo@chevereto.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Chevere\App;

use LogicException;
use Chevere\Api\Api;
use Chevere\Api\Maker as ApiMaker;
use Chevere\Router\Maker as RouterMaker;
use Chevere\App\Exceptions\AlreadyBuiltException;
use Chevere\Contracts\App\BuildContract;
use Chevere\Contracts\App\BuilderContract;
use Chevere\Contracts\App\CheckoutContract;
use Chevere\Contracts\App\ParametersContract;
use Chevere\File\File;
use Chevere\Message\Message;
use Chevere\Path\Path;
use Chevere\Path\PathHandle;
use Chevere\Router\Router;

final class Build implements BuildContract
{
    /** @var BuilderContract */
    private $builder;

    /** @var Container */
    private $container;

    /** @var ParametersContract */
    private $parameters;

    /** @var PathHandle */
    private $pathHandle;

    /** @var bool True if the App was built (cache) */
    private $isBuilt;

    /** @var CheckoutContract */
    private $checkout;

    /** @var array Containing the collection of Cache->toArray() data (checksums) */
    private $cacheChecksums;

    /** @var ApiMaker */
    private $apiMaker;

    /** @var RouterMaker */
    private $routerMaker;

    public function __construct(BuilderContract $builder)
    {
        $this->builder = $builder;
        $this->container = new Container();
        $this->pathHandle = (new PathHandle(BuildContract::FILE_INDETIFIER));
    }

    public function withContainer(Container $container): BuildContract
    {
        $new = clone $this;
        $new->container = $container;

        return $new;
    }

    public function withParameters(ParametersContract $parameters): BuildContract
    {
        if ($this->isBuilt) {
            throw new AlreadyBuiltException();
        }
        $new = clone $this;
        $new->routerMaker = new RouterMaker();
        $new->parameters = $parameters;
        $new->cacheChecksums = [];
        if (!empty($parameters->api())) {
            $new->handleApi();
        }
        if (!empty($parameters->routes())) {
            $new->handleRoutes();
        }
        $new->checkout = new Checkout($new);
        $new->isBuilt = true;
        $new->builder = $new->builder
            ->withParameters($parameters);

        return $new;
    }

    public function file(): File
    {
        return $this->pathHandle->file();
    }

    public function path(): Path
    {
        return $this->pathHandle->path();
    }

    public function container(): Container
    {
        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    public function cacheChecksums(): array
    {
        return $this->cacheChecksums;
    }

    public function checkout(): CheckoutContract
    {
        $this->assertCheckout();

        return $this->checkout;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy(): void
    {
        $this->pathHandle->file()->remove();
        (new PathHandle('cache'))->path()
            ->removeContents();
    }

    private function handleApi(): void
    {
        $this->apiMaker = new ApiMaker($this->routerMaker);
        $this->apiMaker = $this->apiMaker
            ->withPath(
                (new PathHandle($this->parameters->api()))
                    ->path()
            );
        $this->container = $this->container
            ->withApi(
                (new Api())
                    ->withMaker($this->apiMaker)
            );
        $this->apiMaker = $this->apiMaker
            ->withCache();
        $this->cacheChecksums = $this->apiMaker->cache()->toArray();
    }

    private function handleRoutes(): void
    {
        $this->routerMaker = $this->routerMaker
            ->withAddedRouteIdentifiers($this->parameters->routes());
        $this->container = $this->container
            ->withRouter(
                (new Router())
                    ->withMaker($this->routerMaker)
            );
        $this->routerMaker = $this->routerMaker
            ->withCache();
        $this->cacheChecksums = array_merge($this->routerMaker->cache()->toArray(), $this->cacheChecksums);
    }

    private function assertCheckout(): void
    {
        if (!isset($this->checkout)) {
            throw new LogicException(
                (new Message("Property %type% %property% is not set for object of %class% class"))
                    ->code('%type%', CheckoutContract::class)
                    ->code('%property%', 'checkout')
                    ->code('%class%', __CLASS__)
                    ->toString()
            );
        }
    }
}
