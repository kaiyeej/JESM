<?php
class Products extends Connection
{
    private $table = 'tbl_products';
    public $pk = 'product_id';
    public $name = 'product_name';

    public function add()
    {
        $form = array(
            $this->name             => $this->clean($this->inputs[$this->name]),
            'product_price'         => $this->inputs['product_price'],
            'product_img'           => 'default.jpg',
            'product_category_id'   => $this->inputs['product_category_id'],
            'remarks'               => $this->inputs['remarks'],
            'product_code'          => $this->inputs['product_code']
        );
        return $this->insertIfNotExist($this->table, $form, "product_code=".$this->inputs['product_code']." ");
    }

    public function edit()
    {
        $form = array(
            $this->name             => $this->clean($this->inputs[$this->name]),
            'product_category_id'   => $this->inputs['product_category_id'],
            'product_price'         => $this->inputs['product_price'],
            'remarks'               => $this->inputs['remarks']
        );
        return $this->updateIfNotExist($this->table, $form);
    }

    public function show()
    {
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : '';
        $ProductCategories = new ProductCategories();
        $rows = array();
        $result = $this->select($this->table, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['product_category'] = $ProductCategories->name($row['product_category_id']);
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

    public function productID($product_code){
        $fetch = $this->select($this->table, $this->pk, "product_code='$product_code'");
        $row = $fetch->fetch_assoc();
        return $row[$this->pk];
    }

    public function productPrice($primary_id){
        $fetch = $this->select($this->table, "product_price", "$this->pk = '$primary_id'");
        $row = $fetch->fetch_assoc();
        return $row['product_price'];
    }
}
