<?php
class JobOrder extends Connection
{
    private $table = 'tbl_job_order';
    public $pk = 'jo_id';
    public $name = 'reference_number';

    private $table_detail = 'tbl_job_order_details';
    public $pk2 = 'jo_detail_id';
    public $fk_det = 'product_id';

    public function add()
    {
        $form = array(
            $this->name         => $this->clean($this->inputs[$this->name]),
            'customer_id'       => $this->inputs['customer_id'],
            'service_id'        => $this->inputs['service_id'],
            'remarks'           => $this->inputs['remarks'],
            'jo_date'           => $this->inputs['jo_date'],
            'date_added'        => $this->getCurrentDate(),
            
        );
        return $this->insertIfNotExist($this->table, $form, '', 'Y');

    }

    public function add_detail()
    {
        $primary_id = $this->inputs[$this->pk];
        $fk_det     = $this->inputs[$this->fk_det];
        $Products = new Products;
        $product_cost = $Products->productCost($fk_det);
        $form = array(
            $this->pk       => $this->inputs[$this->pk],
            $this->fk_det   => $fk_det,
            'qty'           => $this->inputs['qty'],
            'cost'          => $product_cost,
            'price'         => $this->inputs['price'],
            'amount'         => ($this->inputs['price']*$this->inputs['qty'])
        );

        return $this->insert($this->table_detail, $form);
    }

    public function edit()
    {
        $form = array(
            $this->name         => $this->clean($this->inputs[$this->name]),
            'customer_id'       => $this->inputs['customer_id'],
            'service_id'        => $this->inputs['service_id'],
            'remarks'           => $this->inputs['remarks'],
            'jo_date'           => $this->inputs['jo_date']
        );
        return $this->updateIfNotExist($this->table, $form);
    }

    public function show()
    {
        $Customers = new Customers;
        $Services = new Services;
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $result = $this->select($this->table, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['customer'] = $Customers->name($row['customer_id']);
            $row['service'] = $Services->name($row['service_id']);
            $row['total'] = $this->total($row['jo_id']);
            $rows[] = $row;
        }
        return $rows;
    }

    public function total($primary_id)
    {

        $result = $this->select($this->table_detail, "sum(amount) as total", "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function view()
    {
        $Customers = new Customers;
        $Services = new Services;
        $primary_id = $this->inputs['id'];
        $result = $this->select($this->table, "*", "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        $row['customer'] = $Customers->name($row['customer_id']);
        $row['service'] = $Services->name($row['service_id']);
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
            $row['price'] = number_format($row['price']);
            $row['count'] = $count++;
            $rows[] = $row;
        }
        return $rows;
    }

    public function getJOHeader()
    {
        $Customers = new Customers;
        $id = $_POST['id'];
        $result = $this->select($this->table, "*", "$this->pk='$id'");
        $row = $result->fetch_assoc();
        $row['jo_date'] = date("F j, Y", strtotime($row['jo_date']));
        $row['customer'] = $Customers->name($row['customer_id']);
        $rows[] = $row;
        return $rows;
    }

    
    public function geJODetails()
    {
        $id = $_POST['id'];
        $Products = new Products;
        $rows = array();
        $result = $this->select($this->table_detail, "*", "$this->pk='$id'");
        while ($row = $result->fetch_assoc()) {
            $row['product_name'] = $Products->name($row['product_id']);
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
        $default['date_added'] = $this->metadata('date_added', 'datetime');
        $default['date_last_modified'] = $this->metadata('date_last_modified', 'datetime', '', 'NOT NULL', '', 'ON UPDATE CURRENT_TIMESTAMP');


        // TABLE HEADER
        $tables[] = array(
            'name'      => $this->table,
            'primary'   => $this->pk,
            'fields' => array(
                $this->metadata($this->pk, 'int', 11, 'NOT NULL', '', 'AUTO_INCREMENT'),
                $this->metadata($this->name, 'varchar', 75),
                $this->metadata('customer_id', 'int', 11),
                $this->metadata('service_id', 'int', 3, 'NOT NULL'),
                $this->metadata('remarks', 'varchar', 255, 'NOT NULL'),
                $this->metadata('user_id', 'int', 11, 'NOT NULL'),
                $this->metadata('jo_date', 'datetime', 'NOT NULL'),
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
                $this->metadata('qty', 'decimal', '11,2'),
                $this->metadata('cost', 'decimal', '11,2'),
                $this->metadata('price', 'decimal', '   11,2'),
            )
        );

        return $this->schemaCreator($tables);


        return $this->schemaCreator($tables);
    }
}
