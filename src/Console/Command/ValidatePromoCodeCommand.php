<?php

namespace App\Console\Command;

use App\Adapter\ViewModel\CliViewModel;
use Domain\UseCase\ValidatePromoCode\ValidatePromoCodeInteractor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ValidatePromoCodeCommand extends Command
{
    protected static $defaultName = 'promo-code:validate';

    private ValidatePromoCodeInteractor $interactor;

    public function __construct(ValidatePromoCodeInteractor $interactor)
    {
        parent::__construct();

        $this->interactor = $interactor;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Validates given promo-code.')
            ->setHelp('This command allows you to validate a promo-code.')
            ->addArgument('code', InputArgument::REQUIRED, 'Promo Code');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->interactor->validate($input->getArgument('code'));

        if (! $response instanceof CliViewModel) {
            throw new \LogicException(sprintf(
                "Invalid presenter return value, instance of %s expected, %s given.",
                CliViewModel::class,
                get_class($response),
            ));
        }

        return $response->handle($output);
    }
}
