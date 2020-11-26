<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefrontElasticsearch7\Model\Client;

use Magento\Framework\Exception\LocalizedException;
use Magento\SearchStorefrontElasticsearch\Model\Client\ClientInterface;

/**
 * Elasticsearch client
 */
class Elasticsearch implements ClientInterface
{
    /**
     * @var array
     */
    private $clientOptions;

    /**
     * Elasticsearch Client instances
     *
     * @var \SearchStorefrontElasticsearch\Client[]
     */
    private $client;

    /**
     * @var bool
     */
    private $pingResult;

    /**
     * Initialize Elasticsearch 7 Client
     *
     * @param  array                                      $options
     * @param  \SearchStorefrontElasticsearch\Client|null $elasticsearchClient
     * @throws LocalizedException
     */
    public function __construct(
        $options = [],
        $elasticsearchClient = null
    ) {
        if (empty($options['hostname'])
            || ((!empty($options['enableAuth']) && ($options['enableAuth'] == 1))
            && (empty($options['username']) || empty($options['password'])))
        ) {
            throw new LocalizedException(
                __('The search failed because of a search engine misconfiguration.')
            );
        }
        // phpstan:ignore
        if ($elasticsearchClient instanceof \SearchStorefrontElasticsearch\Client) {
            $this->client[getmypid()] = $elasticsearchClient;
        }
        $this->clientOptions = $options;
    }

    /**
     * Execute suggest query for Elasticsearch 7
     *
     * @param  array $query
     * @return array
     */
    public function suggest(array $query): array
    {
        return $this->getElasticsearchClient()->suggest($query);
    }

    /**
     * Get Elasticsearch 7 Client
     *
     * @return \Elasticsearch\Client
     */
    private function getElasticsearchClient(): \Elasticsearch\Client
    {
        $pid = getmypid();
        if (!isset($this->client[$pid])) {
            $config = $this->buildESConfig($this->clientOptions);
            $this->client[$pid] = \Elasticsearch\ClientBuilder::fromConfig($config, true);
        }
        return $this->client[$pid];
    }

    /**
     * Ping the Elasticsearch 7 client
     *
     * @return bool
     */
    public function ping(): bool
    {
        if ($this->pingResult === null) {
            $this->pingResult = $this->getElasticsearchClient()
                ->ping(['client' => ['timeout' => $this->clientOptions['timeout']]]);
        }

        return $this->pingResult;
    }

    /**
     * Validate connection params for Elasticsearch 7
     *
     * @return bool
     */
    public function testConnection(): bool
    {
        return $this->ping();
    }

    /**
     * Build config for Elasticsearch 7
     *
     * @param  array $options
     * @return array
     */
    private function buildESConfig(array $options = []): array
    {
        $hostname = preg_replace('/http[s]?:\/\//i', '', $options['hostname']);
        // @codingStandardsIgnoreStart
        $protocol = parse_url($options['hostname'], PHP_URL_SCHEME);
        // @codingStandardsIgnoreEnd
        if (!$protocol) {
            $protocol = 'http';
        }

        $authString = '';
        if (!empty($options['enableAuth']) && (int)$options['enableAuth'] === 1) {
            $authString = "{$options['username']}:{$options['password']}@";
        }

        $portString = '';
        if (!empty($options['port'])) {
            $portString = ':' . $options['port'];
        }

        $host = $protocol . '://' . $authString . $hostname . $portString;

        $options['hosts'] = [$host];

        return $options;
    }

    /**
     * Get alias.
     *
     * @param  string $alias
     * @return array
     */
    public function getAlias(string $alias): array
    {
        return $this->getElasticsearchClient()->indices()->getAlias(['name' => $alias]);
    }

    /**
     * Execute search by $query
     *
     * @param  array $query
     * @return array
     */
    public function query(array $query): array
    {
        return $this->getElasticsearchClient()->search($query);
    }
}
