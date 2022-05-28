<?php
class JobOrder extends Connection
{
    private $table = 'tbl_job_orders';
    public $pk = 'job_order_id';
    public $name = 'reference_number';

    private $table_detail = 'tbl_job_order_details';
    public $pk2 = 'jo_detail_id';
    public $fk_det = 'product_id';

    public function add()
    {
        $form = array(
            $this->name         => $this->clean($this->inputs[$this->name]),
            'product_id'        => $this->inputs['product_id'],
            'no_of_batches'     => $this->inputs['no_of_batches'],
            'remarks'           => $this->inputs['remarks'],
            'job_order_date'    => $this->inputs['job_order_date']
        );
        return $this->insertIfNotExist($this->table, $form);
    }

    public function add_detail()
    {
        $primary_id = $this->inputs[$this->pk];
        $fk_det     = $this->inputs[$this->fk_det];
        $Products = new Products;
        $product_price = $Products->productPrice($fk_det);
        $form = array(
            $this->pk               => $this->inputs[$this->pk],
            $this->fk_det           => $fk_det,
            //'product_category_id'   => $this->inputs['product_category_id'],
            'qty'              => $this->inputs['qty'],
            'cost'                 => $product_price
        );

        return $this->insertIfNotExist($this->table_detail, $form, "$this->pk = '$primary_id' AND $this->fk_det = '$fk_det'");
    }

    public function edit()
    {
        $form = array(
            $this->name             => $this->clean($this->inputs[$this->name]),
            'product_category_id'   => $this->inputs['product_category_id'],
            'no_of_batches'         => $this->inputs['no_of_batches'],
            'remarks'               => $this->inputs['remarks'],
            'job_order_date'        => $this->inputs['job_order_date']
        );
        return $this->updateIfNotExist($this->table, $form);
    }

    public function show()
    {
        $Products = new Products;
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $result = $this->select($this->table, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['product'] = $Products->name($row['product_id']);
            $rows[] = $row;
        }
        return $rows;
    }

    public function view()
    {
        $Products = new Products;
        $primary_id = $this->inputs['id'];
        $result = $this->select($this->table, "*", "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        $row['product'] = $Products->name($row['product_id']);
        return $row;
    }

    public function show_detail()
    {
        $Products = new Products;
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $count = 1;
        $result = $this->select($this->table_detail, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['product'] = $Products->name($row['product_id']);
            $row['qty'] = number_format($row['qty']);
            $row['cost'] = $row['cost'];
            $row['count'] = $count++;
            $rows[] = $row;
        }
        return $rows;
    }

    public function remove()
    {
        $ids = implode(",", $this->inputs['ids']);
        return $this->delete($this->table, "$this->pk IN($ids)");
    }

    public function remove_detail()
    {
        $ids = implode(",", $this->inputs['ids']);
        return $this->delete($this->table_detail, "$this->pk2 IN($ids)");
    }

    public function name($primary_id)
    {
        $result = $this->select($this->table, $this->name, "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row[$this->name];
    }

    public function status($primary_id)
    {
        $result = $this->select($this->table, $this->status, "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row[$this->status];
    }

    public function generate()
    {
        return 'JO-' . date('YmdHis');
    }

    public function finish()
    {
        $primary_id = $this->inputs['id'];
        $form = array(
            'status' => 'F',
        );
        return $this->update($this->table, $form, "$this->pk = '$primary_id'");
    }

    public function schema()
    {
        $default['date_added'] = $this->metadata('date_added', 'datetime', '', 'NOT NULL', 'CURRENT_TIMESTAMP');
        $default['date_last_modified'] = $this->metadata('date_last_modified', 'datetime', '', 'NOT NULL', '', 'ON UPDATE CURRENT_TIMESTAMP');


        // TABLE HEADER
        $tables[] = array(
            'name'      => $this->table,
            'primary'   => $this->pk,
            'fields' => array(
                $this->metadata($this->pk, 'int', 11, 'NOT NULL', '', 'AUTO_INCREMENT'),
                $this->metadata($this->name, 'varchar', 75),
                $this->metadata('product_id', 'int', 11),
                $this->metadata('no_of_batches', 'int', 3, 'NOT NULL'),
                $this->metadata('remarks', 'varchar', 255, 'NOT NULL'),
                $this->metadata('user_id', 'int', 11, 'NOT NULL'),
                $this->metadata('job_order_date', 'datetime', 'NOT NULL'),
                $this->metadata('status', 'varchar', 1),
                $default['date_added'],
                $default['date_last_modified']
            )
        );

        // TABLE DETAILS
        $tables[] = array(
            'name'      => $this->table_detail,
            'primary'   => $this->pk2,
            'fields' => array(
                $this->metadata($this->pk2, 'int', 11, 'NOT NULL', '', 'AUTO_INCREMENT'),
                $this->metadata($this->pk, 'int', 11, 'NOT NULL'),
                $this->metadata('product_id', 'int', 11),
                $this->metadata('qty', 'decimal', '7,2'),
                $this->metadata('cost', 'decimal', '7,2')
            )
        );

        return $this->schemaCreator($tables);


        return $this->schemaCreator($tables);
    }
}
