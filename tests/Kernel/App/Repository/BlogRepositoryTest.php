<?php

namespace App\Tests\Kernel\App\Repository;

use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BlogRepositoryTest extends KernelTestCase
{
    public function testSomething(): void
    {
        self::bootKernel();

        $blogRepository = static::getContainer()->get(BlogRepository::class);

        $result = $blogRepository->getBlogs();

        dump($result);
    }
}
