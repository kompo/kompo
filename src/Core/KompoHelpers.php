<?php

/* Kompo helpers */
if (!function_exists('_AddButton')) {
    function _AddButton(): \Kompo\AddButton
    {
        return Kompo\AddButton::form(...func_get_args());
    }
}

if (!function_exists('_AddLink')) {
    function _AddLink(): \Kompo\AddLink
    {
        return Kompo\AddLink::form(...func_get_args());
    }
}

if (!function_exists('_DeleteLink')) {
    function _DeleteLink(): \Kompo\DeleteLink
    {
        return Kompo\DeleteLink::form(...func_get_args());
    }
}

if (!function_exists('_EditButton')) {
    function _EditButton(): \Kompo\EditButton
    {
        return Kompo\EditButton::form(...func_get_args());
    }
}

if (!function_exists('_EditLink')) {
    function _EditLink(): \Kompo\EditLink
    {
        return Kompo\EditLink::form(...func_get_args());
    }
}

if (!function_exists('_Alert')) {
    function _Alert(): \Kompo\Alert
    {
        return Kompo\Alert::form(...func_get_args());
    }
}

if (!function_exists('_Badge')) {
    function _Badge(): \Kompo\Badge
    {
        return Kompo\Badge::form(...func_get_args());
    }
}

if (!function_exists('_Blade')) {
    function _Blade(): \Kompo\Blade
    {
        return Kompo\Blade::form(...func_get_args());
    }
}

if (!function_exists('_Button')) {
    function _Button(): \Kompo\Button
    {
        return Kompo\Button::form(...func_get_args());
    }
}

if (!function_exists('_Calendar')) {
    function _Calendar(): \Kompo\Calendar
    {
        return Kompo\Calendar::form(...func_get_args());
    }
}

if (!function_exists('_Chart')) {
    function _Chart(): \Kompo\Chart
    {
        return Kompo\Chart::form(...func_get_args());
    }
}

if (!function_exists('_Checkbox')) {
    function _Checkbox(): \Kompo\Checkbox
    {
        return Kompo\Checkbox::form(...func_get_args());
    }
}

if (!function_exists('_CKEditor')) {
    function _CKEditor(): \Kompo\CKEditor
    {
        return Kompo\CKEditor::form(...func_get_args());
    }
}

if (!function_exists('_Clock')) {
    function _Clock(): \Kompo\Clock
    {
        return Kompo\Clock::form(...func_get_args());
    }
}

if (!function_exists('_Code')) {
    function _Code(): \Kompo\Code
    {
        return Kompo\Code::form(...func_get_args());
    }
}

if (!function_exists('_Columns')) {
    function _Columns(): \Kompo\Columns
    {
        return Kompo\Columns::form(...func_get_args());
    }
}

if (!function_exists('_Counter')) {
    function _Counter(): \Kompo\Counter
    {
        return Kompo\Counter::form(...func_get_args());
    }
}

if (!function_exists('_Country')) {
    function _Country(): \Kompo\Country
    {
        return Kompo\Country::form(...func_get_args());
    }
}

if (!function_exists('_Date')) {
    function _Date(): \Kompo\Date
    {
        return Kompo\Date::form(...func_get_args());
    }
}

if (!function_exists('_DateRange')) {
    function _DateRange(): \Kompo\DateRange
    {
        return Kompo\DateRange::form(...func_get_args());
    }
}

if (!function_exists('_DateTime')) {
    function _DateTime(): \Kompo\DateTime
    {
        return Kompo\DateTime::form(...func_get_args());
    }
}

if (!function_exists('_Div')) {
    function _Div(): Kompo\Div
    {
        return Kompo\Div::form(...func_get_args());
    }
}

if (!function_exists('_EditableCKEditor')) {
    function _EditableCKEditor(): \Kompo\EditableCKEditor
    {
        return Kompo\EditableCKEditor::form(...func_get_args());
    }
}

if (!function_exists('_EditableTextarea')) {
    function _EditableTextarea(): \Kompo\EditableTextarea
    {
        return Kompo\EditableTextarea::form(...func_get_args());
    }
}

if (!function_exists('_ErrorField')) {
    function _ErrorField(): \Kompo\ErrorField
    {
        return Kompo\ErrorField::form(...func_get_args());
    }
}

if (!function_exists('_File')) {
    function _File(): \Kompo\File
    {
        return Kompo\File::form(...func_get_args());
    }
}

if (!function_exists('_Flex')) {
    function _Flex(): \Kompo\Flex
    {
        return Kompo\Flex::form(...func_get_args());
    }
}

