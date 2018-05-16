<?php
/**
 * 表结构对齐脚本
 * @var string
 */
$dbname     = 'new';
$old_dbname = 'old';
$db         = new mysqli_db('127.0.0.1', 'root', '', $dbname);
$old_db     = new mysqli_db('127.0.0.1', 'root', '', $old_dbname);
$tables     = $db->get_format_tables($dbname);
$old_tables = $old_db->get_format_tables($old_dbname);
foreach ($tables as $value) {
    $columns = $db->get_format_columns($value['name']);
    // var_dump($columns);die;
    if (($old_key = mysqli_db::find_array_key_in_arrays($value, $old_tables)) === false) {
        //表不存在
        // var_dump('creat_table:'.$value['name']);
        $old_db->creat_table($value, $columns);
    } else {
        $old_table = $old_tables[$old_key];
        if ($value != $old_table) {
            //表属性不同
            $old_db->edit_table($value);
        } else {
            //表属性不同
            $old_columns = $old_db->get_format_columns($value['name']);
            if ($columns != $old_columns) {
                //列不同
                foreach ($columns as $col_key => $col_value) {
                    if ($old_col_key = mysqli_db::find_array_key_in_arrays($col_value, $old_columns) === false) {
                        // 列不存在
                        $old_db->alter_cloumn_add($col_value, $value);
                    } else {
                        // 列存在
                        $old_column = $old_columns[$old_col_key];
                        if ($col_value != $old_column) {
                            // 列不同
                            // var_dump($col_value);
                            // var_dump($old_column);
                            $position = $col_key ? 'AFTER `' . $columns[$col_key - 1]['name'] . '`' : 'FIRST';
                            $old_db->alter_cloumn_edit($col_value, $value, $position);
                            // die;
                        }
                    }
                }
                foreach ($old_columns as $col_value) {
                    if ($col_key = mysqli_db::find_array_key_in_arrays($col_value, $columns) === false) {
                        // 列不存在
                        $old_db->alter_cloumn_del($col_value, $value);
                    }
                }
            } else {
                var_dump('complete_table:' . $value['name']);
            }
        }
    }
    /**
     * 索引复制
     */
    $indexs     = $db->get_format_indexs($value['name']);
    $old_indexs = $old_db->get_format_indexs($value['name']);
    if ($indexs != $old_indexs) {
        foreach ($old_indexs as $old_index_key => $old_index) {
            if ($old_index_key != 'PRIMARY') {
                $old_db->alert_index_del($old_index_key, $old_tables[$old_key]);
            }
        }
        foreach ($indexs as $index_key => $index) {
            if ($index_key != 'PRIMARY') {
                $old_db->alert_index_add($index, $index_key, $value);
            }
        }
    } else {
        var_dump('complete_table_indexs:' . $value['name']);
    }
}
foreach ($old_tables as $value) {
    if (mysqli_db::find_array_key_in_arrays($value, $tables) === false) {
        $old_db->del_table($value);
    }
}

/**
 *    数据库操作类
 */
class mysqli_db
{
    private $database;
    private $dbconn;

    public function __construct($address, $username, $password, $dbname = 'information_schema', $names = 'utf8')
    {
        $this->set_connect($address, $username, $password);
        $this->set_database($dbname);
        $this->set_names($names);
    }

    public function excuteSql($sql, $func, $name)
    {
        $res = mysqli_query($this->dbconn, $sql);
        if ($res) {
            // echo($sql);
            var_dump($func . ':' . $name);
        } else {
            echo ($sql);
            var_dump('fail_' . $func . ':' . $name);
            die;
        }
        return false;
    }

    /**
     * [find_array_key_in_arrays description]
     * @param  array $array [description]
     * @param  array $arrays [description]
     * @return bool|int|string [type]         [description]
     */
    public static function find_array_key_in_arrays(array $array, array $arrays)
    {
        foreach ($arrays as $key => $value) {
            if ($value['name'] == $array['name']) {
                return $key;
            }
        }
        return false;
    }

    /**
     * 数据库连接设置
     * @param [type] $database [description]
     */
    public function set_database($database)
    {
        $this->database = $database;
        mysqli_select_db($this->dbconn, $this->database ? $this->database : 'information_schema');
    }

    public function set_names($names)
    {
        mysqli_query($this->dbconn, "SET NAMES '$names'");
    }

    public function set_connect($address, $username, $password)
    {
        $this->dbconn = mysqli_connect($address, $username, $password);
        mysqli_select_db($this->dbconn, $this->database ? $this->database : 'information_schema');

    }

