<?php

namespace Kompo;

class Modal extends Form
{
	protected $_Title;
	protected $_Icon;

	/* KOMPO RENDER METHOD */
	public function komponents()
	{
		return [
			$this->header(),
			$this->bodyWrapper(),
			$this->footer(),
		];
	}

	/* MAIN MODAL ELEMENTS: HEADER / BODY / FOOTER */
	public function header()
	{
		return _FlexBetween(

			$this->title(),

			_FlexEnd(
				$this->headerButtons()
			)->class('flex-row-reverse md:flex-row md:ml-8')
		)
		->class('bg-gray-50 border-b border-gray-200 px-4 py-6 sm:px-6 rounded-t-lg')
		->class('flex-col items-start md:flex-row md:items-center')
		->alignStart();
	}

	public function bodyWrapper()
	{
		return _Rows(
			$this->body()
		)->class('p-4 md:p-6');
	}

	public function body()
	{
		//TO OVERRIDE BY DEVELOPER
	}

	public function footer()
	{
		return _FlexBetween(
			$this->footerTitle(),
			_FlexEnd(
				$this->footerButtons(),
			),
		)->class('flex-row-reverse md:flex-row')
		->class('bg-gray-50 border-t border-gray-200 px-4 py-6 sm:px-6 rounded-b-lg')
		->class('flex-col items-start md:flex-row md:items-center')
		->alignStart();		
	}

	/* MODAL HEADER ELEMENTS */
	public function title()
	{
		if (!$this->_Title && !$this->_Icon) {
			return;
		}

		return _Title($this->_Title)->class('text-2xl sm:text-3xl font-bold')
			->icon($this->icon())
			->class('font-semibold mb-4 md:mb-0')
			->class('flex items-center');
	}

	public function icon()
	{
		return !$this->_Icon ? null :

			_Svg($this->_Icon)->class('text-5xl');
	}

	public function headerButtons()
	{

	}

	/* MODAL BODY ELEMENTS */

	/* MODAL FOOTER ELEMENTS */
	public function footerTitle()
	{

	}

	public function footerButtons()
	{

	}
}
