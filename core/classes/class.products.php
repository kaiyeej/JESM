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
            'remarks'               => $this->inputs['remarks'],
            'date_added'            => $this->getCurrentDate(),
        );

        $this->insert_logs('Added New Product (Name:'. $this->clean($this->inputs[$this->name]).')','');
        return $this->insertIfNotExist($this->table, $form);
    }

    public function edit()
    {
        $form = array(
            $this->name             => $this->clean($this->inputs[$this->name]),
            'product_price'         => $this->inputs['product_price'],
            'remarks'               => $this->inputs['remarks']
        );
        $this->insert_logs('Updated Product (Name:'. $this->clean($this->inputs[$this->name]).')','');
        return $this->updateIfNotExist($this->table, $form);
    }

    public function show()
    {
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : '';
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
        $this->insert_logs('Deleted Product Entry');
        return $this->delete($this->table, "$this->pk IN($ids)");
    }

    public function name($primary_id)
    {
        $result = $this->select($this->table, $this->name, "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row[$this->name];
    }

    public function productID($product_code)
    {
        $fetch = $this->select($this->table, $this->pk, "product_code='$product_code'");
        $row = $fetch->fetch_assoc();
        return $row[$this->pk];
    }

    public function productPrice($primary_id)
    {
        $fetch = $this->select($this->table, "product_price", "$this->pk = '$primary_id'");
        $row = $fetch->fetch_assoc();
        return $row['product_price'];
    }

    public function productCost($primary_id)
    {
        $fetch = $this->select($this->table, "product_cost", "$this->pk = '$primary_id'");
        $row = $fetch->fetch_assoc();
        return $row['product_cost'];
    }

    public function uploadImage()
    {
        $id = $this->inputs['product_id'];
        if (isset($_FILES['file']['tmp_name'])) {
            $image_name = $_FILES['file']['name'];
            move_uploaded_file($_FILES['file']['tmp_name'], '../assets/images/products/' . $image_name);
        } else {
            $image_name = "default.png";
        }

        $form = array(
            'product_img' => $image_name,
        );

        $this->insert_logs('Uploaded Product Image');
        return $this->update($this->table, $form, "product_id='$id'");
    }
}
