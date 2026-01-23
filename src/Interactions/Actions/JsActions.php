<?php

namespace Kompo\Interactions\Actions;

trait JsActions
{
    // ==========================================
    // DOM VISIBILITY
    // ==========================================

    /**
     * Show an element (client-side)
     * @param string $elementId Target element ID
     */
    public function jsShow($elementId)
    {
        $id = json_encode($elementId);
        return $this->run("({ el }) => el({$id}).show()");
    }

    /**
     * Hide an element (client-side)
     */
    public function jsHide($elementId)
    {
        $id = json_encode($elementId);
        return $this->run("({ el }) => el({$id}).hide()");
    }

    /**
     * Toggle element visibility
     */
    public function jsToggle($elementId)
    {
        $id = json_encode($elementId);
        return $this->run("({ el }) => el({$id}).toggle()");
    }

    // ==========================================
    // CSS CLASSES
    // ==========================================

    /**
     * Add CSS class to element
     * @param string $elementId Target element (or null for self)
     * @param string|null $className Class to add
     */
    public function jsAddClass($elementId, $className = null)
    {
        if ($className === null) {
            $class = json_encode($elementId);
            return $this->run("({ \$k }) => \$k.vue.\$el.classList.add({$class})");
        }
        $id = json_encode($elementId);
        $class = json_encode($className);
        return $this->run("({ el }) => el({$id}).addClass({$class})");
    }

    /**
     * Remove CSS class from element
     */
    public function jsRemoveClass($elementId, $className = null)
    {
        if ($className === null) {
            $class = json_encode($elementId);
            return $this->run("({ \$k }) => \$k.vue.\$el.classList.remove({$class})");
        }
        $id = json_encode($elementId);
        $class = json_encode($className);
        return $this->run("({ el }) => el({$id}).removeClass({$class})");
    }

    /**
     * Toggle CSS class on element
     */
    public function jsToggleClass($elementId, $className = null)
    {
        if ($className === null) {
            $class = json_encode($elementId);
            return $this->run("({ \$k }) => \$k.vue.\$el.classList.toggle({$class})");
        }
        $id = json_encode($elementId);
        $class = json_encode($className);
        return $this->run("({ el }) => el({$id}).toggleClass({$class})");
    }

    // ==========================================
    // NAVIGATION
    // ==========================================

    /**
     * Scroll to an element
     */
    public function jsScrollTo($elementId = null)
    {
        if ($elementId === null) {
            return $this->run("({ \$k }) => \$k.vue.\$el.scrollIntoView({ behavior: 'smooth', block: 'center' })");
        }
        $id = json_encode($elementId);
        return $this->run("({ el }) => el({$id}).scrollTo()");
    }

    /**
     * Focus on an element/field
     */
    public function jsFocus($fieldName = null)
    {
        if ($fieldName === null) {
            return $this->run("({ \$k }) => { const i = \$k.vue.\$el.querySelector('input,textarea,select'); if(i) i.focus() }");
        }
        $name = json_encode($fieldName);
        return $this->run("({ field }) => field({$name}).focus()");
    }

    // ==========================================
    // VALUE MANIPULATION
    // ==========================================

    /**
     * Set a field's value
     * @param string $fieldName Target field
     * @param mixed $value Value to set
     */
    public function jsSetValue($fieldName, $value)
    {
        $name = json_encode($fieldName);
        $valueJson = json_encode($value);
        return $this->run("({ field }) => field({$name}).set({$valueJson})");
    }

    /**
     * Copy value from one field to another
     */
    public function jsCopyValue($fromField, $toField)
    {
        $from = json_encode($fromField);
        $to = json_encode($toField);
        return $this->run("({ field }) => field({$to}).set(field({$from}).value)");
    }

    /**
     * Clear a field's value
     */
    public function jsClear($fieldName = null)
    {
        if ($fieldName === null) {
            return $this->run("({ \$k }) => { if(\$k.vue.\$_fill) \$k.vue.\$_fill(''); else if(\$k.vue.value !== undefined) \$k.vue.value = '' }");
        }
        $name = json_encode($fieldName);
        return $this->run("({ field }) => field({$name}).clear()");
    }

    /**
     * Map current value to another field
     * Example: ->jsMapValue('currency', ['US' => 'USD', 'CA' => 'CAD'])
     */
    public function jsMapValue($targetField, array $mapping)
    {
        $target = json_encode($targetField);
        $mappingJson = json_encode($mapping);
        return $this->run("({ field, value }) => { const map = {$mappingJson}; if(map[value] !== undefined) field({$target}).set(map[value]) }");
    }

    // ==========================================
    // PANEL/CONTENT
    // ==========================================

    /**
     * Fill a panel with HTML content (client-side)
     */
    public function jsInPanel($panelId, $html)
    {
        $id = json_encode($panelId);
        $htmlJson = json_encode($html);
        return $this->run("({ panel }) => panel({$id}).fill({$htmlJson})");
    }

