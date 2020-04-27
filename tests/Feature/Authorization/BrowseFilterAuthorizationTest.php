<?php
namespace Kompo\Tests\Feature\Authorization;

use Kompo\Exceptions\UnauthorizedException;
use Kompo\Tests\EnvironmentBoot;

class BrowseFilterAuthorizationTest extends EnvironmentBoot
{
    /** @test */
	public function browse_is_unauthorized_for_query()
	{
		$this->expectException(UnauthorizedException::class);
		
		$this->withoutExceptionHandling()->browse(new _BrowseFilterUnauthorizedQuery());
	}

	/** ------------------ PRIVATE --------------------------- */    

}