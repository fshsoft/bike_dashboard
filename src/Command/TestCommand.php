<?php

namespace Bike\Dashboard\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bike:dashboard:test')
            ->setDescription('测试命令')
            ->setHelp($this->getCommandHelp())
            ->addArgument('name', InputArgument::OPTIONAL, '姓名')
            ->addOption('age', 'a', InputOption::VALUE_OPTIONAL, '年龄');
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (null !== $input->getArgument('name')) {
            return;
        }

        $output->writeln('');
        $output->writeln('bike:dashboard:test 命令交互模式');
        $output->writeln('-----------------------------------');

        $output->writeln(array(
            '',
            '如果你不倾向于交互模式，请按下面的格式输入命令',
            '',
            ' $ php app/console bike:dashboard:test name',
            '',
        ));

        $console = $this->getHelper('question');

        $name = $input->getArgument('name');
        if (null === $name) {
            $question = new Question(' > <info>姓名</info>: ');
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException('姓名不能为空');
                }
                return $answer;
            });
            $name = $console->ask($input, $output, $question);
            $input->setArgument('name', $name);
        } else {
            $output->writeln(' > <info>姓名</info>: ' . $name);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $age = 100;
        if ($input->getOption('age')) {
            $age = $input->getOption('age');
        }

        $output->writeln('> ' . $name . ' ' . $age);
    }

    private function getCommandHelp()
    {
        return <<<HELP
<info>%command.name%</info>
HELP;
    }
}
