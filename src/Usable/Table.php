<?php

namespace Kompo;

use Kompo\Query;
use Kompo\TableRow;

class Table extends Query
{
    public $layout = 'Table';
    public $card = TableRow::class;
}
