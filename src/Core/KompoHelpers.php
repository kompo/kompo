<?php 

if (! function_exists('form')) {
    function form($formClass, $dontBoot = false)
    {
        if(class_exists($formClass = 'App\\Http\\Komposers\\'.$formClass))
            return new $formClass();
        
        throw new \RuntimeException("Form [{$formClass}] does not exist when called with form().");
    }
}

if (! function_exists('query')) {
    function query($queryClass, $dontBoot = false)
    {
        if(class_exists($queryClass = 'App\\Http\\Komposers\\'.$queryClass))
            return new $queryClass();
        
        throw new \RuntimeException("Query [{$queryClass}] does not exist when called with query().");
    }
}

if (! function_exists('menu')) {
    function menu($menuClass)
    {
        if(class_exists($menuClass = 'App\\Menus\\'.$menuClass))
            return new $menuClass();
        
        throw new \RuntimeException("Menu [{$menuClass}] does not exist when called with menu().");
    }
}

if (! function_exists('thumb')) {
    function thumb($path)
    {
        return substr($path, 0, strrpos( $path, '.')).
               '_thumb.'.
               substr($path, strrpos($path,'.') + 1);
    }
}

if (! function_exists('assetThumb')) {
    function assetThumb($path)
    {
        return thumb(asset($path));
    }
}

/* Kompo helpers */

if(! function_exists('_Query'))
{
    function _Query()
    {
        return Kompo\Query::form(...func_get_args());
    }
}

if(! function_exists('_Card'))
{
    function _Card()
    {
        return Kompo\Card::form(...func_get_args());
    }
}

if(! function_exists('_IconText'))
{
    function _IconText()
    {
        return Kompo\IconText::form(...func_get_args());
    }
}

if(! function_exists('_ImageCard'))
{
    function _ImageCard()
    {
        return Kompo\ImageCard::form(...func_get_args());
    }
}

if(! function_exists('_ImageOverlay'))
{
    function _ImageOverlay()
    {
        return Kompo\ImageOverlay::form(...func_get_args());
    }
}

if(! function_exists('_ImageRow'))
{
    function _ImageRow()
    {
        return Kompo\ImageRow::form(...func_get_args());
    }
}

if(! function_exists('_TableRow'))
{
    function _TableRow()
    {
        return Kompo\TableRow::form(...func_get_args());
    }
}

if(! function_exists('_AddButton'))
{
    function _AddButton()
    {
        return Kompo\AddButton::form(...func_get_args());
    }
}

if(! function_exists('_AddLink'))
{
    function _AddLink()
    {
        return Kompo\AddLink::form(...func_get_args());
    }
}

if(! function_exists('_DeleteLink'))
{
    function _DeleteLink()
    {
        return Kompo\DeleteLink::form(...func_get_args());
    }
}

if(! function_exists('_EditButton'))
{
    function _EditButton()
    {
        return Kompo\EditButton::form(...func_get_args());
    }
}

if(! function_exists('_EditLink'))
{
    function _EditLink()
    {
        return Kompo\EditLink::form(...func_get_args());
    }
}

if(! function_exists('_Form'))
{
    function _Form()
    {
        return Kompo\Form::form(...func_get_args());
    }
}

if(! function_exists('_View'))
{
    function _View()
    {
        return Kompo\View::form(...func_get_args());
    }
}

if(! function_exists('_Alert'))
{
    function _Alert()
    {
        return Kompo\Alert::form(...func_get_args());
    }
}

if(! function_exists('_Badge'))
{
    function _Badge()
    {
        return Kompo\Badge::form(...func_get_args());
    }
}

if(! function_exists('_Blade'))
{
    function _Blade()
    {
        return Kompo\Blade::form(...func_get_args());
    }
}

if(! function_exists('_Button'))
{
    function _Button()
    {
        return Kompo\Button::form(...func_get_args());
    }
}