    public function fetch_first($sql)
    {
        return mysqli_fetch_array(mysqli_query($this->dbconn, $sql));
    }

    public function query($sql, $resulttype = MYSQLI_ASSOC)
    {
        $result = mysqli_query($this->dbconn, $sql);
        $data   = [];
        while ($row = mysqli_fetch_array($result, $resulttype)) {
            $data[] = $row;
        }
        return $data;
        // return mysqli_fetch_all($result,$resulttype);
    }

    public function close()
    {
        mysqli_close($this->dbconn);
    }

    /**
     * 查找表
     * @param  [type] $dbname [description]
     * @return array [type]         [description]
     */
    protected function select_tables_from_db($dbname)
    {
        if ($this->database != 'information_schema') {
            $database = $this->database;
            $this->set_database('information_schema');
        } else {
            $database = false;
        }
        $res = $this->query("SELECT * FROM tables WHERE table_schema='$dbname' ORDER BY TABLE_NAME");
        if ($database) {
            $this->set_database($database);
        }
        return $res;
    }

    protected function tables_format(array $tables)
    {
        $data = array();
        foreach ($tables as $value) {
            $data[] = $this->table_format($value);
        }
        return $data;
    }

    protected function table_format(array $table)
    {
        $data = array(
            'name'      => $table['TABLE_NAME'],
            'comment'   => $table['TABLE_COMMENT'],
            'engine'    => $table['ENGINE'],
            'collation' => $table['TABLE_COLLATION'],
        );
        return $data;
    }

    public function get_format_tables($dbname)
    {
        $tables = $this->select_tables_from_db($dbname);
        return $this->tables_format($tables);
    }

    /**
     * 查找列
     * @param  [type] $tablename [description]
     * @return array|bool [type]            [description]
     */
    protected function select_columns_from_table($tablename)
    {
        if ($this->database != 'information_schema') {
            $database = $this->database;
            $this->set_database('information_schema');
        } else {
            return false;
        }
        $res = $this->query("SELECT * FROM columns WHERE table_name='$tablename' AND table_schema='$database' ORDER BY COLUMN_NAME");
        if ($database) {
            $this->set_database($database);
        }
        return $res;
    }

    protected function columns_format(array $columns)
    {
        $data = array();
        foreach ($columns as $value) {
            $data[intval($value['ORDINAL_POSITION'] - 1)] = $this->column_format($value);
        }
        ksort($data);
        return $data;
    }

    protected function column_format(array $column)
    {
        $data = array('name' => $column['COLUMN_NAME']);
        $str  = $column['COLUMN_TYPE'];
        if ($column['IS_NULLABLE'] == 'NO') {
            $str .= ' NOT NULL';
        }
        if ($column['COLUMN_KEY'] == 'PRI') {
            $str .= ' PRIMARY KEY';
        }
        if ($column['COLUMN_TYPE'] != 'timestamp') {
            if ($column['COLUMN_DEFAULT']) {
                $str .= ' DEFAULT \'' . $column['COLUMN_DEFAULT'] . '\'';
            }
        } else {
            if ($column['COLUMN_DEFAULT'] == 'CURRENT_TIMESTAMP') {
                $str .= ' DEFAULT ' . $column['COLUMN_DEFAULT'];
            } else {
                $str .= ' DEFAULT \'0000-00-00 00:00:00\'';
            }
        }

        if ($column['COLUMN_COMMENT']) {
            $str .= ' COMMENT \'' . $column['COLUMN_COMMENT'] . '\'';
        }
        if ($column['EXTRA']) {
            $str .= ' ' . $column['EXTRA'];
        }
        $data['value'] = $str;
        return $data;
    }

    public function get_format_columns($tablename)
    {
        $columns = $this->select_columns_from_table($tablename);
        return $this->columns_format($columns);
    }

    public function select_indexs_from_table($tablename)
    {
        return $this->query("SHOW INDEX FROM `$tablename`");
    }

    public function indexs_format($indexs)
    {
        $data = [];
        foreach ($indexs as $key => $value) {
            $data[$value['Key_name']]['columns'][]  = $value['Column_name'];
            $data[$value['Key_name']]['non_unique'] = $value['Non_unique'];
        }
        ksort($data);
        return $data;
    }

    public function get_format_indexs($tablename)
    {
        $indexs = $this->select_indexs_from_table($tablename);
        return $this->indexs_format($indexs);
    }

