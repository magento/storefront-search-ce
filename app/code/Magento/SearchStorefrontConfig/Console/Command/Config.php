<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types = 1);
namespace Magento\SearchStorefrontConfig\Console\Command;

use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\FileSystemException;
use Magento\SearchStorefrontConfig\Model\Installer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for search service minimum config set up
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Config extends Command
{
    /**
     * Command name
     *
     * @var string
     */
    private const COMMAND_NAME = 'storefront:search:init';

    /**
     * @var Installer
     */
    private $installer;

    /**
     * Installer constructor.
     *
     * @param Installer $installer
     */
    public function __construct(
        Installer $installer
    ) {
        parent::__construct();
        $this->installer = $installer;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription(
                'Adds minimum required config data to env.php'
            )
            ->setDefinition($this->getOptionsList());

        parent::configure();
    }

    /**
     * @inheritDoc
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     * @throws FileSystemException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->installer->install(
                $input->getArguments(),
                $input->getOptions()
            );
        } catch (\Throwable $exception) {
            $output->writeln('Installation failed: ' . $exception->getMessage());
            return Cli::RETURN_FAILURE;
        }
        $output->writeln('Installation complete');

        return Cli::RETURN_SUCCESS;
    }

    /**
     * Provides options for command config
     *
     * @return array
     */
    private function getOptionsList()
    {
        return [
            new InputArgument(
                Installer::DB_HOST,
                InputArgument::REQUIRED,
                'Database hostname'
            ),
            new InputArgument(
                Installer::DB_NAME,
                InputArgument::REQUIRED,
                'Database name',
            ),
            new InputArgument(
                Installer::DB_USER,
                InputArgument::REQUIRED,
                'Database user'
            ),
            new InputArgument(
                Installer::DB_PASSWORD,
                InputArgument::REQUIRED,
                'Database password'
            ),
            new InputOption(
                Installer::DB_TABLE_PREFIX,
                null,
                InputOption::VALUE_OPTIONAL,
                'Database table prefix',
                ''
            ),
            new InputArgument(
                Installer::ES_ENGINE,
                InputArgument::REQUIRED,
                'Elasticsearch engine'
            ),
            new InputArgument(
                Installer::ES_HOSTNAME,
                InputArgument::REQUIRED,
                'Elasticsearch hostname'
            ),
            new InputOption(
                Installer::ES_PORT,
                null,
                InputOption::VALUE_OPTIONAL,
                'Elasticsearch port',
                '9200'
            ),
            new InputOption(
                Installer::ES_USERNAME,
                null,
                InputOption::VALUE_OPTIONAL,
                'Elasticsearch port'
            ),new InputOption(
                Installer::ES_PASSWORD,
                null,
                InputOption::VALUE_OPTIONAL,
                'Elasticsearch port'
            ),
            new InputArgument(
                Installer::ES_INDEX_PREFIX,
                InputArgument::REQUIRED,
                'Elasticsearch index prefix'
            )
        ];
    }
}
