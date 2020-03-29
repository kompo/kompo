<?php 

if (! function_exists('menu')) {
    function menu($menuClass)
    {
        if(class_exists($menuClass = 'App\\Menus\\'.$menuClass)){
            return new $menuClass();
        }
        throw new \RuntimeException("Menu [{$menuClass}] does not exist when called with menu().");
    }
}

/* Kompo helpers */

if(! function_exists('VlCatalog'))
{
    function VlCatalog()
    {
        return Kompo\Catalog::form(...func_get_args());
    }
}

if(! function_exists('VlCard'))
{
    function VlCard()
    {
        return Kompo\Card::form(...func_get_args());
    }
}

if(! function_exists('VlIconText'))
{
    function VlIconText()
    {
        return Kompo\IconText::form(...func_get_args());
    }
}

if(! function_exists('VlImageTitleDesc'))
{
    function VlImageTitleDesc()
    {
        return Kompo\ImageTitleDesc::form(...func_get_args());
    }
}

if(! function_exists('VlImageTitleOverlay'))
{
    function VlImageTitleOverlay()
    {
        return Kompo\ImageTitleOverlay::form(...func_get_args());
    }
}

if(! function_exists('VlImageTitleRow'))
{
    function VlImageTitleRow()
    {
        return Kompo\ImageTitleRow::form(...func_get_args());
    }
}

if(! function_exists('VlTableRow'))
{
    function VlTableRow()
    {
        return Kompo\TableRow::form(...func_get_args());
    }
}

if(! function_exists('VlAddButton'))
{
    function VlAddButton()
    {
        return Kompo\AddButton::form(...func_get_args());
    }
}

if(! function_exists('VlAddLink'))
{
    function VlAddLink()
    {
        return Kompo\AddLink::form(...func_get_args());
    }
}

if(! function_exists('VlDeleteLink'))
{
    function VlDeleteLink()
    {
        return Kompo\DeleteLink::form(...func_get_args());
    }
}

if(! function_exists('VlEditButton'))
{
    function VlEditButton()
    {
        return Kompo\EditButton::form(...func_get_args());
    }
}

if(! function_exists('VlEditLink'))
{
    function VlEditLink()
    {
        return Kompo\EditLink::form(...func_get_args());
    }
}

if(! function_exists('VlForm'))
{
    function VlForm()
    {
        return Kompo\Form::form(...func_get_args());
    }
}

if(! function_exists('VlBadge'))
{
    function VlBadge()
    {
        return Kompo\Badge::form(...func_get_args());
    }
}

if(! function_exists('VlButton'))
{
    function VlButton()
    {
        return Kompo\Button::form(...func_get_args());
    }
}

if(! function_exists('VlCheckbox'))
{
    function VlCheckbox()
    {
        return Kompo\Checkbox::form(...func_get_args());
    }
}

if(! function_exists('VlCKEditor'))
{
    function VlCKEditor()
    {
        return Kompo\CKEditor::form(...func_get_args());
    }
}

if(! function_exists('VlCode'))
{
    function VlCode()
    {
        return Kompo\Code::form(...func_get_args());
    }
}

if(! function_exists('VlColumns'))
{
    function VlColumns()
    {
        return Kompo\Columns::form(...func_get_args());
    }
}

if(! function_exists('VlCountry'))
{
    function VlCountry()
    {
        return Kompo\Country::form(...func_get_args());
    }
}

if(! function_exists('VlDate'))
{
    function VlDate()
    {
        return Kompo\Date::form(...func_get_args());
    }
}

if(! function_exists('VlDateTime'))
{
    function VlDateTime()
    {
        return Kompo\DateTime::form(...func_get_args());
    }
}

if(! function_exists('VlEditableCKEditor'))
{
    function VlEditableCKEditor()
    {
        return Kompo\EditableCKEditor::form(...func_get_args());
    }
}

if(! function_exists('VlEditableTextarea'))
{
    function VlEditableTextarea()
    {
        return Kompo\EditableTextarea::form(...func_get_args());
    }
}