    /**
     * 表格操作
     * @param array $table
     * @param array $columns
     * @return bool
     */
    public function creat_table(array $table, array $columns)
    {
        return self::excuteSql($this->sql_creat_table($table, $columns), 'creat_table', $table['name']);
    }

    public function sql_creat_table(array $table, array $columns)
    {
        if (!$table) {
            return false;
        }
        if (!$columns) {
            return false;
        }
        $sql     = '';
        $primary = '';
        foreach ($columns as $value) {
            if (stripos($value['value'], 'PRIMARY KEY')) {
                $value['value'] = str_replace('PRIMARY KEY', '', $value['value']);
                $primary .= '`' . $value['name'] . '`,';
            }
            $sql .= '`' . $value['name'] . '` ' . $value['value'] . ',';
        }
        if ($primary) {
            $sql .= 'PRIMARY KEY(' . rtrim($primary, ',') . ')';
        }
        $sql = 'CREATE TABLE `' . $table['name'] . '`(' . rtrim($sql, ',') . ')';
        if ($table['comment']) {
            $sql .= 'COMMENT \'' . $table['comment'] . '\'';
        }
        return $sql;
    }

    public function edit_table(array $table)
    {
        return self::excuteSql($this->sql_edit_table($table), 'edit_table', $table['name']);
    }

    public function sql_edit_table(array $table)
    {
        $sql = '';
        if ($table['comment']) {
            $sql .= ' COMMENT \'' . $table['comment'] . '\'';
        }
        if ($table['engine']) {
            $sql .= ' ENGINE \'' . $table['engine'] . '\'';
        }
        if ($table['collation']) {
            $sql .= ' CHARSET=' . explode('_', $table['collation'])[0];
        }
        if ($sql && $table['name']) {
            $sql = ' ALTER TABLE `' . $table['name'] . '` ' . $sql;
            return $sql;
        } else {
            return false;
        }
    }

    public function del_table(array $table)
    {
        if ($table['name']) {
            $sql = "DROP TABLE `" . $table['name'] . '`';
            return self::excuteSql($sql, 'del_table', $table['name']);
        } else {
            return false;
        }
    }

    /**
     * 列操作
     * @param array $column
     * @param array $table
     * @param string $position
     * @return bool [type]         [description]
     * @internal param $ [type] $column [description]
     * @internal param $ [type] $table  [description]
     */
    public function alter_cloumn_add(array $column, array $table, $position = '')
    {
        if ($table['name'] && $column['name'] && $column['value']) {
            $sql = 'ALTER TABLE `' . $table['name'] . '` ADD `' . $column['name'] . '` ' . $column['value'] . ' ' . $position;
            return self::excuteSql($sql, 'alter_cloumn_add', $column['name']);
        } else {
            return false;
        }
    }

    public function alter_cloumn_del(array $column, array $table)
    {
        if ($table['name'] && $column['name']) {
            $sql = 'ALTER TABLE `' . $table['name'] . '` DROP COLUMN `' . $column['name'] . '`';
            return self::excuteSql($sql, 'alter_cloumn_del', $column['name']);
        } else {
            return false;
        }
    }

    public function alter_cloumn_edit(array $column, array $table, $position = '')
    {
        if (stripos($column['value'], 'PRIMARY KEY')) {
            $column['value'] = str_replace('PRIMARY KEY', '', $column['value']);
        }
        $sql = 'ALTER TABLE `' . $table['name'] . '` MODIFY COLUMN `' . $column['name'] . '` ' . $column['value'] . ' ' . $position;
        return self::excuteSql($sql, 'alter_cloumn_edit', $column['name']);
    }

    public function alert_index_del($index_name, $table)
    {
        // $sql='alter table `mp_business_application` add constraint mp_business_application_token unique (`token`)';
        $sql = 'ALTER TABLE `' . $table['name'] . '` drop INDEX ' . $index_name;
        return self::excuteSql($sql, 'alert_index_del', $index_name);
    }

    public function alert_index_add($index, $index_name, $table)
    {
        $columns = '';
        foreach ($index['columns'] as $value) {
            $columns .= '`' . $value . '`,';
        }
        $columns = rtrim($columns, ',');
        if ($index['non_unique']) {
            $sql = 'ALTER TABLE `' . $table['name'] . '` ADD INDEX `' . $index_name . '` (' . $columns . ')';
        } else {
            $sql = 'ALTER TABLE `' . $table['name'] . '` ADD CONSTRAINT `' . $index_name . '` unique (' . $columns . ')';
        }
        return self::excuteSql($sql, 'alert_index_add', $index_name);
    }

}
