<?php
namespace App\Composer;

use Composer\Script\Event;

class ScriptHandler
{
    /**
     * @param Event $event
     *
     * @return bool
     */
    public static function preHooks(Event $event)
    {
        $io = $event->getIO();
        $gitHook = '.git/hooks/pre-commit';

        if (file_exists($gitHook)) {
            unlink($gitHook);
            $io->write('<info>Pre-commit hook removed!</info>');
        }

        return true;
    }

    /**
     * @param Event $event
     *
     * @return bool
     *
     * @throws \Exception
     */
    public static function postHooks(Event $event)
    {
        /** @var array $extras */
        $extras = $event->getComposer()->getPackage()->getExtra();

        if (! array_key_exists('hooks', $extras)) {
            throw new \InvalidArgumentException('The parameter handler needs to be configured through the extra.hooks setting.');
        }
        $configs = $extras['hooks'];
        if (! array_key_exists('pre-commit', $configs)) {
            throw new \InvalidArgumentException('The parameter handler needs to be configured through the extra.hooks.pre-commit setting.');
        }

        if (file_exists('.git/hooks')) {
            /** @var \Composer\IO\IOInterface $io */
            $io = $event->getIO();
            $gitHook = '.git/hooks/pre-commit';
            $docHook = $configs['pre-commit'];
            copy($docHook, $gitHook);
            chmod($gitHook, 0777);
            $io->write('<info>Pre-commit hook created!</info>');
        }

        return true;
    }
}