if (!function_exists('_FlexAround')) {
    function _FlexAround(): \Kompo\FlexAround
    {
        return Kompo\FlexAround::form(...func_get_args());
    }
}

if (!function_exists('_FlexBetween')) {
    function _FlexBetween(): \Kompo\FlexBetween
    {
        return Kompo\FlexBetween::form(...func_get_args());
    }
}

if (!function_exists('_FlexCenter')) {
    function _FlexCenter(): \Kompo\FlexCenter
    {
        return Kompo\FlexCenter::form(...func_get_args());
    }
}

if (!function_exists('_FlexEnd')) {
    function _FlexEnd(): \Kompo\FlexEnd
    {
        return Kompo\FlexEnd::form(...func_get_args());
    }
}

if (!function_exists('_H1')) {
    function _H1(): \Kompo\H1
    {
        return Kompo\H1::form(...func_get_args());
    }
}

if (!function_exists('_H2')) {
    function _H2(): \Kompo\H2
    {
        return Kompo\H2::form(...func_get_args());
    }
}

if (!function_exists('_H3')) {
    function _H3(): \Kompo\H3
    {
        return Kompo\H3::form(...func_get_args());
    }
}

if (!function_exists('_H4')) {
    function _H4(): \Kompo\H4
    {
        return Kompo\H4::form(...func_get_args());
    }
}

if (!function_exists('_H5')) {
    function _H5(): \Kompo\H5
    {
        return Kompo\H5::form(...func_get_args());
    }
}

if (!function_exists('_H6')) {
    function _H6(): \Kompo\H6
    {
        return Kompo\H6::form(...func_get_args());
    }
}

if (!function_exists('_Hidden')) {
    function _Hidden(): \Kompo\Hidden
    {
        return Kompo\Hidden::form(...func_get_args());
    }
}

if (!function_exists('_Html')) {
    function _Html(): \Kompo\Html
    {
        return Kompo\Html::form(...func_get_args());
    }
}

if (!function_exists('_HtmlField')) {
    function _HtmlField(): \Kompo\HtmlField
    {
        return Kompo\HtmlField::form(...func_get_args());
    }
}

if (!function_exists('_I')) {
    function _I(): \Kompo\I
    {
        return Kompo\I::form(...func_get_args());
    }
}

if (!function_exists('_Image')) {
    function _Image(): \Kompo\Image
    {
        return Kompo\Image::form(...func_get_args());
    }
}

if (!function_exists('_Img')) {
    function _Img(): \Kompo\Img
    {
        return Kompo\Img::form(...func_get_args());
    }
}

if (!function_exists('_Input')) {
    function _Input(): \Kompo\Input
    {
        return Kompo\Input::form(...func_get_args());
    }
}

if (!function_exists('_InputNumber')) {
    function _InputNumber(): \Kompo\InputNumber
    {
        return Kompo\InputNumber::form(...func_get_args());
    }
}

if (!function_exists('_Json')) {
    function _Json(): \Kompo\Json
    {
        return Kompo\Json::form(...func_get_args());
    }
}

if (!function_exists('_MultiCheckbox')) {
    function _MultiCheckbox(): \Kompo\MultiCheckbox
    {
        return Kompo\MultiCheckbox::form(...func_get_args());
    }
}

if (!function_exists('_Link')) {
    function _Link(): \Kompo\Link
    {
        return Kompo\Link::form(...func_get_args());
    }
}

if (!function_exists('_Liste')) {
    function _Liste(): \Kompo\Liste
    {
        return Kompo\Liste::form(...func_get_args());
    }
}

if (!function_exists('_ListeSelect')) {
    function _ListeSelect(): \Kompo\ListeSelect
    {
        return Kompo\ListeSelect::form(...func_get_args());
    }
}

if (!function_exists('_MultiFile')) {
    function _MultiFile(): \Kompo\MultiFile
    {
        return Kompo\MultiFile::form(...func_get_args());
    }
}

if (!function_exists('_MultiForm')) {
    function _MultiForm(): \Kompo\MultiForm
    {
        return Kompo\MultiForm::form(...func_get_args());
    }
}

if (!function_exists('_MultiImage')) {
    function _MultiImage(): \Kompo\MultiImage
    {
        return Kompo\MultiImage::form(...func_get_args());
    }
}

if (!function_exists('_MultiPlace')) {
    function _MultiPlace(): \Kompo\MultiPlace
    {
        return Kompo\MultiPlace::form(...func_get_args());
    }
}

