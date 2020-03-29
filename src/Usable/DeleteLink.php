<?php

namespace Kompo;

use Kompo\Link;

class DeleteLink extends Link
{    
    public $component = 'DeleteLink';

    const DB_DELETE_ROUTE = 'vuravel-catalog.db.delete';

    public $data = [
    	'deleteTitle' => 'Delete this item',
    	'confirmMessage' => 'Are you sure?',
    	'cancelMessage' => 'Cancel'
    ];

	public function __construct($item, $label = '')
	{
		$this->post(self::DB_DELETE_ROUTE, [
				'id' => method_exists($item, 'getKey') ? $item->getKey() : $item->id,
				'objectClass' => get_class($item)
			]);
			
		parent::__construct($label);
	}

    protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);

		if(!$label) //just an icon
			$this->icon('icon-trash');

		$this->deleteTitle(__($this->data('deleteTitle')));
		$this->confirmMessage(__($this->data('confirmMessage')));
		$this->cancelMessage(__($this->data('cancelMessage')));
    }


    public function mounted($form)
    {
        $this->onSuccess(function($e) {
    		$e->emitDirect('insertForm');
    	});
    }

	/**
	 * Sets the title of the modal that opens when the DeleteLink is clicked.
	 * By default, it is 'Delete this item'.
	 *
	 * @param      string  $deleteTitle  The title of the action. 
	 *
	 * @return     self  
	 */
	public function deleteTitle($deleteTitle)
	{
		$this->data([
			'deleteTitle' => $deleteTitle
		]);
		return $this;
	}

	/**
	 * Sets the label (confirmation message) of the button that will really perform the delete request.
	 * By default, it is 'Are you sure?'.
	 *
	 * @param      string  $confirmMessage  The label of the confirmation button. 
	 *
	 * @return     self  
	 */
	public function confirmMessage($confirmMessage)
	{
		$this->data([
			'confirmMessage' => $confirmMessage
		]);
		return $this;
	}

	/**
	 * Sets the label (cancellation message) of the button that will close the modal.
	 * By default, it is 'Cancel'.
	 *
	 * @param      string  $cancelMessage  The label of the cancel button. 
	 *
	 * @return     self  
	 */
	public function cancelMessage($cancelMessage)
	{
		$this->data([
			'cancelMessage' => $cancelMessage
		]);
		return $this;
	}

}