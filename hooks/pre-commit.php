<?php
define('VENDOR_DIR', __DIR__.'/../../vendor');
require VENDOR_DIR.'/autoload.php';

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Process\Process;

class CodeQualityTool extends Application
{
    /**
     * @var OutputInterface
     */
    private $output;
    /**
     * @var InputInterface
     */
    private $input;

    const PHP_FILES_IN_SRC = '/^src\/(.*)(\.php)$/';

    public function __construct()
    {
        parent::__construct('Ecombo Quality Tool', '1.0.0');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws \Exception
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $output->writeln('<fg=white;options=bold;bg=red>Code Quality Tool</fg=white;options=bold;bg=red>');
        $output->writeln('<info>Fetching files</info>');
        $files = $this->extractCommitedFiles();
        $output->writeln('<info>Running PHPLint</info>');

        if (! $this->phpLint($files)) {
            throw new \Exception('There are some PHP syntax errors!');
        }

        $output->writeln('<info>Checking code style with PHPCS</info>');

        if (! $this->codeStylePsr($files)) {
            throw new \Exception(sprintf('There are PHPCS coding standards violations!'));
        }

        $output->writeln('<info>Well done!</info>');
    }

    /**
     * @return array
     */
    private function extractCommitedFiles()
    {
        $output = array();
        $against = 'HEAD';
        exec("git diff-index --cached --name-status $against | egrep '^(A|M)' | awk '{print $2;}'", $output);

        return $output;
    }

    /**
     * @param array $files
     *
     * @return bool
     *
     * @throws \Exception
     */
    private function phpLint($files)
    {
        $needle = '/(\.php)|(\.inc)$/';
        $succeed = true;

        foreach ($files as $file) {
            if (! preg_match($needle, $file)) {
                continue;
            }

            $process = new Process(['php', '-l', $file]);
            $process->run();

            if (! $process->isSuccessful()) {
                $this->output->writeln($file);
                $this->output->writeln(sprintf('<error>%s</error>', trim($process->getErrorOutput())));

                if ($succeed) {
                    $succeed = false;
                }
            }
        }

        return $succeed;
    }

    /**
     * @param array $files
     *
     * @return bool
     */
    private function codeStylePsr(array $files)
    {
        $succeed = true;
        $needle = self::PHP_FILES_IN_SRC;
        $standard = 'PSR2';

        foreach ($files as $file) {
            if (! preg_match($needle, $file)) {
                continue;
            }

            $phpCsFixer = new Process([
                'php',
                VENDOR_DIR.'/bin/phpcs',
                '-n',
                '--standard='.$standard,
                $file,
            ]);

            $phpCsFixer->setWorkingDirectory(__DIR__.'/../../');
            $phpCsFixer->run();

            if (! $phpCsFixer->isSuccessful()) {
                $this->output->writeln(sprintf('<error>%s</error>', trim($phpCsFixer->getOutput())));

                if ($succeed) {
                    $succeed = false;
                }
            }
        }

        return $succeed;
    }
}

$console = new CodeQualityTool();
$console->run();