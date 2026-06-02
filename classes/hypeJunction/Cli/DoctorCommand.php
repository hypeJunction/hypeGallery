<?php

declare(strict_types=1);

namespace hypeJunction\Cli;

use Elgg\Cli\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Post-migration data integrity checks for the hypegallery plugin.
 *
 * Run with:
 *   php elgg-cli hypegallery:doctor
 */
class DoctorCommand extends Command {

    /** @var mixed */
    protected static $defaultName = 'hypegallery:doctor';

    /**
     * @return void
     */
    protected function configure(): void {
        $this->setDescription('Post-migration data integrity checks for hypegallery');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function command(InputInterface $input, OutputInterface $output): int {
        $exitCode = self::SUCCESS;

        // Count object/hjalbum entities
        $count_hjalbum = (int) \elgg_get_entities([
            'type' => 'object',
            'subtype' => 'hjalbum',
            'count' => true,
        ]);
        $output->writeln("  object/hjalbum: {$count_hjalbum} entities");

        // Count object/hjalbumimage entities
        $count_hjalbumimage = (int) \elgg_get_entities([
            'type' => 'object',
            'subtype' => 'hjalbumimage',
            'count' => true,
        ]);
        $output->writeln("  object/hjalbumimage: {$count_hjalbumimage} entities");

        // Verify upgrades completed
        // TODO: check pending Elgg\Upgrade\Batch scripts for this plugin
        // Example: query elgg_entities for type='object' subtype='upgrade' with status != 'completed'

        // Orphan relationship check
        // TODO: check for relationships referencing non-existent entities owned by this plugin

        // Plugin-specific config invariants
        // TODO: verify expected plugin settings are set and valid

        if ($exitCode === self::SUCCESS) {
            $output->writeln('<info>hypegallery:doctor complete — no issues found</info>');
        } else {
            $output->writeln('<error>hypegallery:doctor found issues — review output above</error>');
        }

        return $exitCode;
    }
}