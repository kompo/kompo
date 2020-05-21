<?php
namespace Kompo\Tests\Feature\Authorization;

use Illuminate\Auth\Access\AuthorizationException;
use Kompo\Tests\EnvironmentBoot;

class BrowseFilterAuthorizationTest extends EnvironmentBoot
{
    /** @test */
	public function browse_is_unauthorized_for_query()
	{
		$this->expectException(AuthorizationException::class);
		
		$this->withoutExceptionHandling()->browse(new _BrowseFilterUnauthorizedQuery());
	}

	/** ------------------ PRIVATE --------------------------- */    

}