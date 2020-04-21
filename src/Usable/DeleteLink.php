<?php

namespace Kompo;

use Kompo\Link;

class DeleteLink extends Link
{   
    public $vueComponent = 'DeleteLink';

    protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);

		$this->deleteTitle('Delete this item');
		$this->confirmMessage('Are you sure?');
		$this->cancelMessage('Cancel');
    }

	/**
	 * Activates the delete functionality by specifying the key of the item to be deleted.
	 *
	 * @param Eloquent\Model  $deleteItem  The model that will be deleted. 
	 *
	 * @return self  
	 */
	public function byKeyNonStatic($deleteItem)
	{
		return $this->selfHttpRequest('DELETE', 'delete-item', null, [

			'deleteKey' => $deleteItem->getKey()
			
		])->emitDirect('deleted');
	}

	public static function byKeyStatic($deleteItem)
    {
        return with(new static())->byKey($deleteItem);
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
			'deleteTitle' => __($deleteTitle)
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
			'confirmMessage' => __($confirmMessage)
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
			'cancelMessage' => __($cancelMessage)
		]);
		return $this;
	}

	public static function duplicateStaticMethods()
    {
        return array_merge(parent::duplicateStaticMethods(), ['byKey']);
    }

}