if (!function_exists('_MultiSelect')) {
    function _MultiSelect(): \Kompo\MultiSelect
    {
        return Kompo\MultiSelect::form(...func_get_args());
    }
}

if (!function_exists('_MultiSelectLinks')) {
    function _MultiSelectLinks(): \Kompo\MultiSelectLinks
    {
        return Kompo\MultiSelectLinks::form(...func_get_args());
    }
}

if (!function_exists('_MultiSelectButtons')) {
    function _MultiSelectButtons(): \Kompo\MultiSelectButtons
    {
        return Kompo\MultiSelectButtons::form(...func_get_args());
    }
}

if (!function_exists('_Panel')) {
    function _Panel(): \Kompo\Panel
    {
        return Kompo\Panel::form(...func_get_args());
    }
}

if (!function_exists('_Panel1')) {
    function _Panel1(): \Kompo\Panel1
    {
        return Kompo\Panel1::form(...func_get_args());
    }
}

if (!function_exists('_Panel2')) {
    function _Panel2(): \Kompo\Panel2
    {
        return Kompo\Panel2::form(...func_get_args());
    }
}

if (!function_exists('_Panel3')) {
    function _Panel3(): \Kompo\Panel3
    {
        return Kompo\Panel3::form(...func_get_args());
    }
}

if (!function_exists('_Panel4')) {
    function _Panel4(): \Kompo\Panel4
    {
        return Kompo\Panel4::form(...func_get_args());
    }
}

if (!function_exists('_Panel5')) {
    function _Panel5(): \Kompo\Panel5
    {
        return Kompo\Panel5::form(...func_get_args());
    }
}

if (!function_exists('_Password')) {
    function _Password(): \Kompo\Password
    {
        return Kompo\Password::form(...func_get_args());
    }
}

if (!function_exists('_Place')) {
    function _Place(): \Kompo\Place
    {
        return Kompo\Place::form(...func_get_args());
    }
}

if (!function_exists('_Rows')) {
    function _Rows(): \Kompo\Rows
    {
        return Kompo\Rows::form(...func_get_args());
    }
}

if (!function_exists('_Search')) {
    function _Search(): \Kompo\Search
    {
        return Kompo\Search::form(...func_get_args());
    }
}

if (!function_exists('_Select')) {
    function _Select(): \Kompo\Select
    {
        return Kompo\Select::form(...func_get_args());
    }
}

if (!function_exists('_SelectButtons')) {
    function _SelectButtons(): \Kompo\SelectButtons
    {
        return Kompo\SelectButtons::form(...func_get_args());
    }
}

if (!function_exists('_SelectLinks')) {
    function _SelectLinks(): \Kompo\SelectLinks
    {
        return Kompo\SelectLinks::form(...func_get_args());
    }
}

if (!function_exists('_SelectNative')) {
    function _SelectNative(): \Kompo\SelectNative
    {
        return Kompo\SelectNative::form(...func_get_args());
    }
}

if (!function_exists('_SelectUpdatable')) {
    function _SelectUpdatable(): \Kompo\SelectUpdatable
    {
        return Kompo\SelectUpdatable::form(...func_get_args());
    }
}

if (!function_exists('_Stripe')) {
    function _Stripe(): \Kompo\Stripe
    {
        return Kompo\Stripe::form(...func_get_args());
    }
}

if (!function_exists('_StripeButton')) {
    function _StripeButton(): \Kompo\StripeButton
    {
        return Kompo\StripeButton::form(...func_get_args());
    }
}

if (!function_exists('_SubmitButton')) {
    function _SubmitButton(): \Kompo\SubmitButton
    {
        return Kompo\SubmitButton::form(...func_get_args());
    }
}

if (!function_exists('_Svg')) {
    function _Svg(): \Kompo\Svg
    {
        return Kompo\Svg::form(...func_get_args());
    }
}

if (!function_exists('_Tab')) {
    function _Tab(): \Kompo\Tab
    {
        return Kompo\Tab::form(...func_get_args());
    }
}

if (!function_exists('_Tabs')) {
    function _Tabs(): \Kompo\Tabs
    {
        return Kompo\Tabs::form(...func_get_args());
    }
}

if (!function_exists('_Textarea')) {
    function _Textarea(): \Kompo\Textarea
    {
        return Kompo\Textarea::form(...func_get_args());
    }
}

if (!function_exists('_Th')) {
    function _Th(): \Kompo\Th
    {
        return Kompo\Th::form(...func_get_args());
    }
}

