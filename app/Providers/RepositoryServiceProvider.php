<?php

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\BrandRepository\BrandRepository;
use App\Repositories\BrandRepository\BrandRepositoryInterface;
use App\Repositories\CategoryRepository\CategoryRepository;
use App\Repositories\CategoryRepository\CategoryRepositoryInterface;
use App\Repositories\FileRepository\FileRepository;
use App\Repositories\FileRepository\FileRepositoryInterface;
use App\Repositories\JwtTokenRepository\JwtTokenRepository;
use App\Repositories\JwtTokenRepository\JwtTokenRepositoryInterface;
use App\Repositories\OrderRepository\OrderRepository;
use App\Repositories\OrderRepository\OrderRepositoryInterface;
use App\Repositories\OrderStatusRepository\OrderStatusRepository;
use App\Repositories\OrderStatusRepository\OrderStatusRepositoryInterface;
use App\Repositories\PaymentRepository\PaymentRepository;
use App\Repositories\PaymentRepository\PaymentRepositoryInterface;
use App\Repositories\ProductRepository\ProductRepository;
use App\Repositories\ProductRepository\ProductRepositoryInterface;
use App\Repositories\UserRepository\UserRepository;
use App\Repositories\UserRepository\UserRepositoryInterface;
use App\Repository\BaseRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    private array $repositories = [
        BaseRepositoryInterface::class => BaseRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
        ProductRepositoryInterface::class => ProductRepository::class,
        OrderRepositoryInterface::class => OrderRepository::class,
        BrandRepositoryInterface::class => BrandRepository::class,
        CategoryRepositoryInterface::class => CategoryRepository::class,
        PaymentRepositoryInterface::class => PaymentRepository::class,
        FileRepositoryInterface::class => FileRepository::class,
        JwtTokenRepositoryInterface::class => JwtTokenRepository::class,
        OrderStatusRepositoryInterface::class => OrderStatusRepository::class,

    ];

    public function register()
    {
        foreach ($this->repositories as $contracts => $eloquentClass) {
            $this->app->bind(
                $contracts,
                $eloquentClass
            );
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
