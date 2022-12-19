<?php


class Backup
{
    private $conn;

    function __construct($db)
    {
        $this->conn = $db;
    }

    public function do_backup()
    {
        $tables = array();
        $get_all_table_query = "SHOW TABLES";
        $statement = $this->conn->prepare($get_all_table_query);
        $statement->execute();
        $row = $statement->fetchAll();
        foreach ($row as $table) {
            $tables[] = $table[0];
        }
        $output = '';
        foreach ($tables as $table) {
            $show_table_query = "SHOW CREATE TABLE " . $table . "";
            $statement = $this->conn->prepare($show_table_query);
            $statement->execute();
            $show_table_result = $statement->fetchAll();

            foreach ($show_table_result as $show_table_row) {
                $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
            }
            $select_query = "SELECT * FROM " . $table . "";
            $statement = $this->conn->prepare($select_query);
            $statement->execute();
            $total_row = $statement->rowCount();
            for ($count = 0; $count < $total_row; $count++) {
                $single_result = $statement->fetch(PDO::FETCH_ASSOC);
                $table_column_array = array_keys($single_result);
                $table_value_array = array_values($single_result);
                $output .= "\nINSERT INTO $table (";
                $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
                $output .= "'" . implode("','", $table_value_array) . "');\n";
            }
        }
        $file_name = '../backups/database_backup_on_' . date('y-m-d') .'_'. time() . '.sql';
        $file_handle = fopen($file_name, 'w+');
        fwrite($file_handle, $output);
        fclose($file_handle);
        return true;
    }
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function download_backup($file_name)
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_name));
        //ob_clean();
        flush();
        readfile($file_name);
        exit();
        //unlink($file_name);
    }
    public function delete_backup($filename){
        unlink($filename);
        return true;
    }

    public function do_restore()
    {

    }

    public function backups()
    {

    }


}