if (!function_exists('_Time')) {
    function _Time(): \Kompo\Time
    {
        return Kompo\Time::form(...func_get_args());
    }
}

if (!function_exists('_Title')) {
    function _Title(): \Kompo\Title
    {
        return Kompo\Title::form(...func_get_args());
    }
}

if (!function_exists('_Toggle')) {
    function _Toggle(): \Kompo\Toggle
    {
        return Kompo\Toggle::form(...func_get_args());
    }
}

if (!function_exists('_ToggleInput')) {
    function _ToggleInput(): \Kompo\ToggleInput
    {
        return Kompo\ToggleInput::form(...func_get_args());
    }
}

if (!function_exists('_Toolbar')) {
    function _Toolbar(): \Kompo\Toolbar
    {
        return Kompo\Toolbar::form(...func_get_args());
    }
}

if (!function_exists('_Translatable')) {
    function _Translatable(): \Kompo\Translatable
    {
        return Kompo\Translatable::form(...func_get_args());
    }
}

if (!function_exists('_TranslatableEditor')) {
    function _TranslatableEditor(): \Kompo\TranslatableEditor
    {
        return Kompo\TranslatableEditor::form(...func_get_args());
    }
}

if (!function_exists('_Trix')) {
    function _Trix(): \Kompo\Trix
    {
        return Kompo\Trix::form(...func_get_args());
    }
}

/* MENU */
if (!function_exists('_AuthMenu')) {
    function _AuthMenu(): \Kompo\AuthMenu
    {
        return Kompo\AuthMenu::form(...func_get_args());
    }
}

if (!function_exists('_Collapse')) {
    function _Collapse(): \Kompo\Collapse
    {
        return Kompo\Collapse::form(...func_get_args());
    }
}

if (!function_exists('_CollapseOnMobile')) {
    function _CollapseOnMobile(): \Kompo\CollapseOnMobile
    {
        return Kompo\CollapseOnMobile::form(...func_get_args());
    }
}

if (!function_exists('_Dropdown')) {
    function _Dropdown(): \Kompo\Dropdown
    {
        return Kompo\Dropdown::form(...func_get_args());
    }
}

if (!function_exists('_Locales')) {
    function _Locales(): \Kompo\Locales
    {
        return Kompo\Locales::form(...func_get_args());
    }
}

if (!function_exists('_Logo')) {
    function _Logo(): \Kompo\Logo
    {
        return Kompo\Logo::form(...func_get_args());
    }
}

if (!function_exists('_NavSearch')) {
    function _NavSearch(): \Kompo\NavSearch
    {
        return Kompo\NavSearch::form(...func_get_args());
    }
}

if (!function_exists('_SidebarToggler')) {
    function _SidebarToggler(): \Kompo\SidebarToggler
    {
        return Kompo\SidebarToggler::form(...func_get_args());
    }
}

/* QUERY */
if (!function_exists('_Card')) {
    function _Card(): \Kompo\Card
    {
        return Kompo\Card::form(...func_get_args());
    }
}

if (!function_exists('_IconText')) {
    function _IconText(): \Kompo\IconText
    {
        return Kompo\IconText::form(...func_get_args());
    }
}

if (!function_exists('_ImageCard')) {
    function _ImageCard(): \Kompo\ImageCard
    {
        return Kompo\ImageCard::form(...func_get_args());
    }
}

if (!function_exists('_ImageOverlay')) {
    function _ImageOverlay(): \Kompo\ImageOverlay
    {
        return Kompo\ImageOverlay::form(...func_get_args());
    }
}

if (!function_exists('_ImageRow')) {
    function _ImageRow(): \Kompo\ImageRow
    {
        return Kompo\ImageRow::form(...func_get_args());
    }
}

if (!function_exists('_TableRow')) { //to deprecate , use _Tr()
    function _TableRow(): \Kompo\TableRow
    {
        return Kompo\TableRow::form(...func_get_args());
    }
}

if (!function_exists('_Tr')) {
    function _Tr(): \Kompo\Tr
    {
        return Kompo\Tr::form(...func_get_args());
    }
}

/* ADDITIONAL HELPERS */
if (!function_exists('_Flex4')) {
    function _Flex4(): \Kompo\Flex
    {
        return _Flex(...func_get_args())->class('space-x-4');
    }
}

if (!function_exists('_FlexEnd4')) {
    function _FlexEnd4(): \Kompo\FlexEnd
    {
        return _FlexEnd(...func_get_args())->class('space-x-4');
    }
}