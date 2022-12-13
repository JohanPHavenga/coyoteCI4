<?php

namespace App\Controllers;

class File extends BaseController
{
    protected $file_model, $race_model;

    public function __construct()
    {
        $this->file_model = model(FileModel::class);
        $this->race_model = model(RaceModel::class);
        helper('encrypt');
    }

    // public function _remap($method, $params = array())
    // {
    //     if (method_exists(__CLASS__, $method)) {
    //         $this->$method($params);
    //     } else {
    //         d($method);
    //         d($params);
    //         $this->handler($method, $params);
    //     }
    // }

    public function handler($linked_to, $edition_slug, $filetype_name, $file_name = null, $race_filename = null)
    {
        $file_id = false;
        if (is_numeric($filetype_name)) {
            $file_id = $filetype_name;
        } else {
            $filetype_name = str_replace(" ", "_", urldecode($filetype_name));
            // $filetype_name = str_replace("-", "_", urldecode($filetype_name));
        }


        switch ($linked_to) {
            case "edition":
                $file_id = null;
                $e=$this->edition_model->get_edition_id_from_slug($edition_slug);
                $edition_id = $e['edition_id'];
                $file_list = $this->file_model->list("edition", $edition_id, true);
                $filetype_list = $this->file_model->get_filetype_list();
                // dd($filetype_list);
                $filetype_id = $filetype_list[$filetype_name];
                if ($file_list[$filetype_id]) {
                    foreach ($file_list[$filetype_id] as $key => $file_detail) {
                        if ($file_detail['file_name'] == $file_name) {
                            $file_id = $file_detail['file_id'];
                        }
                    }
                }
                break;
            case "race":
                $race_name = $file_name;

                $file_id = null;
                $edition_sum = $this->edition_model->get_edition_id_from_slug($edition_slug);
                $this->data_to_views['race_list'] = $this->race_model->list($edition_sum['edition_id']);

                // d($race_filename);
                // d($this->data_to_views['race_list']);
                foreach ($this->data_to_views['race_list'] as $race_id => $race) {
                    if ($race_name == url_title($race['race_name'])) {
                        break;
                    }
                }
                //                echo $race_id;
                $file_list = $this->file_model->list("race", $race_id, true);
                $filetype_list = $this->file_model->get_filetype_list();
                $filetype_id = $filetype_list[$filetype_name];
                foreach ($file_list[$filetype_id] as $key => $file_detail) {
                    if ($file_detail['file_name'] == $race_filename) {
                        $file_id = $file_detail['file_id'];
                    }
                }
                break;
            default:
                // decrypt the file ID
                $file_id = my_decrypt($file_id);
                break;
        }
        //    d($file_id);
        //    d($file_list);
        //    d($filetype_list);
        //    dd($filetype_id);
        // check for INT
        if (!preg_match('/^\d+$/', $file_id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File could not be found');
        }
        // Get details
        $file_detail = $this->file_model->detail($file_id);

        // If there is no details
        if (!$file_detail) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('No file found with that file ID');
        }
        // get ID type
        $id_type = $file_detail['file_linked_to'];
        $id = $file_detail['linked_id'];
        // add race id section here
        // set path
        $path = "./uploads/" . $id_type . "/" . $id . "/" . $file_detail['file_name'];

        // echo file_exists($path);
        // dd($path);

        if (file_exists($path)) {
            switch ($file_detail['file_ext']) {
                case ".xls":
                case ".xlsx":
                    //                    die("oops");
                    header("X-Robots-Tag: noindex, nofollow", true);
                    // We'll be outputting a XLS
                    header('Content-Type: application/xls');
                    // It will be called filename
                    header('Content-Disposition: attachment; filename="' . $file_detail['file_name'] . '"');
                    // The file source
                    readfile($path);
                    break;
                default:
                    $this->display($file_detail['file_type'], $path, $file_detail['file_name']);
                    break;
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File could not be found');
        }
    }

    public function download($path)
    {
        // force_download($path, NULL);
    }

    public function display($mime, $filepath, $file_name)
    {
        header('Content-Length: ' . filesize($filepath));
        header("Content-Type: $mime");
        header('Content-Disposition: inline; filename="' . $file_name . '";');
        readfile($filepath);
        exit();        
    }
}