if(! function_exists('VlFile'))
{
    function VlFile()
    {
        return Kompo\File::form(...func_get_args());
    }
}

if(! function_exists('VlFlex'))
{
    function VlFlex()
    {
        return Kompo\Flex::form(...func_get_args());
    }
}

if(! function_exists('VlFlexAround'))
{
    function VlFlexAround()
    {
        return Kompo\FlexAround::form(...func_get_args());
    }
}

if(! function_exists('VlFlexBetween'))
{
    function VlFlexBetween()
    {
        return Kompo\FlexBetween::form(...func_get_args());
    }
}

if(! function_exists('VlFlexCenter'))
{
    function VlFlexCenter()
    {
        return Kompo\FlexCenter::form(...func_get_args());
    }
}

if(! function_exists('VlFlexEnd'))
{
    function VlFlexEnd()
    {
        return Kompo\FlexEnd::form(...func_get_args());
    }
}

if(! function_exists('VlHidden'))
{
    function VlHidden()
    {
        return Kompo\Hidden::form(...func_get_args());
    }
}

if(! function_exists('VlHtml'))
{
    function VlHtml()
    {
        return Kompo\Html::form(...func_get_args());
    }
}

if(! function_exists('VlImage'))
{
    function VlImage()
    {
        return Kompo\Image::form(...func_get_args());
    }
}

if(! function_exists('VlInput'))
{
    function VlInput()
    {
        return Kompo\Input::form(...func_get_args());
    }
}

if(! function_exists('VlJson'))
{
    function VlJson()
    {
        return Kompo\Json::form(...func_get_args());
    }
}

if(! function_exists('VlLink'))
{
    function VlLink()
    {
        return Kompo\Link::form(...func_get_args());
    }
}

if(! function_exists('VlListe'))
{
    function VlListe()
    {
        return Kompo\Liste::form(...func_get_args());
    }
}

if(! function_exists('VlMultiFile'))
{
    function VlMultiFile()
    {
        return Kompo\MultiFile::form(...func_get_args());
    }
}

if(! function_exists('VlMultiForm'))
{
    function VlMultiForm()
    {
        return Kompo\MultiForm::form(...func_get_args());
    }
}

if(! function_exists('VlMultiImage'))
{
    function VlMultiImage()
    {
        return Kompo\MultiImage::form(...func_get_args());
    }
}

if(! function_exists('VlMultiPlace'))
{
    function VlMultiPlace()
    {
        return Kompo\MultiPlace::form(...func_get_args());
    }
}

if(! function_exists('VlMultiSelect'))
{
    function VlMultiSelect()
    {
        return Kompo\MultiSelect::form(...func_get_args());
    }
}

if(! function_exists('VlPanel'))
{
    function VlPanel()
    {
        return Kompo\Panel::form(...func_get_args());
    }
}

if(! function_exists('VlPanel1'))
{
    function VlPanel1()
    {
        return Kompo\Panel1::form(...func_get_args());
    }
}

if(! function_exists('VlPanel2'))
{
    function VlPanel2()
    {
        return Kompo\Panel2::form(...func_get_args());
    }
}

if(! function_exists('VlPanel3'))
{
    function VlPanel3()
    {
        return Kompo\Panel3::form(...func_get_args());
    }
}

if(! function_exists('VlPanel4'))
{
    function VlPanel4()
    {
        return Kompo\Panel4::form(...func_get_args());
    }
}

if(! function_exists('VlPanel5'))
{
    function VlPanel5()
    {
        return Kompo\Panel5::form(...func_get_args());
    }
}

if(! function_exists('VlPassword'))
{
    function VlPassword()
    {
        return Kompo\Password::form(...func_get_args());
    }
}

if(! function_exists('VlPlace'))
{
    function VlPlace()
    {
        return Kompo\Place::form(...func_get_args());
    }
}

if(! function_exists('VlRows'))
{
    function VlRows()
    {
        return Kompo\Rows::form(...func_get_args());
    }
}

