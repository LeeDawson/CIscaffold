<?php

namespace OutSource\Console\Commands;

use OutSource\Console\OutputStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class BaseCommand extends SymfonyCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name;

    /**
     * The application.
     *
     * @var object
     */
    protected $container;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description;

    /**
     * Indicates whether the command should be shown in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = false;

    /**
     * The input interface implementation.
     *
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * The output interface implementation.
     *
     * @var \Illuminate\Console\OutputStyle
     */
    protected $output;

    /**
     * The mapping between human readable verbosity levels and Symfony's OutputInterface.
     *
     * @var array
     */
    protected $verbosityMap = [
        'v' => OutputInterface::VERBOSITY_VERBOSE,
        'vv' => OutputInterface::VERBOSITY_VERY_VERBOSE,
        'vvv' => OutputInterface::VERBOSITY_DEBUG,
        'quiet' => OutputInterface::VERBOSITY_QUIET,
        'normal' => OutputInterface::VERBOSITY_NORMAL,
    ];

    /**
     * The default verbosity of output commands.
     *
     * @var int
     */
    protected $verbosity = OutputInterface::VERBOSITY_NORMAL;

    /**
     * config info
     *
     * @var object
     */
    protected $config;


    public function __construct()
    {
        parent::__construct($this->name);

        $this->setDescription($this->description);
        $this->setHidden($this->hidden);

        foreach ($this->getArguments() as $argument) {
            $this->getDefinition()->addArgument($this->parseArgument($argument));
        }

        foreach ($this->getOptions() as $option) {
            $this->getDefinition()->addOption($this->parseOption($option));
        }
    }

    /**
     * 处理下参数转换成参数对象
     *
     * @param array $argument
     *
     * @return InputArgument
     */
    protected function parseArgument($argument)
    {
        if(count($argument) < 3) {
            throw new InvalidArgumentException('argument least three');
        }

        list($name,$model,$description) = $argument;

        if(empty($name) 
            || empty($description) || (!in_array(
                $model, [
                InputArgument::OPTIONAL,
                InputArgument::REQUIRED,
                InputArgument::IS_ARRAY,
                ]
            ))
        ) {
            throw new InvalidArgumentException('argument error');
        }

        if($model == InputArgument::OPTIONAL && empty($argument[3])) {
            throw new InvalidArgumentException('The default value (for self::OPTIONAL mode only)');
        }



        return new InputArgument(
            $name,
            $model,
            $description,
            $model == InputArgument::OPTIONAL ? $argument[3]: null
        );
    }

    /**
     * 处理下选项转换成选项对象
     *
     * @param array $argument
     *
     * @return InputArgument
     */
    protected function parseOption($options)
    {

        if(count($options) < 4) {
            throw new InvalidArgumentException('argument least four');
        }

        list($name,$shortcut,$model,$description) = $options;

        if(empty($name) 
            || empty($description) || (!in_array(
                $model, [
                InputOption::VALUE_NONE,
                InputOption::VALUE_REQUIRED,
                InputOption::VALUE_OPTIONAL,
                InputOption::VALUE_IS_ARRAY
                ]
            ))
        ) {
            throw new InvalidArgumentException('argument error');
        }

        return new InputOption(
            $name,
            $shortcut,
            $model,
            $description,
            $model == InputOption::VALUE_NONE  ? null: null
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected  function getArguments()
    {
        return [];
    }

    /**
     * Run the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface   $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        return parent::run(
            $this->input = $input, $this->output = new OutputStyle($input, $output)
        );
    }

    /**
     * Confirm a question with the user.
     *
     * @param  string $question
     * @param  bool   $default
     * @return bool
     */
    public function confirm($question, $default = false)
    {
        return $this->output->confirm($question, $default);
    }

    /**
     * Prompt the user for input.
     *
     * @param  string $question
     * @param  string $default
     * @return string
     */
    public function ask($question, $default = null)
    {
        return $this->output->ask($question, $default);
    }



    /**
     * Prompt the user for input but hide the answer from the console.
     *
     * @param  string $question
     * @param  bool   $fallback
     * @return string
     */
    public function secret($question, $fallback = true)
    {
        $question = new Question($question);

        $question->setHidden(true)->setHiddenFallback($fallback);

        return $this->output->askQuestion($question);
    }

    /**
     * Give the user a single choice from an array of answers.
     *
     * @param  string $question
     * @param  array  $choices
     * @param  string $default
     * @param  mixed  $attempts
     * @param  bool   $multiple
     * @return string
     */
    public function choice($question, array $choices, $default = null, $attempts = null, $multiple = null)
    {
        $question = new ChoiceQuestion($question, $choices, $default);

        $question->setMaxAttempts($attempts)->setMultiselect($multiple);

        return $this->output->askQuestion($question);
    }

    /**
     * Format input to textual table.
     *
     * @param  array                                         $headers
     * @param  \Illuminate\Contracts\Support\Arrayable|array $rows
     * @param  string                                        $style
     * @return void
     */
    public function table(array $headers, $rows, $style = 'default')
    {
        $table = new Table($this->output);

        if ($rows instanceof Arrayable) {
            $rows = $rows->toArray();
        }

        $table->setHeaders($headers)->setRows($rows)->setStyle($style)->render();
    }

    /**
     * Write a string as information output.
     *
     * @param  string          $string
     * @param  null|int|string $verbosity
     * @return void
     */
    public function info($string, $verbosity = null)
    {
        $this->line($string, 'info', $verbosity);
    }

    /**
     * Write a string as standard output.
     *
     * @param  string          $string
     * @param  string          $style
     * @param  null|int|string $verbosity
     * @return void
     */
    public function line($string, $style = null, $verbosity = null)
    {
        $styled = $style ? "<$style>$string</$style>" : $string;

        $this->output->writeln($styled, $this->parseVerbosity($verbosity));
    }

    /**
     * Write a string as comment output.
     *
     * @param  string          $string
     * @param  null|int|string $verbosity
     * @return void
     */
    public function comment($string, $verbosity = null)
    {
        $this->line($string, 'comment', $verbosity);
    }

    /**
     * Write a string as question output.
     *
     * @param  string          $string
     * @param  null|int|string $verbosity
     * @return void
     */
    public function question($string, $verbosity = null)
    {
        $this->line($string, 'question', $verbosity);
    }

    /**
     * Write a string as error output.
     *
     * @param  string          $string
     * @param  null|int|string $verbosity
     * @return void
     */
    public function error($string, $verbosity = null)
    {
        $this->line($string, 'error', $verbosity);
    }

    /**
     * Write a string as warning output.
     *
     * @param  string          $string
     * @param  null|int|string $verbosity
     * @return void
     */
    public function warn($string, $verbosity = null)
    {
        if (! $this->output->getFormatter()->hasStyle('warning')) {
            $style = new OutputFormatterStyle('yellow');

            $this->output->getFormatter()->setStyle('warning', $style);
        }

        $this->line($string, 'warning', $verbosity);
    }

    /**
     * Get the verbosity level in terms of Symfony's OutputInterface level.
     *
     * @param  string|int $level
     * @return int
     */
    protected function parseVerbosity($level = null)
    {
        if (isset($this->verbosityMap[$level])) {
            $level = $this->verbosityMap[$level];
        } elseif (! is_int($level)) {
            $level = $this->verbosity;
        }

        return $level;
    }

    //入口
    public function handle()
    {
        //让commandData准备数据
        $this->commandData->getFields($this->input);

        $this->preOptions();
        //启动config
        $this->config->init($this->commandData);
    }

    public function preOptions()
    {
        //把option放到config准备好
        $this->config->setOptions($this->input);
    }

    /**
     * Get the value of a command argument.
     *
     * @param  string|null $key
     * @return string|array
     */
    public function argument($key = null)
    {
        if (is_null($key)) {
            return $this->input->getArguments();
        }

        return $this->input->getArgument($key);
    }

    /**
     * @param $fileName
     * @param string   $prompt
     *
     * @return bool
     */
    protected function confirmOverwrite($fileName, $prompt = '')
    {
        $prompt = (empty($prompt))
            ? $fileName.' already exists. Do you want to overwrite it? [y|N]'
            : $prompt;

        return $this->confirm($prompt, false);
    }

    /**
     * Get the value of a command option.
     *
     * @param  string $key
     * @return string|array
     */
    public function option($key = null)
    {
        if (is_null($key)) {
            return $this->input->getOptions();
        }

        return $this->input->getOption($key);
    }



    /**
     * Get all of the options passed to the command.
     *
     * @return array
     */
    public function options()
    {
        return $this->option();
    }

    public function isSkip($skip)
    {
        if ($this->config->getOption('skip')) {
            return in_array($skip, (array) $this->config->getOption('skip'));
        }

        return false;
    }


}