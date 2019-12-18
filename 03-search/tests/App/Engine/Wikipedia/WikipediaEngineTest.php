<?php

namespace Test\App\Engine\Wikipedia;

use App\Engine\Wikipedia\WikipediaParser;
use App\Engine\Wikipedia\WikipediaEngine;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class WikipediaEngineTest extends TestCase
{
    /** @test */
    public function performSearch()
    {
        $content = file_get_contents(__DIR__ . '/../../../Resources/fixtures/wikipedia.html');
        $client = new MockHttpClient([new MockResponse($content)]);
        $engine = new WikipediaEngine(new WikipediaParser(), $client);
        $result = $engine->search('bdd');
        $this->assertSame(22, $result->count());
    }
}