if(! function_exists('VlSearch'))
{
    function VlSearch()
    {
        return Kompo\Search::form(...func_get_args());
    }
}

if(! function_exists('VlSelect'))
{
    function VlSelect()
    {
        return Kompo\Select::form(...func_get_args());
    }
}

if(! function_exists('VlSelectButtons'))
{
    function VlSelectButtons()
    {
        return Kompo\SelectButtons::form(...func_get_args());
    }
}

if(! function_exists('VlSelectLinks'))
{
    function VlSelectLinks()
    {
        return Kompo\SelectLinks::form(...func_get_args());
    }
}

if(! function_exists('VlSelectUpdatable'))
{
    function VlSelectUpdatable()
    {
        return Kompo\SelectUpdatable::form(...func_get_args());
    }
}

if(! function_exists('VlStripe'))
{
    function VlStripe()
    {
        return Kompo\Stripe::form(...func_get_args());
    }
}

if(! function_exists('VlTableCatalog'))
{
    function VlTableCatalog()
    {
        return Kompo\TableCatalog::form(...func_get_args());
    }
}

if(! function_exists('VlTab'))
{
    function VlTab()
    {
        return Kompo\Tab::form(...func_get_args());
    }
}

if(! function_exists('VlTabs'))
{
    function VlTabs()
    {
        return Kompo\Tabs::form(...func_get_args());
    }
}

if(! function_exists('VlTextarea'))
{
    function VlTextarea()
    {
        return Kompo\Textarea::form(...func_get_args());
    }
}

if(! function_exists('VlTh'))
{
    function VlTh()
    {
        return Kompo\Th::form(...func_get_args());
    }
}

if(! function_exists('VlTime'))
{
    function VlTime()
    {
        return Kompo\Time::form(...func_get_args());
    }
}

if(! function_exists('VlTitle'))
{
    function VlTitle()
    {
        return Kompo\Title::form(...func_get_args());
    }
}

if(! function_exists('VlToggle'))
{
    function VlToggle()
    {
        return Kompo\Toggle::form(...func_get_args());
    }
}

if(! function_exists('VlToggleInput'))
{
    function VlToggleInput()
    {
        return Kompo\ToggleInput::form(...func_get_args());
    }
}

if(! function_exists('VlToolbar'))
{
    function VlToolbar()
    {
        return Kompo\Toolbar::form(...func_get_args());
    }
}

if(! function_exists('VlTranslatable'))
{
    function VlTranslatable()
    {
        return Kompo\Translatable::form(...func_get_args());
    }
}

if(! function_exists('VlTranslatableEditor'))
{
    function VlTranslatableEditor()
    {
        return Kompo\TranslatableEditor::form(...func_get_args());
    }
}

if(! function_exists('VlTrix'))
{
    function VlTrix()
    {
        return Kompo\Trix::form(...func_get_args());
    }
}

if(! function_exists('VlMenu'))
{
    function VlMenu()
    {
        return Kompo\Menu::form(...func_get_args());
    }
}

if(! function_exists('VlAuthMenu'))
{
    function VlAuthMenu()
    {
        return Kompo\AuthMenu::form(...func_get_args());
    }
}

if(! function_exists('VlCollapse'))
{
    function VlCollapse()
    {
        return Kompo\Collapse::form(...func_get_args());
    }
}

if(! function_exists('VlCollapseOnMobile'))
{
    function VlCollapseOnMobile()
    {
        return Kompo\CollapseOnMobile::form(...func_get_args());
    }
}

if(! function_exists('VlDropdown'))
{
    function VlDropdown()
    {
        return Kompo\Dropdown::form(...func_get_args());
    }
}

if(! function_exists('VlLocales'))
{
    function VlLocales()
    {
        return Kompo\Locales::form(...func_get_args());
    }
}

if(! function_exists('VlLogo'))
{
    function VlLogo()
    {
        return Kompo\Logo::form(...func_get_args());
    }
}

if(! function_exists('VlNavSearch'))
{
    function VlNavSearch()
    {
        return Kompo\NavSearch::form(...func_get_args());
    }
}