if(! function_exists('_Calendar'))
{
    function _Calendar()
    {
        return Kompo\Calendar::form(...func_get_args());
    }
}

if(! function_exists('_Checkbox'))
{
    function _Checkbox()
    {
        return Kompo\Checkbox::form(...func_get_args());
    }
}

if(! function_exists('_CKEditor'))
{
    function _CKEditor()
    {
        return Kompo\CKEditor::form(...func_get_args());
    }
}

if(! function_exists('_Code'))
{
    function _Code()
    {
        return Kompo\Code::form(...func_get_args());
    }
}

if(! function_exists('_Columns'))
{
    function _Columns()
    {
        return Kompo\Columns::form(...func_get_args());
    }
}

if(! function_exists('_Counter'))
{
    function _Counter()
    {
        return Kompo\Counter::form(...func_get_args());
    }
}

if(! function_exists('_Country'))
{
    function _Country()
    {
        return Kompo\Country::form(...func_get_args());
    }
}

if(! function_exists('_Date'))
{
    function _Date()
    {
        return Kompo\Date::form(...func_get_args());
    }
}

if(! function_exists('_DateRange'))
{
    function _DateRange()
    {
        return Kompo\DateRange::form(...func_get_args());
    }
}

if(! function_exists('_DateTime'))
{
    function _DateTime()
    {
        return Kompo\DateTime::form(...func_get_args());
    }
}

if(! function_exists('_Div'))
{
    function _Div() : Kompo\Div
    {
        return Kompo\Div::form(...func_get_args());
    }
}

if(! function_exists('_EditableCKEditor'))
{
    function _EditableCKEditor()
    {
        return Kompo\EditableCKEditor::form(...func_get_args());
    }
}

if(! function_exists('_EditableTextarea'))
{
    function _EditableTextarea()
    {
        return Kompo\EditableTextarea::form(...func_get_args());
    }
}

if(! function_exists('_File'))
{
    function _File()
    {
        return Kompo\File::form(...func_get_args());
    }
}

if(! function_exists('_Flex'))
{
    function _Flex()
    {
        return Kompo\Flex::form(...func_get_args());
    }
}

if(! function_exists('_FlexAround'))
{
    function _FlexAround()
    {
        return Kompo\FlexAround::form(...func_get_args());
    }
}

if(! function_exists('_FlexBetween'))
{
    function _FlexBetween()
    {
        return Kompo\FlexBetween::form(...func_get_args());
    }
}

if(! function_exists('_FlexCenter'))
{
    function _FlexCenter()
    {
        return Kompo\FlexCenter::form(...func_get_args());
    }
}

if(! function_exists('_FlexEnd'))
{
    function _FlexEnd()
    {
        return Kompo\FlexEnd::form(...func_get_args());
    }
}

if(! function_exists('_H1'))
{
    function _H1()
    {
        return Kompo\H1::form(...func_get_args());
    }
}

if(! function_exists('_H2'))
{
    function _H2()
    {
        return Kompo\H2::form(...func_get_args());
    }
}

if(! function_exists('_H3'))
{
    function _H3()
    {
        return Kompo\H3::form(...func_get_args());
    }
}

if(! function_exists('_H4'))
{
    function _H4()
    {
        return Kompo\H4::form(...func_get_args());
    }
}

if(! function_exists('_H5'))
{
    function _H5()
    {
        return Kompo\H5::form(...func_get_args());
    }
}

if(! function_exists('_H6'))
{
    function _H6()
    {
        return Kompo\H6::form(...func_get_args());
    }
}

if(! function_exists('_Hidden'))
{
    function _Hidden()
    {
        return Kompo\Hidden::form(...func_get_args());
    }
}

if(! function_exists('_Html'))
{
    function _Html()
    {
        return Kompo\Html::form(...func_get_args());
    }
}

if(! function_exists('_I'))
{
    function _I()
    {
        return Kompo\I::form(...func_get_args());
    }
}

