<?php

namespace AppBundle\Command;

use AppBundle\Entity\FioulPrice;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportCSVCommand
 * @package AppBundle\Command
 * @codeCoverageIgnore
 */
class ImportCSVCommand extends ContainerAwareCommand
{
    /**
     * @{inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('import:csv')
            ->setDescription('Import CSV to DB using Doctrine')
            ->addArgument('file', InputArgument::REQUIRED, 'File to import')
        ;
    }

    /**
     * @{inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = new \DateTime();
        $output->writeln('<comment>Start : '.$start->format('d-m-Y G:i:s').'</comment>');
        $this->import($input, $output);
        $end = new \DateTime();
        $duration = $end->diff($start);
        $output->writeln('');
        $output->writeln('<comment>End : '.$end->format('d-m-Y G:i:s').'</comment>');
        $output->writeln('<info>Duration : '.$duration->format('%h h %i m %s s').'</info>');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function import(InputInterface $input, OutputInterface $output)
    {
        $data = $this->getData($input->getArgument('file'));

        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $size = count($data);
        $batchSize = 10000;
        $i = 1;

        $progress = new ProgressBar($output, $size);
        $progress->start();

        foreach ($data as $row) {
            if (count($row) == 3) {
                $user = new FioulPrice();
                $user->setAmount((float) $row[1]);
                $user->setDate((new \DateTime())->setTimestamp(strtotime($row[2])));
                $user->setZipCode((int) $row[0]);
                $em->persist($user);
            }

            if (($i % $batchSize) === 0) {
                $em->flush();
                $em->clear();
                $progress->advance($batchSize);
            }

            ++$i;
        }

        $em->flush();
        $em->clear();
        $progress->finish();
    }

    /**
     * @param $fileName
     * @return array|bool
     */
    protected function getData($fileName)
    {
        $converter = $this->getContainer()->get('app.services.csv_to_array');
        $data = $converter->convert($fileName);

        return $data;
    }
}
