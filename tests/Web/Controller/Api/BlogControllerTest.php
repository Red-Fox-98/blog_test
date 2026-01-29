<?php

namespace App\Tests\Web\Controller\Api;

use App\Factory\BlogFactory;
use App\Factory\UserFactory;
use App\Repository\BlogRepository;
use App\Tests\Helpers\WebTestCaseUnit;
use Helmich\JsonAssert\JsonAssertions;

final class BlogControllerTest extends WebTestCaseUnit
{
    use JsonAssertions;

    public function testIndex(): void
    {
        $client = static::createClient();

        $user = UserFactory::createOne();

        BlogFactory::createMany(10, ['user' => $user]);

        $client->request('GET', '/api/blog');

        $this->assertResponseIsSuccessful();
    }

    public function testAdd(): void
    {
        $client = static::createClient();

        $user = UserFactory::createOne();

        $client->loginUser($user);

        $content = '{
            "title": "Blog title",
            "description": "Description",
            "text": "Blog text",
            "tags": "tag1, tag2, tag3"
        }';

        $client->request('POST', '/api/blog', content: $content);

        $json = json_decode($client->getResponse()->getContent(), true);

        $this->assertJsonValueEquals($json, '$.title', 'Blog title');
    }

    public function testUpdate(): void
    {
        $client = static::createClient();

        $user = UserFactory::createOne();

        $client->loginUser($user);

        $blog = BlogFactory::createOne(['user' => $user]);

        $blogId = $blog->getId();

        $content = [
            "title" => "Blog title",
            "description" => "Description",
            "text" => "Blog text",
            "tags" => "tag1,tag2,tag3"
        ];

        $client->request('PUT', '/api/blog/' . $blogId, content: json_encode($content));

        $json = json_decode($client->getResponse()->getContent(), true);

        $this->assertJsonValueEquals($json, '$.title', 'Blog title');
    }

    public function testDelete(): void
    {
        $client = static::createClient();

        $user = UserFactory::createOne();

        $client->loginUser($user);

        $blog = BlogFactory::createOne(['user' => $user]);

        $blogId = $blog->getId();

        $client->request('DELETE', '/api/blog/' . $blogId);

        $blogRepository = static::getContainer()->get(BlogRepository::class);

        self::assertNull($blogRepository->find($blogId));
    }
}
