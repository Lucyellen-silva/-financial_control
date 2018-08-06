<?php

use Phinx\Migration\AbstractMigration;

class IncludeFieldParcelasInBillPayTable extends AbstractMigration
{
    public function up()
    {
        $this->table('bill_pays')
            ->addColumn('plots', 'integer', ['null' => true])
            ->save();
    }

    public function down()
    {
        $this->table('bill_pays')
            ->removeColumn('plots');
    }
}
