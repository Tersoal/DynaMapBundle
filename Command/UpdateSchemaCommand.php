<?php

namespace Tersoal\DynaMapBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\DoctrineCommandHelper;
use Doctrine\ORM\Tools\SchemaTool;

use Tersoal\DynaMapBundle\Tool\ModelTool;

/**
 * Command copied from Doctrine to generate all SQL needed to update schema, including dynamic entities.
 *
 * @author Sergio Rebollo "Tersoal" <tersoald20@gmail.com>
 *
 * Original authors:
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 */
class UpdateSchemaCommand extends UpdateSchemaDoctrineCommand
{
    protected $modelTool;

    public function __construct(ModelTool $modelTool)
    {
        $this->modelTool = $modelTool;

        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        DoctrineCommandHelper::setApplicationEntityManager($this->getApplication(), $input->getOption('em'));
        $emHelper = $this->getHelper('em');
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $emHelper->getEntityManager();
        $this->modelTool->setEntityManager($em)->setModelData();

        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        if (empty($metadatas)) {
            $output->writeln('No Metadata Classes to process.');
            return 0;
        }

        $tool = new SchemaTool($em);
        $this->executeSchemaCommand($input, $output, $tool, $metadatas);

        $output->writeln('');
        $output->writeln("<bg=green;options=bold>\n    Updating Dyna Map entities...\n</>");
        $output->writeln('');

        try {
            $metadatas = $this->modelTool->getAllModelMetadatas();

            return $this->executeSchemaCommand($input, $output, $tool, $metadatas);

        } catch (\Exception $e) {
            $output->writeln("<error>\n" . $e->getMessage() . "\n</error>");
            $output->writeln('');

            if ($e->getPrevious()->getCode() == '42S22') {
                $output->writeln("<bg=green;options=bold>\n    The dynamic entities will be updated successfully with <comment>--force option</>.\n</>");
            }

            return 1;
        }
    }
}
