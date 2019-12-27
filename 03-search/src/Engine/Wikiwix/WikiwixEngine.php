<?php

namespace App\Engine\Wikiwix;

use App\Engine\EngineInterface;
use App\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WikiwixEngine implements EngineInterface
{
    private const URI = 'http://www.wikiwix.com/index.php?lang=pt&art=true&disp=article&action=%s';

    /** @var WikiwixParser */
    private $parser;

    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * WikiwixEngine constructor.
     * @param WikiwixParser $parser
     * @param HttpClientInterface $client
     */
    public function __construct(WikiwixParser $parser, HttpClientInterface $client)
    {
        $this->parser = $parser;
        $this->client = $client;
    }

    public function search(string $term): Result
    {
        $url = sprintf(self::URI, $term);
        $response = $this->client->request('GET', $url);

        return $this->parser->parse($response->getContent());
    }

    public static function getName(): String
    {
        return 'wikiwix';
    }
}