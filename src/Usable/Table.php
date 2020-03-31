<?php

namespace Kompo;

use Kompo\Catalog;
use Kompo\TableRow;

class Table extends Catalog
{
    public $layout = 'Table';
    public $card = TableRow::class;
}