    /**
     * Append HTML to a panel
     */
    public function jsAppend($panelId, $html)
    {
        $id = json_encode($panelId);
        $htmlJson = json_encode($html);
        return $this->run("({ panel }) => panel({$id}).append({$htmlJson})");
    }

    /**
     * Prepend HTML to a panel
     */
    public function jsPrepend($panelId, $html)
    {
        $id = json_encode($panelId);
        $htmlJson = json_encode($html);
        return $this->run("({ panel }) => panel({$id}).prepend({$htmlJson})");
    }

    /**
     * Clear a panel's content
     */
    public function jsClearPanel($panelId)
    {
        $id = json_encode($panelId);
        return $this->run("({ panel }) => panel({$id}).clear()");
    }

    /**
     * Show loading skeleton in panel
     */
    public function jsLoading($panelId, $show = true)
    {
        $id = json_encode($panelId);
        $showStr = $show ? 'true' : 'false';
        return $this->run("({ panel }) => panel({$id}).loading({$showStr})");
    }

    // ==========================================
    // EVENTS
    // ==========================================

    /**
     * Emit a custom event
     */
    public function jsEmit($eventName, $payload = [])
    {
        $event = json_encode($eventName);
        $payloadJson = json_encode($payload);
        return $this->run("({ emit }) => emit({$event}, {$payloadJson})");
    }

    /**
     * Emit to a specific component
     */
    public function jsEmitTo($kompoid, $eventName, $payload = [])
    {
        $kompoId = json_encode($kompoid);
        $event = json_encode($eventName);
        $payloadJson = json_encode($payload);
        return $this->run("({ emitTo }) => emitTo({$kompoId}, {$event}, {$payloadJson})");
    }

    // ==========================================
    // COMPONENT ACTIONS
    // ==========================================

    /**
     * Refresh a component
     */
    public function jsRefresh($kompoid)
    {
        $kompoId = json_encode($kompoid);
        return $this->run("({ refresh }) => refresh({$kompoId})");
    }

    /**
     * Show alert
     */
    public function jsAlert($message, $type = 'success')
    {
        $messageJson = json_encode($message);
        $typeJson = json_encode($type);
        return $this->run("({ alert }) => alert({$messageJson}, {$typeJson})");
    }

    /**
     * Redirect
     */
    public function jsRedirect($url)
    {
        $urlJson = json_encode($url);
        return $this->run("({ redirect }) => redirect({$urlJson})");
    }

    /**
     * Open modal
     */
    public function jsOpenModal($modalId = null)
    {
        if ($modalId) {
            $id = json_encode($modalId);
            return $this->run("({ modal }) => modal({$id}).open()");
        }
        return $this->run("({ modal }) => modal().open()");
    }

    /**
     * Close modal
     */
    public function jsCloseModal()
    {
        return $this->run("({ modal }) => modal().close()");
    }

    // ==========================================
    // FORM OPERATIONS
    // ==========================================

    /**
     * Fill multiple form fields at once
     */
    public function jsFillForm(array $data)
    {
        $dataJson = json_encode($data);
        return $this->run("({ form }) => form.fill({$dataJson})");
    }

    /**
     * Reset form
     */
    public function jsResetForm()
    {
        return $this->run("({ form }) => form.reset()");
    }

    /**
     * Submit form
     */
    public function jsSubmitForm()
    {
        return $this->run("({ form }) => form.submit()");
    }

    // ==========================================
    // QUERY/TABLE
    // ==========================================

    /**
     * Refresh a query/table
     */
    public function jsRefreshQuery($queryId)
    {
        $id = json_encode($queryId);
        return $this->run("({ query }) => query({$id}).refresh()");
    }

    /**
     * Go to page in query
     */
    public function jsQueryPage($queryId, $page)
    {
        $id = json_encode($queryId);
        $pageNum = json_encode($page);
        return $this->run("({ query }) => query({$id}).page({$pageNum})");
    }

    /**
     * Sort query
     */
    public function jsQuerySort($queryId, $column)
    {
        $id = json_encode($queryId);
        $col = json_encode($column);
        return $this->run("({ query }) => query({$id}).sort({$col})");
    }

    // ==========================================
    // ELEMENT REMOVAL
    // ==========================================

    /**
     * Remove an element from DOM
     */
    public function jsRemove($elementId = null)
    {
        if ($elementId === null) {
            return $this->run("({ \$k }) => \$k.vue.\$el.remove()");
        }
        $id = json_encode($elementId);
        return $this->run("({ el }) => el({$id}).remove()");
    }

    /**
     * Remove self (the triggering element)
     */
    public function jsRemoveSelf()
    {
        return $this->jsRemove();
    }

    // ==========================================
    // UTILITIES
    // ==========================================

    /**
     * Toggle global loading spinner
     */
    public function jsGlobalLoading($show = true)
    {
        $showStr = $show ? 'true' : 'false';
        return $this->run("({ loading }) => loading({$showStr})");
    }

    /**
     * Console log (for debugging)
     */
    public function jsLog($message)
    {
        $messageJson = json_encode($message);
        return $this->run("() => console.log({$messageJson})");
    }
}
