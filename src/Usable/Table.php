<?php

namespace Kompo;

abstract class Table extends Query
{
    public $layout = 'Table';
    public $card = TableRow::class;
}