if(! function_exists('_Image'))
{
    function _Image()
    {
        return Kompo\Image::form(...func_get_args());
    }
}

if(! function_exists('_Img'))
{
    function _Img()
    {
        return Kompo\Img::form(...func_get_args());
    }
}

if(! function_exists('_Input'))
{
    function _Input()
    {
        return Kompo\Input::form(...func_get_args());
    }
}

if(! function_exists('_InputNumber'))
{
    function _InputNumber()
    {
        return Kompo\InputNumber::form(...func_get_args());
    }
}

if(! function_exists('_Json'))
{
    function _Json()
    {
        return Kompo\Json::form(...func_get_args());
    }
}

if(! function_exists('_Link'))
{
    function _Link()
    {
        return Kompo\Link::form(...func_get_args());
    }
}

if(! function_exists('_Liste'))
{
    function _Liste()
    {
        return Kompo\Liste::form(...func_get_args());
    }
}

if(! function_exists('_ListeSelect'))
{
    function _ListeSelect()
    {
        return Kompo\ListeSelect::form(...func_get_args());
    }
}

if(! function_exists('_MultiFile'))
{
    function _MultiFile()
    {
        return Kompo\MultiFile::form(...func_get_args());
    }
}

if(! function_exists('_MultiForm'))
{
    function _MultiForm()
    {
        return Kompo\MultiForm::form(...func_get_args());
    }
}

if(! function_exists('_MultiImage'))
{
    function _MultiImage()
    {
        return Kompo\MultiImage::form(...func_get_args());
    }
}

if(! function_exists('_MultiPlace'))
{
    function _MultiPlace()
    {
        return Kompo\MultiPlace::form(...func_get_args());
    }
}

if(! function_exists('_MultiSelect'))
{
    function _MultiSelect()
    {
        return Kompo\MultiSelect::form(...func_get_args());
    }
}

if(! function_exists('_Panel'))
{
    function _Panel()
    {
        return Kompo\Panel::form(...func_get_args());
    }
}

if(! function_exists('_Panel1'))
{
    function _Panel1()
    {
        return Kompo\Panel1::form(...func_get_args());
    }
}

if(! function_exists('_Panel2'))
{
    function _Panel2()
    {
        return Kompo\Panel2::form(...func_get_args());
    }
}

if(! function_exists('_Panel3'))
{
    function _Panel3()
    {
        return Kompo\Panel3::form(...func_get_args());
    }
}

if(! function_exists('_Panel4'))
{
    function _Panel4()
    {
        return Kompo\Panel4::form(...func_get_args());
    }
}

if(! function_exists('_Panel5'))
{
    function _Panel5()
    {
        return Kompo\Panel5::form(...func_get_args());
    }
}

if(! function_exists('_Password'))
{
    function _Password()
    {
        return Kompo\Password::form(...func_get_args());
    }
}

if(! function_exists('_Place'))
{
    function _Place()
    {
        return Kompo\Place::form(...func_get_args());
    }
}

if(! function_exists('_Rows'))
{
    function _Rows()
    {
        return Kompo\Rows::form(...func_get_args());
    }
}

if(! function_exists('_Search'))
{
    function _Search()
    {
        return Kompo\Search::form(...func_get_args());
    }
}

if(! function_exists('_Select'))
{
    function _Select()
    {
        return Kompo\Select::form(...func_get_args());
    }
}

if(! function_exists('_SelectButtons'))
{
    function _SelectButtons()
    {
        return Kompo\SelectButtons::form(...func_get_args());
    }
}

if(! function_exists('_SelectLinks'))
{
    function _SelectLinks()
    {
        return Kompo\SelectLinks::form(...func_get_args());
    }
}

if(! function_exists('_SelectNative'))
{
    function _SelectNative()
    {
        return Kompo\SelectNative::form(...func_get_args());
    }
}

