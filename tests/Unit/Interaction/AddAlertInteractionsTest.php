<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\SubmitButton;
use Kompo\Tests\EnvironmentBoot;

class AddAlertInteractionsTest extends EnvironmentBoot
{

	/** @test */
	public function higher_order_success_error_alert()
	{
		$al1 = ['success message', 'success icon', 'successClass'];
		$al2 = ['error message', 'error icon', 'errorClass'];

		$nested = SubmitButton::form()
			->onSuccess->alert($al1[0], $al1[1], $al1[2])
			->onError->alert($al2[0], $al2[1], $al2[2])
			->interactions[0]->action->interactions;

		$this->assertCount(2, $nested);

		$this->assert_alert_is_well_set_on_button($al1, 'success', $nested[0]);
		$this->assert_alert_is_well_set_on_button($al2, 'error', $nested[1]);
	}


	/** @test */
	public function higher_order_implied_success_error_alert()
	{
		$al1 = ['success message', 'success icon', 'successClass'];
		$al2 = ['error message', 'error icon', 'errorClass'];

		$nested = SubmitButton::form()
			->alert($al1[0], $al1[1], $al1[2]) //onSuccess not explicit
			->onError->alert($al2[0], $al2[1], $al2[2])
			->interactions[0]->action->interactions;

		$this->assertCount(2, $nested);

		$this->assert_alert_is_well_set_on_button($al1, 'success', $nested[0]);
		$this->assert_alert_is_well_set_on_button($al2, 'error', $nested[1]);
	}
	
	/** ------------------ PRIVATE --------------------------- */  

	private function assert_alert_is_well_set_on_button($result, $interactionType, $interaction)
	{
		$this->assertEquals($interactionType, $interaction->interactionType);
		$this->assertEquals('addAlert', $interaction->action->actionType);

		foreach (['message', 'iconClass', 'alertClass'] as $k => $v) {
			$this->assertEquals($result[$k], $interaction->action->data['alert'][$v]);
		}
	}
	
}