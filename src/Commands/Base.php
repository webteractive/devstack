<?php

namespace Webteractive\Devstack\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Webteractive\Devstack\CommandSignature;

abstract class Base extends Command
{
    protected $name;

    protected $signature;

    protected $description;

    protected $help;

    protected $hidden = false;

    protected $input;

    protected $output;

    public function __construct()
    {
        if (isset($this->signature)) {
            $this->setup();
        } else {
            parent::__construct($this->name);
        }

        $this->setDescription((string) $this->description);
        $this->setHelp((string) $this->help);
        $this->setHidden($this->isHidden());
    }
    
    abstract public function handle(): int;

    public function setup()
    {
        [$name, $arguments, $options] = CommandSignature::parse($this->signature);

        parent::__construct($name);

        $this->getDefinition()->addArguments($arguments);
        $this->getDefinition()->addOptions($options);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->setIO($input, $output)
            ->handle();
    }

    public function setIO($input, $output)
    {
        $this->input = $input;
        $this->output = $output;
        return $this;
    }

    public function argument($name, $default = null)
    {
        return $this->input->getArgument($name, $default);
    }

    public function option($name, $default = null)
    {
        return $this->input->getOption($name) ?? $default;
    }

    public function ask($question, $default = null)
    {
        $helper = $this->getHelper('question');
        $message = "<info>{$question}</info>";
        if ($default) {
            $message .= " <comment>($default)</comment>";
        }

        return optional($helper)->ask(
            $this->input,
            $this->output,
            new Question("{$message}\n", $default)
        );
    }

    public function choice($question, $options = [], $default = null)
    {
        $helper = $this->getHelper('question');
        $message = "<info>{$question}</info>";
        if ($default) {
            $message .= " <comment>($default)</comment>";
        }

        foreach ($options as $index => $value) {
            $message .= "\n<comment>{$value}</comment>";
        }

        $choice = new Question("{$message}\n", $default);
        $choice->setAutocompleterValues($options);

        return optional($helper)->ask(
            $this->input,
            $this->output,
            $choice
        );
    }

    public function line($message = '')
    {
        $this->output->writeln($message);
        return $this;
    }

    public function info($message = '')
    {
        $this->output->writeln(empty($message) ? '' : "<info>{$message}</info>");
        return $this;
    }

    public function error($message = '')
    {
        $this->output->writeln(empty($message) ? '' : "<error>{$message}</error>");
        return $this;
    }

    public function warn($message)
    {
        $this->output->writeln(empty($message) ? '' : "<comment>{$message}</comment>");
        return $this;
    }
}