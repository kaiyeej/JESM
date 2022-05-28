<?php
class Discounts extends Connection
{
    private $table = 'tbl_discounts';
    public $pk = 'discount_id';
    public $name = 'discount_name';
    public $status = 'status';


    private $table_detail = 'tbl_discount_details';
    public $pk2 = 'discount_detail_id';

    public function add()
    {
        $form = array(
            $this->name         => $this->clean($this->inputs[$this->name]),
            'description'       => $this->inputs['description'],
            'coverage_type'     => $this->inputs['coverage_type'],
            'discount_percent'  => $this->inputs['discount_percent'],
            'discount_type'     => $this->inputs['discount_type'],
            'discount_start'    => $this->inputs['discount_start'],
            'discount_end'      => $this->inputs['discount_end']
        );
        return $this->insertIfNotExist($this->table, $form);
    }

    public function edit()
    {
        $form = array(
            $this->name             => $this->clean($this->inputs[$this->name]),
            'product_category_id'   => $this->inputs['product_category_id'],
            'product_price'         => $this->inputs['product_price']
        );
        return $this->updateIfNotExist($this->table, $form);
    }

    public function show()
    {
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $result = $this->select($this->table, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function view()
    {
        $primary_id = $this->inputs['id'];
        $result = $this->select($this->table, "*", "$this->pk = '$primary_id'");
        return $result->fetch_assoc();
    }

    public function remove()
    {
        $ids = implode(",", $this->inputs['ids']);
        return $this->delete($this->table, "$this->pk IN($ids)");
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

    public function show_detail()
    {
        $discount_id = $this->inputs['discount_id'];
        $status = $this->status($discount_id);
        if ($status == 'F') {
            $table = $this->table_detail;
            $param = "det.product_id = p.product_id AND det.discount_id = '$discount_id'";
            $result = $this->select("$this->table_detail AS det, tbl_products AS p", 'product_name', $param);
        } else {
            $table = "tbl_products";
            $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
            $result = $this->select($table, '*', $param);
        }

        $rows = array();
        $count = 1;
        while ($row = $result->fetch_assoc()) {
            $row['is_checked'] = $status == 'F' ? '' : ($this->isItemDiscount($discount_id, $row['product_id']) > 0 ? 'checked' : '');
            $row['count'] = $count++;
            $rows[] = $row;
        }
        return $rows;
    }

    public function isItemDiscount($discount_id, $product_id)
    {
        $is_exist = $this->select($this->table_detail, $this->pk2, "product_id = '$product_id' AND discount_id = '$discount_id'");
        return $is_exist->num_rows;
    }

    public function add_item_discount()
    {
        $product = isset($this->inputs['product_id']) ? $this->inputs['product_id'] : [];
        $discount_id = $this->inputs['discount_id'];
        $all_item = $this->inputs['all_item'];

        $product_ids = $all_item == 1 ? $product : ($this->inputs['is_checked'] == 'true' ? [$product] : []);

        if (count($product_ids) > 0) {
            foreach ($product_ids as $product_id) {
                $form = array(
                    'discount_id'   => $discount_id,
                    'product_id'    => $product_id
                );
                $this->insertIfNotExist($this->table_detail, $form, "product_id = '$product_id' AND discount_id = '$discount_id'");
            }
        }

        if ($all_item == 1) {
            $product_category_id = $this->inputs['product_category_id'];
            $Products = new Products();
            $product_category_id > 0 ? $ject[] = "product_category_id = '$product_category_id'" : "";
            count($product_ids) > 0 ? $ject[] = "product_id NOT IN(" . implode(',', $product_ids) . ")" : "";
            $inject = implode(" AND ", $ject);
            $Products->inputs['param'] = $inject;
            $products = $Products->show();
            $product_del = [];
            if (count($products) > 0) {
                foreach ($products as $rowP) {
                    $product_del[] = $rowP['product_id'];
                }
            }
        } else {
            $product_del = ($this->inputs['is_checked'] == 'true') ? [] : [$product];
        }

        if (count($product_del) > 0) {
            foreach ($product_del as $product_id) {
                $form = array(
                    'discount_id'   => $discount_id,
                    'product_id'    => $product_id
                );
                $this->delete($this->table_detail, "product_id = '$product_id' AND discount_id = '$discount_id'");
            }
        }
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
                $this->metadata($this->status, 'varchar', 1),
                $this->metadata('description', 'varchar', 255),
                $this->metadata('coverage_type', 'varchar', 1, 'NOT NULL', '', '', "'A=All;L=Limited'"),
                $this->metadata('discount_type', 'varchar', 1, 'NOT NULL', '', '', "'A=Automatic;M=Manual'"),
                $this->metadata('discount_percent', 'decimal', '12,2'),
                $this->metadata('discount_start', 'datetime'),
                $this->metadata('discount_end', 'datetime'),
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
                $this->metadata('product_id', 'int', 11, 'NOT NULL'),
                $default['date_added'],
                $default['date_last_modified']
            )
        );

        return $this->schemaCreator($tables);
    }
}