if(! function_exists('_SelectUpdatable'))
{
    function _SelectUpdatable()
    {
        return Kompo\SelectUpdatable::form(...func_get_args());
    }
}

if(! function_exists('_Stripe'))
{
    function _Stripe()
    {
        return Kompo\Stripe::form(...func_get_args());
    }
}

if(! function_exists('_StripeButton'))
{
    function _StripeButton()
    {
        return Kompo\StripeButton::form(...func_get_args());
    }
}

if(! function_exists('_SubmitButton'))
{
    function _SubmitButton()
    {
        return Kompo\SubmitButton::form(...func_get_args());
    }
}

if(! function_exists('_Svg'))
{
    function _Svg()
    {
        return Kompo\Svg::form(...func_get_args());
    }
}

if(! function_exists('_Table'))
{
    function _Table()
    {
        return Kompo\Table::form(...func_get_args());
    }
}

if(! function_exists('_Tab'))
{
    function _Tab()
    {
        return Kompo\Tab::form(...func_get_args());
    }
}

if(! function_exists('_Tabs'))
{
    function _Tabs()
    {
        return Kompo\Tabs::form(...func_get_args());
    }
}

if(! function_exists('_Textarea'))
{
    function _Textarea()
    {
        return Kompo\Textarea::form(...func_get_args());
    }
}

if(! function_exists('_Th'))
{
    function _Th()
    {
        return Kompo\Th::form(...func_get_args());
    }
}

if(! function_exists('_Time'))
{
    function _Time()
    {
        return Kompo\Time::form(...func_get_args());
    }
}

if(! function_exists('_Title'))
{
    function _Title()
    {
        return Kompo\Title::form(...func_get_args());
    }
}

if(! function_exists('_Toggle'))
{
    function _Toggle()
    {
        return Kompo\Toggle::form(...func_get_args());
    }
}

if(! function_exists('_ToggleInput'))
{
    function _ToggleInput()
    {
        return Kompo\ToggleInput::form(...func_get_args());
    }
}

if(! function_exists('_Toolbar'))
{
    function _Toolbar()
    {
        return Kompo\Toolbar::form(...func_get_args());
    }
}

if(! function_exists('_Translatable'))
{
    function _Translatable()
    {
        return Kompo\Translatable::form(...func_get_args());
    }
}

if(! function_exists('_TranslatableEditor'))
{
    function _TranslatableEditor()
    {
        return Kompo\TranslatableEditor::form(...func_get_args());
    }
}

if(! function_exists('_Trix'))
{
    function _Trix()
    {
        return Kompo\Trix::form(...func_get_args());
    }
}

if(! function_exists('_Menu'))
{
    function _Menu()
    {
        return Kompo\Menu::form(...func_get_args());
    }
}

if(! function_exists('_AuthMenu'))
{
    function _AuthMenu()
    {
        return Kompo\AuthMenu::form(...func_get_args());
    }
}

if(! function_exists('_Collapse'))
{
    function _Collapse()
    {
        return Kompo\Collapse::form(...func_get_args());
    }
}

if(! function_exists('_CollapseOnMobile'))
{
    function _CollapseOnMobile()
    {
        return Kompo\CollapseOnMobile::form(...func_get_args());
    }
}

if(! function_exists('_Dropdown'))
{
    function _Dropdown()
    {
        return Kompo\Dropdown::form(...func_get_args());
    }
}

if(! function_exists('_Locales'))
{
    function _Locales()
    {
        return Kompo\Locales::form(...func_get_args());
    }
}

if(! function_exists('_Logo'))
{
    function _Logo()
    {
        return Kompo\Logo::form(...func_get_args());
    }
}

if(! function_exists('_NavSearch'))
{
    function _NavSearch()
    {
        return Kompo\NavSearch::form(...func_get_args());
    }
}

if(! function_exists('_SidebarToggler'))
{
    function _SidebarToggler()
    {
        return Kompo\SidebarToggler::form(...func_get_args());
    }
}