<?php

namespace Tests\Unit;

use App\Adapter\Presenter\ValidatePromoCodeCliPresenter;
use App\Tests\TestCase;
use Mockery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class ValidatePromoCodeAdaptersTest extends TestCase
{
    public function testCliPresenterValid()
    {
        $output = Mockery::mock(OutputInterface::class);
        $output->shouldReceive('writeln')
            ->with(Mockery::capture($jsonString));

        $promoCode = $this->mockPromoCodeAggregate();

        $presenter = new ValidatePromoCodeCliPresenter();
        $viewModel = $presenter->valid($promoCode);

        $this->assertEquals(Command::SUCCESS, $viewModel->handle($output));
        $this->assertEquals($promoCode->getRoot()->getName(), json_decode($jsonString)->promoCode);
    }

    public function testCliPresenterNoCompatibleOffer()
    {
        $output = Mockery::mock(OutputInterface::class);
        $output->shouldReceive('writeln')
            ->with(Mockery::capture($error));

        $presenter = new ValidatePromoCodeCliPresenter();
        $viewModel = $presenter->noCompatibleOffer($this->mockPromoCodeAggregate());

        $this->assertEquals(Command::FAILURE, $viewModel->handle($output));
        $this->assertStringContainsString("No compatible offer found for code", $error);
    }

    public function testCliPresenterExpired()
    {
        $output = Mockery::mock(OutputInterface::class);
        $output->shouldReceive('writeln')
            ->with(Mockery::capture($error));

        $presenter = new ValidatePromoCodeCliPresenter();
        $viewModel = $presenter->expired($this->mockPromoCodeAggregate());

        $this->assertEquals(Command::FAILURE, $viewModel->handle($output));
        $this->assertStringContainsString("has expired", $error);
    }

    public function testCliPresenterNotFound()
    {
        $output = Mockery::mock(OutputInterface::class);
        $output->shouldReceive('writeln')
            ->with(Mockery::capture($error));

        $presenter = new ValidatePromoCodeCliPresenter();
        $viewModel = $presenter->notFound(uniqid('code-'));

        $this->assertEquals(Command::FAILURE, $viewModel->handle($output));
        $this->assertStringContainsString("was not found", $error);
    }
}
